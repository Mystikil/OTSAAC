<?php
namespace App\Templating;

use App\Auth;
use App\Router;

class TemplateAdapter
{
    private static ?self $instance = null;
    private string $theme;

    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->theme = \App\config('app.layout', 'default');
    }

    public function render(string $view, array $data = []): string
    {
        $themePath = BASE_PATH . '/app/Templating/themes/' . $this->theme;
        $viewPath = BASE_PATH . '/modules/' . $this->normalizeView($view) . '.php';
        if (!file_exists($viewPath)) {
            $viewPath = BASE_PATH . '/resources/views/' . $this->normalizeView($view) . '.php';
        }
        extract($data);
        $csrf = \App\csrf_token();
        $user = Auth::user();
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        $layout = file_get_contents($themePath . '/layout.html');
        $layout = $this->replaceIncludes($layout);
        $replacements = [
            '{{ yield:content }}' => $content,
            '{{ csrf }}' => $csrf,
            '{{ site.name }}' => \App\config('app.site_name', 'AAC Website'),
            '{{ user.name }}' => $user['username'] ?? 'Guest',
        ];
        $replacements['{{ yield:alerts }}'] = $this->alerts();
        $replacements['{{ yield:styles }}'] = $this->includePartial('styles');
        $replacements['{{ yield:scripts }}'] = $this->includePartial('scripts');
        $replacements['{{ yield:head }}'] = $this->includePartial('head');

        $layout = $this->replaceTokens($layout);
        $layout = strtr($layout, $replacements);
        return $layout;
    }

    private function normalizeView(string $view): string
    {
        return str_replace('..', '', str_replace('.', '/', $view));
    }

    private function includePartial(string $name): string
    {
        $file = BASE_PATH . '/app/Templating/themes/' . $this->theme . '/partials/' . $name . '.html';
        if (file_exists($file)) {
            return $this->replaceTokens(file_get_contents($file));
        }
        return '';
    }

    private function alerts(): string
    {
        if (empty($_SESSION['flash'])) {
            return '';
        }
        $alerts = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $output = '';
        foreach ($alerts as $type => $messages) {
            foreach ($messages as $message) {
                $output .= '<div class="alert alert-' . htmlspecialchars($type, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</div>';
            }
        }
        return $output;
    }

    private function replaceTokens(string $content): string
    {
        $content = preg_replace_callback('/{{ route\(([^}]+)\) }}/', function ($matches) {
            $args = array_map('trim', explode(',', $matches[1]));
            $name = trim($args[0], '\'"'"');
            $params = [];
            if (count($args) > 1) {
                $params = json_decode($args[1], true) ?: [];
            }
            return Router::instance()->route($name, $params);
        }, $content);
        $content = preg_replace_callback('/{{ asset\(([^}]+)\) }}/', function ($matches) {
            $arg = trim($matches[1], '\'"'"');
            return \App\asset($arg);
        }, $content);
        return $content;
    }

    private function replaceIncludes(string $content): string
    {
        return preg_replace_callback('/{{ include_partial:([a-zA-Z0-9_\-]+) }}/', function ($matches) {
            $file = BASE_PATH . '/app/Templating/themes/' . $this->theme . '/partials/' . $matches[1] . '.html';
            if (file_exists($file)) {
                return $this->replaceTokens(file_get_contents($file));
            }
            return '';
        }, $content);
    }
}
