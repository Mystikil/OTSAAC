<?php
declare(strict_types=1);

namespace App\Modules\Status;

final class ServerPing
{
    public static function check(string $host, int $port, int $timeout = 2): array
    {
        $start = microtime(true);
        $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$fp) {
            return ['online' => false, 'latency' => null];
        }
        fclose($fp);
        return ['online' => true, 'latency' => round((microtime(true) - $start) * 1000)];
    }
}
