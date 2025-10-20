<?php
namespace Modules\Media;

use App\Auth;
use App\Controller;
use App\DB;

class MediaController extends Controller
{
    public function index(): string
    {
        $pdo = DB::connection();
        $stmt = $pdo->query('SELECT * FROM ' . $this->table('media') . ' ORDER BY created_at DESC');
        $media = $stmt->fetchAll();
        return $this->view('Media/views/index', ['media' => $media]);
    }

    public function upload(): string
    {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            $title = trim($_POST['title']);
            $url = trim($_POST['url']);
            $pdo = DB::connection();
            $stmt = $pdo->prepare('INSERT INTO ' . $this->table('media') . ' (title, type, url, uploaded_by, path) VALUES (:title, :type, :url, :uploaded_by, :path)');
            $stmt->execute([
                'title' => $title,
                'type' => 'video',
                'url' => $url,
                'uploaded_by' => Auth::user()['id'],
                'path' => null,
            ]);
            $_SESSION['flash']['success'][] = 'Submission received for moderation.';
            $this->redirect('/media');
        }
        return $this->view('Media/views/upload');
    }

    private function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
