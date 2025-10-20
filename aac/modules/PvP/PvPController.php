<?php
namespace Modules\PvP;

use App\Controller;
use App\DB;

class PvPController extends Controller
{
    public function index(): string
    {
        $pdo = DB::connection();
        $stmt = $pdo->query('SELECT * FROM ' . $this->table('pvp_kills') . ' ORDER BY occurred_at DESC LIMIT 50');
        $kills = $stmt->fetchAll();
        return $this->view('PvP/views/index', ['kills' => $kills]);
    }

    private function table(string $name): string
    {
        return \App\config('database.table_prefix', '') . $name;
    }
}
