<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Router
{
    /** @var array<int, array{method:string, pattern:string, handler:callable}> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function patch(string $path, callable $handler): void
    {
        $this->add('PATCH', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    public function add(string $method, string $path, callable $handler): void
    {
        $path = '/' . trim($path, '/');
        $path = $path === '/' ? '/' : rtrim($path, '/');

        $quoted = preg_quote($path, '~');
        $pattern = preg_replace_callback(
            '~\\\{([A-Za-z_][A-Za-z0-9_]*)\\\}~',
            static fn (array $matches): string => '(?P<' . $matches[1] . '>[^/]+)',
            $quoted
        );

        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => '~^' . $pattern . '$~',
            'handler' => $handler,
        ];
    }

    /** Match and execute the route for the current request. */
    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = $request->path();
        $pathMatched = false;

        foreach ($this->routes as $route) {
            if (!preg_match($route['pattern'], $path, $matches)) {
                continue;
            }

            $pathMatched = true;

            if ($route['method'] !== $method && !($method === 'HEAD' && $route['method'] === 'GET')) {
                continue;
            }

            $parameters = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            unset($parameters[0]);

            $response = ($route['handler'])($request, $parameters);
            if (is_string($response)) {
                echo $response;
            }

            return;
        }

        if ($pathMatched) {
            http_response_code(405);
            header('Allow: ' . $this->allowedMethods($path));
            echo '405 - Method Not Allowed';
            return;
        }

        http_response_code(404);
        echo '404 - Page Not Found';
    }

    private function allowedMethods(string $path): string
    {
        $methods = [];

        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $path)) {
                $methods[] = $route['method'];
            }
        }

        return implode(', ', array_unique($methods));
    }
}
