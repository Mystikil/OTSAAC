<?php
declare(strict_types=1);

namespace App\Modules\Guilds;

use App\Controller;
use function App\db;

final class GuildsController extends Controller
{
    public function index(): string
    {
        $stmt = db()->query('SELECT * FROM guilds ORDER BY name');
        $guilds = $stmt->fetchAll();
        return $this->render('Guilds/views/index', ['guilds' => $guilds]);
    }

    public function show(string $id): string
    {
        $stmt = db()->prepare('SELECT * FROM guilds WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $guild = $stmt->fetch();
        $members = db()->prepare('SELECT * FROM guild_members WHERE guild_id = :id');
        $members->execute(['id' => $id]);
        return $this->render('Guilds/views/show', ['guild' => $guild, 'members' => $members->fetchAll()]);
    }
}
