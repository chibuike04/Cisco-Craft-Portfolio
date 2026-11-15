<?php

declare(strict_types=1);

namespace App\Helpers;

final class Response
{
    private function __construct()
    {
    }

    /** Send a JSON response and stop execution. */
    public static function json(array $data, int $statusCode = 200): never
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        header('X-Content-Type-Options: nosniff');

        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }

    /** Send a plain-text response and stop execution. */
    public static function text(string $message, int $statusCode = 200): never
    {
        http_response_code($statusCode);
        header('Content-Type: text/plain; charset=UTF-8');
        header('X-Content-Type-Options: nosniff');
        echo $message;
        exit;
    }

    /** Redirect to an internal application path and stop execution. */
    public static function redirect(string $path, int $statusCode = 302): never
    {
        \redirect($path, $statusCode);
    }
}
