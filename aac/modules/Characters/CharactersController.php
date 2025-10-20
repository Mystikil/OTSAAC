<?php
namespace Modules\Characters;

use App\Auth;
use App\Controller;
use App\DB;

class CharactersController extends Controller
{
    public function index(): string
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $pdo = DB::connection();
        $stmt = $pdo->prepare('SELECT * FROM ' . $this->table('characters') . ' WHERE account_id = :id');
        $stmt->execute(['id' => Auth::user()['id']]);
        $characters = $stmt->fetchAll();
        return $this->view('Characters/views/index', ['characters' => $characters]);
    }

    public function create(): string
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $name = trim($_POST['name'] ?? '');
            $vocation = trim($_POST['vocation'] ?? '');
            $world = trim($_POST['world'] ?? '');
            if (!NameRules::validate($name)) {
                $_SESSION['flash']['danger'][] = 'Character name invalid.';
            } else {
                $pdo = DB::connection();
                $stmt = $pdo->prepare('INSERT INTO ' . $this->table('characters') . ' (account_id, name, vocation, level, world) VALUES (:account_id, :name, :vocation, :level, :world)');
                $stmt->execute([
                    'account_id' => Auth::user()['id'],
                    'name' => $name,
                    'vocation' => $vocation,
                    'level' => 1,
                    'world' => $world,
                ]);
                $_SESSION['flash']['success'][] = 'Character created.';
                $this->redirect('/characters');
            }
        }
        return $this->view('Characters/views/create');
    }

    public function requestDelete(int $id): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $this->validateCsrf();
        $pdo = DB::connection();
        $stmt = $pdo->prepare('UPDATE ' . $this->table('characters') . ' SET delete_at = DATE_ADD(NOW(), INTERVAL 24 HOUR) WHERE id = :id AND account_id = :account_id');
        $stmt->execute(['id' => $id, 'account_id' => Auth::user()['id']]);
        $_SESSION['flash']['success'][] = 'Deletion scheduled. You can cancel before it completes.';
        $this->redirect('/characters');
    }

    public function cancelDelete(int $id): void
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        $this->validateCsrf();
        $pdo = DB::connection();
        $stmt = $pdo->prepare('UPDATE ' . $this->table('characters') . ' SET delete_at = NULL WHERE id = :id AND account_id = :account_id');
        $stmt->execute(['id' => $id, 'account_id' => Auth::user()['id']]);
        $_SESSION['flash']['success'][] = 'Deletion canceled.';
        $this->redirect('/characters');
    }

    private function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
