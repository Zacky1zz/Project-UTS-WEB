<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var array<int, array{0:string,1:string,2:array{0:class-string,1:string}}> */
    private array $routes = [];

    /** @param array{0:class-string,1:string} $handler  [ControllerClass::class, 'method'] */
    public function add(string $method, string $pattern, array $handler): void
    {
        $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $pattern) . '$#';
        $this->routes[] = [$method, $regex, $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = rtrim(parse_url($uri, PHP_URL_PATH) ?: '/', '/') ?: '/';

        foreach ($this->routes as [$routeMethod, $regex, $handler]) {
            if ($routeMethod === $method && preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                [$class, $action] = $handler;
                (new $class())->$action(...$params);
                return;
            }
        }

        http_response_code(404);
        echo '<h1>404 - Halaman tidak ditemukan</h1><p><a href="/">Kembali ke beranda</a></p>';
    }
}
