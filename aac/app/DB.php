<?php
declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

final class DB
{
    private static ?PDO $connection = null;

    public static function configure(array $config): void
    {
        $_SESSION['_db_config'] = $config;
    }

    public static function getConnection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }
        $config = $_SESSION['_db_config'] ?? require dirname(__DIR__) . '/config/database.php';
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['dbname']);
        try {
            self::$connection = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }
        return self::$connection;
    }
}
