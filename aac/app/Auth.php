<?php
declare(strict_types=1);

namespace App;

use PDO;

final class Auth
{
    private static ?array $user = null;

    public static function attempt(string $email, string $password): bool
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user || !Security::verifyPassword($password, $user['password'])) {
            return false;
        }
        $_SESSION['user_id'] = $user['id'];
        self::$user = $user;
        return true;
    }

    public static function check(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        if (self::$user === null) {
            $stmt = db()->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $_SESSION['user_id']]);
            self::$user = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }
        return self::$user;
    }

    public static function logout(): void
    {
        unset($_SESSION['user_id']);
        self::$user = null;
    }
}
