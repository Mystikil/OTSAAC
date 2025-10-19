<?php
declare(strict_types=1);

namespace App\Modules\Characters;

use App\Auth;
use App\Controller;
use App\Security;
use DateInterval;
use DateTimeImmutable;
use function App\db;

final class CharactersController extends Controller
{
    public function index(): string
    {
        $user = Auth::user();
        $stmt = db()->prepare('SELECT * FROM characters WHERE account_id = :id');
        $stmt->execute(['id' => $user['id']]);
        $characters = $stmt->fetchAll();
        return $this->render('Characters/views/index', ['characters' => $characters]);
    }

    public function create(): string
    {
        Security::requireCsrf();
        $user = Auth::user();
        $name = $_POST['name'] ?? '';
        $vocation = $_POST['vocation'] ?? 'Knight';
        $world = $_POST['world'] ?? 'Default';
        if (!NameRules::validate($name)) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Character name invalid'];
            return $this->index();
        }
        $stmt = db()->prepare('INSERT INTO characters (account_id, name, vocation, level, world, is_demo) VALUES (:account, :name, :vocation, :level, :world, 0)');
        $stmt->execute([
            'account' => $user['id'],
            'name' => $name,
            'vocation' => $vocation,
            'level' => 1,
            'world' => $world,
        ]);
        $_SESSION['_alerts'][] = ['type' => 'success', 'message' => 'Character created'];
        $this->redirect('/characters');
    }

    public function delete(): string
    {
        Security::requireCsrf();
        $id = (int)($_POST['character_id'] ?? 0);
        $user = Auth::user();
        $stmt = db()->prepare('UPDATE characters SET deletion_requested_at = :at WHERE id = :id AND account_id = :account');
        $stmt->execute([
            'at' => (new DateTimeImmutable())->add(new DateInterval('P1D'))->format('Y-m-d H:i:s'),
            'id' => $id,
            'account' => $user['id'],
        ]);
        $_SESSION['_alerts'][] = ['type' => 'info', 'message' => 'Character deletion requested'];
        $this->redirect('/characters');
    }

    public function cancelDelete(): string
    {
        Security::requireCsrf();
        $id = (int)($_POST['character_id'] ?? 0);
        $user = Auth::user();
        $stmt = db()->prepare('UPDATE characters SET deletion_requested_at = NULL WHERE id = :id AND account_id = :account');
        $stmt->execute([
            'id' => $id,
            'account' => $user['id'],
        ]);
        $_SESSION['_alerts'][] = ['type' => 'success', 'message' => 'Character deletion cancelled'];
        $this->redirect('/characters');
    }
}
