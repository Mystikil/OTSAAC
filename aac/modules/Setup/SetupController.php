<?php
namespace Modules\Setup;

use App\Controller;
use App\DB;
use App\Security;
use App\view;

class SetupController extends Controller
{
    public function index(): string
    {
        if ($this->isInstalled()) {
            return $this->view('Setup/views/already-installed');
        }
        $step = $_GET['step'] ?? 'welcome';
        return match ($step) {
            'environment' => $this->environment(),
            'database' => $this->database(),
            'admin' => $this->admin(),
            'demo' => $this->demo(),
            'finish' => $this->finish(),
            default => $this->welcome(),
        };
    }

    private function welcome(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            if (empty($_POST['agree'])) {
                $_SESSION['flash']['danger'][] = 'You must accept the agreement to continue.';
            } else {
                header('Location: /setup?step=environment');
                exit;
            }
        }
        return $this->view('Setup/views/welcome');
    }

    private function environment(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $_SESSION['setup']['environment'] = [
                'site_name' => trim($_POST['site_name']),
                'timezone' => trim($_POST['timezone']),
                'base_url' => trim($_POST['base_url']),
                'layout' => trim($_POST['layout']),
            ];
            header('Location: /setup?step=database');
            exit;
        }
        return $this->view('Setup/views/environment');
    }

    private function database(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $_SESSION['setup']['database'] = [
                'host' => trim($_POST['host']),
                'port' => trim($_POST['port']),
                'user' => trim($_POST['user']),
                'pass' => trim($_POST['pass']),
                'dbname' => trim($_POST['dbname']),
                'table_prefix' => trim($_POST['table_prefix']),
                'schema_version' => $_POST['schema_version'],
            ];
            header('Location: /setup?step=admin');
            exit;
        }
        $schemas = ['1098' => '10.98', '860' => '8.60'];
        return $this->view('Setup/views/database', ['schemas' => $schemas]);
    }

    private function admin(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $_SESSION['setup']['admin'] = [
                'email' => trim($_POST['email']),
                'username' => trim($_POST['username']),
                'password' => Security::hashPassword($_POST['password']),
                'two_factor' => !empty($_POST['two_factor'])
            ];
            header('Location: /setup?step=demo');
            exit;
        }
        return $this->view('Setup/views/admin');
    }

    private function demo(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $_SESSION['setup']['demo'] = [
                'load_demo' => !empty($_POST['load_demo'])
            ];
            header('Location: /setup?step=finish');
            exit;
        }
        return $this->view('Setup/views/demo');
    }

    private function finish(): string
    {
        $this->applyConfiguration();
        return $this->view('Setup/views/finish');
    }

    private function applyConfiguration(): void
    {
        if ($this->isInstalled()) {
            return;
        }
        $env = $_SESSION['setup']['environment'] ?? [];
        $db = $_SESSION['setup']['database'] ?? [];
        $admin = $_SESSION['setup']['admin'] ?? [];
        if (!$env || !$db || !$admin) {
            return;
        }
        $this->writeConfig('app.php', array_merge(require BASE_PATH . '/config/app.php', $env, ['debug' => false]));
        $this->writeConfig('database.php', $db);

        $pdo = new \PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['port'], $db['dbname']), $db['user'], $db['pass']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->runMigrations($pdo, $db['table_prefix'], $db['schema_version']);
        $this->createAdmin($pdo, $db['table_prefix'], $admin);
        if (!empty($_SESSION['setup']['demo']['load_demo'])) {
            (new DemoSeeder($pdo, $db['table_prefix']))->seed();
        }
        file_put_contents(BASE_PATH . '/config/.installed', 'installed');
        unset($_SESSION['setup']);
    }

    private function runMigrations(\PDO $pdo, string $prefix, string $version): void
    {
        $this->runFile($pdo, $prefix, BASE_PATH . '/modules/Setup/schema/base.sql');
        $this->runFile($pdo, $prefix, BASE_PATH . '/modules/Setup/schema/' . $version . '.sql');
    }

    private function runFile(\PDO $pdo, string $prefix, string $file): void
    {
        $sql = str_replace('{{prefix}}', $prefix, file_get_contents($file));
        foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
            if ($statement) {
                $pdo->exec($statement);
            }
        }
    }

    private function createAdmin(\PDO $pdo, string $prefix, array $admin): void
    {
        $stmt = $pdo->prepare('INSERT INTO ' . $prefix . 'users (email, username, password, role) VALUES (:email, :username, :password, :role)');
        $stmt->execute([
            'email' => $admin['email'],
            'username' => $admin['username'],
            'password' => $admin['password'],
            'role' => 'Admin'
        ]);
    }

    private function writeConfig(string $file, array $data): void
    {
        $content = "<?php\nreturn " . var_export($data, true) . ";\n";
        file_put_contents(BASE_PATH . '/config/' . $file, $content);
    }

    private function isInstalled(): bool
    {
        return file_exists(BASE_PATH . '/config/.installed');
    }
}
