<?php
namespace App;

use PDO;

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $pdo = DB::connection();
        $stmt = $pdo->prepare('SELECT id, password, role FROM ' . self::table('users') . ' WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        if (!$user) {
            return false;
        }
        if (!Security::verifyPassword($password, $user['password'])) {
            return false;
        }
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        return true;
    }

    public static function user(): ?array
    {
        if (empty($_SESSION['user_id'])) {
            return null;
        }
        $pdo = DB::connection();
        $stmt = $pdo->prepare('SELECT id, email, username, role FROM ' . self::table('users') . ' WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user_id']]);
        return $stmt->fetch() ?: null;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['user_id']);
    }

    public static function logout(): void
    {
        session_destroy();
    }

    private static function table(string $name): string
    {
        $prefix = config('database.table_prefix', '');
        return $prefix . $name;
    }
}
