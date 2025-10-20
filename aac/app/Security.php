<?php
namespace App;

use DateTimeImmutable;

class Security
{
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function requireRateLimit(string $key, int $attempts = 5, int $seconds = 60): bool
    {
        $cacheKey = 'rate_' . $key;
        $cache = Cache::instance();
        $entry = $cache->get($cacheKey);
        $now = time();
        if (!$entry) {
            $cache->set($cacheKey, ['count' => 1, 'expires' => $now + $seconds], $seconds);
            return true;
        }
        if ($entry['expires'] < $now) {
            $cache->set($cacheKey, ['count' => 1, 'expires' => $now + $seconds], $seconds);
            return true;
        }
        if ($entry['count'] >= $attempts) {
            return false;
        }
        $entry['count']++;
        $cache->set($cacheKey, $entry, $entry['expires'] - $now);
        return true;
    }

    public static function logIncident(string $type, string $details): void
    {
        $line = sprintf('[%s] %s: %s', (new DateTimeImmutable())->format('c'), $type, $details) . PHP_EOL;
        file_put_contents(BASE_PATH . '/storage/logs/security.log', $line, FILE_APPEND);
    }
}
