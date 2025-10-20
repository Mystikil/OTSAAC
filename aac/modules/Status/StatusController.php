<?php
namespace Modules\Status;

use App\Controller;

class StatusController extends Controller
{
    public function index(): string
    {
        $servers = \App\config('status.servers', []);
        $timeout = \App\config('status.timeout', 3);
        $results = [];
        foreach ($servers as $server) {
            $online = ServerPing::check($server['host'], $server['port'], $timeout);
            $results[] = [
                'name' => $server['name'],
                'online' => $online,
            ];
        }
        return $this->view('Status/views/index', ['servers' => $results]);
    }
}
