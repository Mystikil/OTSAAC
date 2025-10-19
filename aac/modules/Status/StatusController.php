<?php
declare(strict_types=1);

namespace App\Modules\Status;

use App\Controller;
final class StatusController extends Controller
{
    public function home(): string
    {
        $status = $this->fetchStatus();
        return $this->render('Status/views/home', ['status' => $status]);
    }

    public function status(): string
    {
        $status = $this->fetchStatus();
        return $this->render('Status/views/status', ['status' => $status]);
    }

    private function fetchStatus(): array
    {
        $servers = $this->config['status']['game_servers'] ?? [];
        $results = [];
        foreach ($servers as $server) {
            $check = ServerPing::check($server['host'], (int)$server['port']);
            $results[] = [
                'name' => $server['name'],
                'online' => $check['online'],
                'latency' => $check['latency'],
            ];
        }
        return $results;
    }
}
