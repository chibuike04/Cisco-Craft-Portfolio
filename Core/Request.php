<?php

declare(strict_types=1);

namespace App\Core;

final class Request
{
    private array $headers;
    private array $input;

    public function __construct(
        private readonly string $method,
        private readonly string $path,
        array $headers = [],
        array $input = []
    ) {
        $this->headers = array_change_key_case($headers, CASE_LOWER);
        $this->input = $input;
    }

    public static function capture(): self
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        // Get the application base URL.
        $appUrl = $_ENV['APP_URL'] ?? getenv('APP_URL') ?: '';

        $basePath = parse_url($appUrl, PHP_URL_PATH) ?: '';

        // Automatically detect the project directory if APP_URL is empty.
        if ($basePath === '') {
            $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';

            $basePath = str_replace(
                '\\',
                '/',
                dirname($scriptName)
            );

            if ($basePath === '.' || $basePath === '/') {
                $basePath = '';
            }
        }

        $basePath = rtrim($basePath, '/');

        // Remove the project subdirectory from the request path.
        if (
            $basePath !== ''
            && (
                $path === $basePath
                || str_starts_with($path, $basePath . '/')
            )
        ) {
            $path = substr($path, strlen($basePath)) ?: '/';
        }

        // Capture and normalize request headers.
        $headers = function_exists('getallheaders')
            ? (getallheaders() ?: [])
            : [];

        $headers = array_change_key_case($headers, CASE_LOWER);

        // Fallback for servers that do not expose all request headers.
        if (!isset($headers['content-type']) && isset($_SERVER['CONTENT_TYPE'])) {
            $headers['content-type'] = $_SERVER['CONTENT_TYPE'];
        }

        if (
            !isset($headers['x-csrf-token'])
            && isset($_SERVER['HTTP_X_CSRF_TOKEN'])
        ) {
            $headers['x-csrf-token'] = $_SERVER['HTTP_X_CSRF_TOKEN'];
        }

        if (
            !isset($headers['x-requested-with'])
            && isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        ) {
            $headers['x-requested-with'] = $_SERVER['HTTP_X_REQUESTED_WITH'];
        }

        if (!isset($headers['accept']) && isset($_SERVER['HTTP_ACCEPT'])) {
            $headers['accept'] = $_SERVER['HTTP_ACCEPT'];
        }

        // Capture GET and POST data.
        $input = $_GET + $_POST;

        $contentType = strtolower(
            (string) ($headers['content-type'] ?? '')
        );

        // Support JSON request bodies.
        if (str_contains($contentType, 'application/json')) {
            $rawBody = file_get_contents('php://input');

            $json = json_decode($rawBody ?: '', true);

            if (is_array($json)) {
                $input = array_replace($input, $json);
            }
        }

        return new self(
            $method,
            self::normalizePath($path),
            $headers,
            $input
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function all(): array
    {
        return $this->input;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->input[$key] ?? $default;
    }

    public function header(string $name, mixed $default = null): mixed
    {
        return $this->headers[strtolower($name)] ?? $default;
    }

    private static function normalizePath(string $path): string
    {
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }
}
