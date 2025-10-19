<?php
declare(strict_types=1);

namespace App\Templating;

use App\Auth;
use function App\asset;
use function App\csrf_token;
use function App\route;

final class TemplateAdapter
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function render(string $content, array $data = []): string
    {
        $layout = $this->config['app']['layout'] ?? 'default';
        $base = dirname(__DIR__, 2) . '/themes/' . $layout;
        $layoutFile = $base . '/layout.html';
        $partials = $base . '/partials';

        if (!is_file($layoutFile)) {
            throw new \RuntimeException('Layout not found for theme: ' . $layout);
        }

        $slots = [
            '{{ yield:content }}' => $content,
            '{{ yield:alerts }}' => $this->renderAlerts(),
            '{{ yield:head }}' => $data['head'] ?? '',
            '{{ yield:styles }}' => $this->renderAssets('css'),
            '{{ yield:scripts }}' => $this->renderAssets('js'),
        ];

        $html = (string)file_get_contents($layoutFile);

        $html = $this->injectPartials($html, $partials);
        foreach ($slots as $token => $value) {
            $html = str_replace($token, $value, $html);
        }

        $replacements = [
            '{{ site.name }}' => $this->config['app']['site_name'] ?? 'AAC',
            '{{ csrf }}' => csrf_token(),
            '{{ user.name }}' => Auth::user()['username'] ?? 'Guest',
        ];

        $html = $this->replaceRoutes($html);
        $html = $this->replaceAssets($html);

        foreach ($replacements as $token => $value) {
            $html = str_replace($token, htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'), $html);
        }
        return $html;
    }

    private function renderAlerts(): string
    {
        $alerts = $_SESSION['_alerts'] ?? [];
        unset($_SESSION['_alerts']);
        $html = '';
        foreach ($alerts as $alert) {
            $html .= '<div class="alert alert-' . htmlspecialchars($alert['type']) . '">' . htmlspecialchars($alert['message']) . '</div>';
        }
        return $html;
    }

    private function renderAssets(string $type): string
    {
        $assets = $this->config['templates'][$type] ?? [];
        $html = '';
        foreach ($assets as $asset) {
            if ($type === 'css') {
                $html .= '<link rel="stylesheet" href="' . asset($asset) . '">';
            } else {
                $html .= '<script src="' . asset($asset) . '"></script>';
            }
        }
        return $html;
    }

    private function injectPartials(string $html, string $partialsDir): string
    {
        return preg_replace_callback('/{{\s*partial:(.*?)\s*}}/', function ($matches) use ($partialsDir) {
            $name = trim($matches[1]);
            $file = $partialsDir . '/' . $name . '.html';
            if (file_exists($file)) {
                return (string)file_get_contents($file);
            }
            return '';
        }, $html) ?? $html;
    }

    private function replaceRoutes(string $html): string
    {
        return preg_replace_callback('/{{\s*route\(\'([^\']+)\'\)\s*}}/', function ($matches) {
            return route($matches[1]);
        }, $html) ?? $html;
    }

    private function replaceAssets(string $html): string
    {
        return preg_replace_callback('/{{\s*asset\(\'([^\']+)\'\)\s*}}/', function ($matches) {
            return asset($matches[1]);
        }, $html) ?? $html;
    }
}
