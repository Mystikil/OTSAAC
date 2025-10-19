<?php
declare(strict_types=1);

namespace App\Modules\Admin;

use App\Auth;
use App\Controller;
use App\Security;
use function App\db;

final class AdminController extends Controller
{
    public function dashboard(): string
    {
        $this->authorize();
        $metrics = [
            'users' => db()->query('SELECT COUNT(*) AS total FROM users')->fetch()['total'] ?? 0,
            'media_pending' => db()->query('SELECT COUNT(*) AS total FROM media WHERE approved = 0')->fetch()['total'] ?? 0,
            'market_volume' => db()->query('SELECT COUNT(*) AS total FROM market_offers')->fetch()['total'] ?? 0,
        ];
        return $this->render('Admin/views/dashboard', ['metrics' => $metrics]);
    }

    public function users(): string
    {
        $this->authorize();
        $stmt = db()->query('SELECT id, email, username, role, is_demo FROM users');
        $users = $stmt->fetchAll();
        return $this->render('Admin/views/users', ['users' => $users]);
    }

    public function updateRole(): string
    {
        $this->authorize();
        Security::requireCsrf();
        $stmt = db()->prepare('UPDATE users SET role = :role WHERE id = :id');
        $stmt->execute(['role' => $_POST['role'], 'id' => (int)$_POST['user_id']]);
        AuditLog::record(Auth::user()['id'], 'role.update', ['target' => (int)$_POST['user_id'], 'role' => $_POST['role']]);
        $this->redirect('/admin/users');
    }

    public function moderateMedia(): string
    {
        $this->authorize();
        Security::requireCsrf();
        $user = Auth::user();
        $stmt = db()->prepare('UPDATE media SET approved = :approved, approved_by = :user WHERE id = :id');
        $stmt->execute([
            'approved' => (int)($_POST['approved'] ?? 0),
            'user' => $user['id'],
            'id' => (int)$_POST['media_id'],
        ]);
        AuditLog::record($user['id'], 'media.moderate', ['media_id' => (int)$_POST['media_id']]);
        $this->redirect('/admin');
    }

    private function authorize(): void
    {
        $user = Auth::user();
        if (!$user || $user['role'] !== 'Admin') {
            http_response_code(403);
            exit('Forbidden');
        }
    }
}
