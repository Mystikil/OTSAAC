<?php
declare(strict_types=1);

namespace App\Modules\Setup;

use App\Controller;
use App\DB;
use App\Security;

final class SetupController extends Controller
{
    private string $flagFile;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->flagFile = dirname(__DIR__, 2) . '/storage/.installed';
    }

    public function index(): string
    {
        if ($this->isInstalled()) {
            return $this->render('Setup/views/locked', []);
        }
        return $this->render('Setup/views/welcome', ['config' => $this->config]);
    }

    public function acceptEula(): string
    {
        $this->assertNotInstalled();
        Security::requireCsrf();

        if (empty($_POST['accept'])) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'You must accept the agreement to continue.'];
            return $this->render('Setup/views/welcome', ['config' => $this->config]);
        }

        $_SESSION['setup'] = $_SESSION['setup'] ?? [];
        $_SESSION['setup']['accepted'] = true;
        return $this->render('Setup/views/database', []);
    }

    public function saveDatabase(): string
    {
        $this->assertNotInstalled();
        Security::requireCsrf();
        $this->assertEulaAccepted();
        $config = [
            'host' => trim((string)($_POST['db_host'] ?? '127.0.0.1')),
            'port' => (int)($_POST['db_port'] ?? 3306),
            'user' => trim((string)($_POST['db_user'] ?? 'root')),
            'pass' => (string)($_POST['db_pass'] ?? ''),
            'dbname' => trim((string)($_POST['db_name'] ?? 'aac')),
            'prefix' => preg_replace('/[^a-zA-Z0-9_]/', '', (string)($_POST['db_prefix'] ?? '')),
        ];
        DB::configure($config);
        $_SESSION['setup']['database'] = $config;

        $schemaVersion = in_array($_POST['schema_version'] ?? '1098', ['1098', '860'], true)
            ? $_POST['schema_version']
            : '1098';
        $this->applyMigrations($schemaVersion);
        return $this->render('Setup/views/admin', []);
    }

    public function createAdmin(): string
    {
        $this->assertNotInstalled();
        Security::requireCsrf();

        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if (!$email || $username === '' || strlen($password) < 8) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Provide a valid email, username, and a password of at least 8 characters.'];
            return $this->render('Setup/views/admin', []);
        }

        $data = [
            'email' => $email,
            'username' => $username,
            'password' => Security::hashPassword($password, $this->config),
            'role' => 'Admin',
        ];
        $pdo = DB::getConnection();
        $stmt = $pdo->prepare('INSERT INTO users (email, username, password, role, is_demo) VALUES (:email, :username, :password, :role, 0)');
        $stmt->execute($data);
        $_SESSION['setup']['admin'] = $data;

        return $this->render('Setup/views/options', ['config' => $this->config]);
    }

    public function saveOptions(): string
    {
        $this->assertNotInstalled();
        Security::requireCsrf();
        $this->assertEulaAccepted();

        $siteName = trim((string)($_POST['site_name'] ?? 'AAC'));
        $timezone = trim((string)($_POST['timezone'] ?? 'UTC'));
        $baseUrl = filter_var($_POST['base_url'] ?? 'http://localhost/aac/public', FILTER_VALIDATE_URL);
        $selectedLayout = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)($_POST['layout'] ?? 'default'));

        if ($siteName === '' || !$baseUrl) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Provide a site name and a valid base URL.'];
            return $this->render('Setup/views/options', ['config' => $this->config]);
        }

        if (!in_array($timezone, timezone_identifiers_list(), true)) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Select a valid PHP timezone identifier.'];
            return $this->render('Setup/views/options', ['config' => $this->config]);
        }

        $availableThemes = $this->getAvailableThemes();
        if (!in_array($selectedLayout, $availableThemes, true)) {
            $selectedLayout = 'default';
        }

        $allowedFeatures = array_keys($this->config['features'] ?? []);
        $requestedFeatures = array_map('strval', $_POST['features'] ?? []);
        $_SESSION['setup']['environment'] = [
            'site_name' => $siteName,
            'timezone' => $timezone,
            'base_url' => $baseUrl,
            'layout' => $selectedLayout,
            'features' => array_values(array_intersect($allowedFeatures, $requestedFeatures)),
        ];

        return $this->render('Setup/views/demo', []);
    }

    public function seedDemo(): string
    {
        $this->assertNotInstalled();
        Security::requireCsrf();
        $this->assertEulaAccepted();
        $this->assertOptionsConfigured();
        if (!empty($_POST['load_demo'])) {
            $seeder = new DemoSeeder(DB::getConnection(), $_SESSION['setup']['database']['prefix'] ?? '');
            $seeder->run();
        }
        return $this->render('Setup/views/finish', []);
    }

    public function finish(): string
    {
        $this->assertNotInstalled();
        Security::requireCsrf();

        $configDir = dirname(__DIR__, 2) . '/config';
        $environment = $_SESSION['setup']['environment'] ?? [];
        $this->assertOptionsConfigured();
        $database = $_SESSION['setup']['database'] ?? [];
        $featuresSelection = $environment['features'] ?? [];

        $appConfig = array_merge($this->config['app'], $environment, ['installed' => true]);
        $this->writeConfig($configDir . '/app.php', $appConfig);
        $this->writeConfig($configDir . '/database.php', array_merge($this->config['database'], $database));

        $featuresConfig = $this->config['features'];
        foreach ($featuresConfig as $feature => $enabled) {
            $featuresConfig[$feature] = in_array($feature, $featuresSelection, true);
        }
        $this->writeConfig($configDir . '/features.php', $featuresConfig);

        file_put_contents($this->flagFile, (string)time());
        unset($_SESSION['setup']);
        $_SESSION['_alerts'][] = ['type' => 'success', 'message' => 'Setup completed successfully'];
        return $this->render('Setup/views/complete', []);
    }

    private function applyMigrations(string $schemaVersion): void
    {
        $schemaPath = dirname(__DIR__) . '/Setup/schema';
        $files = ['base.sql'];
        $files[] = $schemaVersion === '860' ? '860.sql' : '1098.sql';
        $pdo = DB::getConnection();
        $prefix = $_SESSION['setup']['database']['prefix'] ?? '';
        foreach ($files as $file) {
            $sql = (string)file_get_contents($schemaPath . '/' . $file);
            $sql = str_replace('{{prefix}}', $prefix, $sql);
            foreach (array_filter(array_map('trim', explode(';', $sql))) as $statement) {
                if ($statement !== '') {
                    $pdo->exec($statement);
                }
            }
        }
    }

    private function writeConfig(string $path, array $config): void
    {
        $content = '<?php return ' . var_export($config, true) . ';';
        file_put_contents($path, $content);
    }

    private function isInstalled(): bool
    {
        return file_exists($this->flagFile);
    }

    private function assertNotInstalled(): void
    {
        if ($this->isInstalled()) {
            throw new \RuntimeException('Already installed');
        }
    }

    private function assertEulaAccepted(): void
    {
        if (empty($_SESSION['setup']['accepted'])) {
            throw new \RuntimeException('Setup agreement not accepted.');
        }
    }

    private function assertOptionsConfigured(): void
    {
        if (!isset($_SESSION['setup']['environment'])) {
            throw new \RuntimeException('Site options have not been saved.');
        }
    }

    private function getAvailableThemes(): array
    {
        $path = dirname(__DIR__, 2) . '/themes';
        if (!is_dir($path)) {
            return ['default'];
        }
        $entries = array_filter(scandir($path) ?: [], static fn($item) => !in_array($item, ['.', '..'], true));
        $themes = array_values(array_filter(array_map(static function ($item) use ($path) {
            $themePath = $path . '/' . $item;
            return is_dir($themePath) ? $item : null;
        }, $entries)));
        return $themes ?: ['default'];
    }
}
