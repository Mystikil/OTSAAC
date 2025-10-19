<?php
declare(strict_types=1);

namespace App;

final class Security
{
    public static function hashPassword(string $password, array $config): string
    {
        $options = $config['security']['hash'] ?? [];
        return password_hash($password, PASSWORD_ARGON2ID, $options);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function requireCsrf(): void
    {
        $token = $_POST['_csrf'] ?? '';
        if (!verify_csrf($token)) {
            http_response_code(422);
            throw new \RuntimeException('Invalid CSRF token');
        }
    }
}
