<?php
declare(strict_types=1);

namespace App\Modules\PvP;

use App\Controller;
use function App\db;

final class PvPController extends Controller
{
    public function index(): string
    {
        $stmt = db()->prepare('SELECT * FROM pvp_kills ORDER BY occurred_at DESC LIMIT 50');
        $stmt->execute();
        $kills = $stmt->fetchAll();
        return $this->render('PvP/views/index', ['kills' => $kills]);
    }
}
