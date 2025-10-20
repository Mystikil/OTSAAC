<?php
namespace Modules\Highscores;

use App\Cache;
use App\Controller;
use App\DB;

class HighscoresController extends Controller
{
    public function index(): string
    {
        $cache = Cache::instance();
        $cached = $cache->get('highscores_overall');
        if (!$cached) {
            $pdo = DB::connection();
            $stmt = $pdo->query('SELECT name, level FROM ' . $this->table('characters') . ' ORDER BY level DESC LIMIT 10');
            $cached = $stmt->fetchAll();
            $cache->set('highscores_overall', $cached, 300);
        }
        return $this->view('Highscores/views/index', ['entries' => $cached]);
    }

    private function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
