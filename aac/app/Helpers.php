<?php
namespace App;

use App\Templating\TemplateAdapter;

function load_config(): void
{
    global $appConfig;
    $configFiles = glob(BASE_PATH . '/config/*.php');
    foreach ($configFiles as $file) {
        $name = basename($file, '.php');
        if (str_ends_with($name, '.example')) {
            continue;
        }
        $appConfig[$name] = require $file;
    }
}

function config(string $key, $default = null)
{
    global $appConfig;
    if (!isset($appConfig)) {
        $appConfig = [];
    }
    $segments = explode('.', $key);
    $value = $appConfig;
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

function view(string $name, array $data = []): string
{
    $adapter = TemplateAdapter::instance();
    return $adapter->render($name, $data);
}

function route(string $name, array $params = []): string
{
    return Router::instance()->route($name, $params);
}

function asset(string $path): string
{
    $base = rtrim(config('app.base_url', ''), '/');
    $path = ltrim((string) $path, '/');
    return $base . '/assets/' . $path;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool
{
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
