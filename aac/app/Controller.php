<?php
namespace App;

abstract class Controller
{
    protected function view(string $view, array $data = []): string
    {
        return view($view, $data);
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }

    protected function validateCsrf(): void
    {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            Security::logIncident('csrf_failure', 'Invalid CSRF token on ' . ($_SERVER['REQUEST_URI'] ?? ''));
            http_response_code(419);
            exit('Invalid CSRF token');
        }
    }
}
