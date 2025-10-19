<?php
declare(strict_types=1);

namespace App;

use PDO;

function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function verify_csrf(string $token): bool
{
    return hash_equals($_SESSION['_csrf'] ?? '', $token);
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
}

function route(string $name): string
{
    return $GLOBALS['__route_map'][$name] ?? '#';
}

function register_route(string $name, string $path): void
{
    $GLOBALS['__route_map'][$name] = $path;
}

function db(): PDO
{
    return DB::getConnection();
}
