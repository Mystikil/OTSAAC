<?php
namespace Modules\Guilds;

use App\Auth;
use App\Controller;
use App\DB;

class GuildsController extends Controller
{
    public function index(): string
    {
        $pdo = DB::connection();
        $stmt = $pdo->query('SELECT * FROM ' . $this->table('guilds') . ' ORDER BY name');
        $guilds = $stmt->fetchAll();
        return $this->view('Guilds/views/index', ['guilds' => $guilds]);
    }

    public function create(): string
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $name = trim($_POST['name']);
            $motd = trim($_POST['motd']);
            $pdo = DB::connection();
            $stmt = $pdo->prepare('INSERT INTO ' . $this->table('guilds') . ' (name, motd) VALUES (:name, :motd)');
            $stmt->execute(['name' => $name, 'motd' => $motd]);
            $_SESSION['flash']['success'][] = 'Guild created.';
            $this->redirect('/guilds');
        }
        return $this->view('Guilds/views/create');
    }

    private function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
