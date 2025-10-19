<?php
declare(strict_types=1);

namespace App;

use App\Templating\TemplateAdapter;

final class View
{
    private TemplateAdapter $adapter;

    public function __construct(private array $config)
    {
        $this->adapter = new TemplateAdapter($config);
    }

    public function render(string $template, array $data = []): string
    {
        $templateFile = $this->resolveTemplate($template);
        if (!file_exists($templateFile)) {
            throw new \RuntimeException('Template not found: ' . $templateFile);
        }
        extract($data, EXTR_SKIP);
        ob_start();
        include $templateFile;
        $content = ob_get_clean();
        return $this->adapter->render($content, $data);
    }

    private function resolveTemplate(string $template): string
    {
        $template = trim($template, '/');
        $path = dirname(__DIR__) . '/modules/' . $template . '.php';
        if (file_exists($path)) {
            return $path;
        }
        $segments = explode('/', $template);
        $module = array_shift($segments);
        $file = implode('/', $segments);
        return dirname(__DIR__) . '/modules/' . ucfirst($module) . '/views/' . $file . '.php';
    }
}
