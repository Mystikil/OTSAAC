<?php
require __DIR__ . '/../app/bootstrap.php';

use App\Router;

session_start();

$router = new Router();

require __DIR__ . '/../routes.php';

$installed = file_exists(__DIR__ . '/../config/.installed');
if (!$installed && strpos($_SERVER['REQUEST_URI'], '/setup') !== 0) {
    header('Location: /setup');
    exit;
}

try {
    $response = $router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    echo $response;
} catch (Throwable $e) {
    error_log($e->getMessage());
    if (App\config('app.debug', false)) {
        throw $e;
    }
    http_response_code(500);
    echo App\view('errors/500', ['message' => 'An unexpected error occurred.']);
}
