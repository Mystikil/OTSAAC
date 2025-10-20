<?php
namespace App;

class Router
{
    private array $routes = [];
    private static ?self $instance = null;

    public function __construct()
    {
        self::$instance = $this;
    }

    public static function instance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function add(string $method, string $path, callable $handler, ?string $name = null, array $middleware = []): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'name', 'middleware');
    }

    public function get(string $path, callable $handler, ?string $name = null, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $name, $middleware);
    }

    public function post(string $path, callable $handler, ?string $name = null, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $name, $middleware);
    }

    public function dispatch(string $uri, string $method)
    {
        $path = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes as $route) {
            if (strtoupper($method) !== $route['method']) {
                continue;
            }
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route['path']);
            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
                array_shift($matches);
                foreach ($route['middleware'] as $middleware) {
                    $result = $middleware($path, $method);
                    if ($result !== null) {
                        return $result;
                    }
                }
                return call_user_func_array($route['handler'], $matches);
            }
        }
        http_response_code(404);
        return view('errors/404', ['path' => $path]);
    }

    public function route(string $name, array $params = []): string
    {
        foreach ($this->routes as $route) {
            if ($route['name'] === $name) {
                $path = $route['path'];
                foreach ($params as $key => $value) {
                    $path = str_replace('{' . $key . '}', $value, $path);
                }
                return $path;
            }
        }
        return '#';
    }
}
