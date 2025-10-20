<?php
ini_set('display_errors', '0');
error_reporting(E_ALL);

define('APP_ROOT', realpath(__DIR__ . '/..'));
define('BASE_PATH', APP_ROOT);
define('PUBLIC_PATH', APP_ROOT . '/public');

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $baseDir = APP_ROOT . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

require_once __DIR__ . '/Helpers.php';

App\load_config();

date_default_timezone_set(App\config('app.timezone', 'UTC'));
