<?php
namespace Modules\Status;

class ServerPing
{
    public static function check(string $host, int $port, int $timeout = 3): bool
    {
        $connection = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if ($connection) {
            fclose($connection);
            return true;
        }
        return false;
    }
}
