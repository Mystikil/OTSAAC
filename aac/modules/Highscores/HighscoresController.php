<?php
declare(strict_types=1);

namespace App\Modules\Highscores;

use App\Cache;
use App\Controller;
use function App\db;

final class HighscoresController extends Controller
{
    public function index(): string
    {
        $cache = new Cache(dirname(__DIR__, 2) . '/storage/cache');
        $range = $_GET['range'] ?? 'all';
        $key = 'highscores-' . $range;
        $scores = $cache->get($key, 600, function () {
            $stmt = db()->query('SELECT name, level, vocation FROM characters ORDER BY level DESC LIMIT 50');
            return $stmt->fetchAll();
        });
        return $this->render('Highscores/views/index', ['scores' => $scores]);
    }
}
