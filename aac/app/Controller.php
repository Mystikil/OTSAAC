<?php
declare(strict_types=1);

namespace App;

abstract class Controller
{
    protected View $view;
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->view = new View($config);
    }

    protected function render(string $template, array $data = []): string
    {
        return $this->view->render($template, $data);
    }

    protected function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
