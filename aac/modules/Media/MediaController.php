<?php
declare(strict_types=1);

namespace App\Modules\Media;

use App\Auth;
use App\Controller;
use App\Security;
use function App\db;

final class MediaController extends Controller
{
    public function index(): string
    {
        $stmt = db()->query('SELECT * FROM media WHERE approved = 1 ORDER BY created_at DESC');
        $media = $stmt->fetchAll();
        return $this->render('Media/views/index', ['media' => $media]);
    }

    public function upload(): string
    {
        Security::requireCsrf();
        $user = Auth::user();
        if (!isset($_FILES['upload']) || $_FILES['upload']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Upload failed'];
            $this->redirect('/media');
        }
        $file = $_FILES['upload'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','gif'];
        if (!in_array(strtolower($ext), $allowed, true)) {
            $_SESSION['_alerts'][] = ['type' => 'danger', 'message' => 'Invalid file type'];
            $this->redirect('/media');
        }
        $target = 'assets/uploads/' . uniqid('media_', true) . '.' . $ext;
        $path = dirname(__DIR__, 2) . '/public/' . $target;
        move_uploaded_file($file['tmp_name'], $path);
        $stmt = db()->prepare('INSERT INTO media (title, type, path, uploaded_by, approved, is_demo) VALUES (:title, :type, :path, :uploaded, 0, 0)');
        $stmt->execute([
            'title' => $_POST['title'] ?? $file['name'],
            'type' => 'image',
            'path' => $target,
            'uploaded' => $user['id'],
        ]);
        $_SESSION['_alerts'][] = ['type' => 'info', 'message' => 'Upload submitted for moderation'];
        $this->redirect('/media');
    }
}
