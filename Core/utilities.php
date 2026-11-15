<?php

declare(strict_types=1);

if (!function_exists('e')) {
    /** Escape dynamic content before outputting it into HTML. */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('app_config')) {
    /** Read all application configuration or a dot-notated configuration value. */
    function app_config(?string $key = null, mixed $default = null): mixed
    {
        static $config = null;
        $config ??= require __DIR__ . '/config.php';

        if ($key === null || $key === '') {
            return $config;
        }

        $value = $config;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('url')) {
    /** Generate an absolute URL using APP_URL as the application base. */
    function url(string $path = ''): string
    {
        $baseUrl = rtrim((string) app_config('app.url', 'http://localhost'), '/');
        return $baseUrl . ($path === '' ? '' : '/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    /** Redirect to an application path and stop execution. */
    function redirect(string $path, int $statusCode = 302): never
    {
        if (preg_match('~^https?://~i', $path)) {
            $destination = $path;
        } else {
            $destination = url($path);
        }

        header('Location: ' . $destination, true, $statusCode);
        exit;
    }
}
