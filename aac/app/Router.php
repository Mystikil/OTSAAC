<?php
declare(strict_types=1);

namespace App;

use Closure;

final class Router
{
    private array $routes = [];
    private array $middleware = [];
    private array $middlewareStack = [];
    private array $prefixStack = [''];
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get(string $path, callable $handler): self
    {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }

    public function post(string $path, callable $handler): self
    {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

    public function group(string $prefix, callable $callback): self
    {
        $this->prefixStack[] = rtrim(end($this->prefixStack), '/') . $prefix;
        $callback($this);
        array_pop($this->prefixStack);
        return $this;
    }

    public function withMiddleware(string $name, callable $callback): void
    {
        $this->middlewareStack[] = $name;
        $callback($this);
        array_pop($this->middlewareStack);
    }

    public function middleware(string $name, Closure $callback): void
    {
        $this->middleware[$name] = $callback;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH) ?: '/';
        foreach ($this->routes[$method] ?? [] as $pattern => $route) {
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                foreach ($route['middleware'] as $name) {
                    if (isset($this->middleware[$name]) && !$this->middleware[$name]()) {
                        http_response_code(403);
                        echo 'Forbidden';
                        return;
                    }
                }
                $handler = $route['handler'];
                if (is_array($handler) && is_string($handler[0])) {
                    $instance = new $handler[0]($this->config);
                    $handler = [$instance, $handler[1]];
                }
                echo call_user_func_array($handler, $matches);
                return;
            }
        }
        http_response_code(404);
        echo 'Not Found';
    }

    private function addRoute(string $method, string $path, callable $handler): self
    {
        $prefix = end($this->prefixStack);
        $fullPath = rtrim($prefix . '/' . ltrim($path, '/'), '/') ?: '/';
        $pattern = '#^' . str_replace('#', '\#', $fullPath) . '$#';
        $this->routes[$method][$pattern] = [
            'handler' => $handler,
            'middleware' => $this->middlewareStack,
        ];
        return $this;
    }
}
