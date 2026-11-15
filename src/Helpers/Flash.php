<?php

declare(strict_types=1);

namespace App\Helpers;

final class Flash
{
    private const SESSION_KEY = '_flash_messages';

    private function __construct()
    {
    }

    public static function set(string $type, string $message): void
    {
        Session::start();
        $_SESSION[self::SESSION_KEY][$type] = $message;
    }

    public static function success(string $message): void
    {
        self::set('success', $message);
    }

    public static function error(string $message): void
    {
        self::set('error', $message);
    }

    /** Retrieve one message and remove it so it is displayed only once. */
    public static function get(string $type): ?string
    {
        Session::start();
        $message = $_SESSION[self::SESSION_KEY][$type] ?? null;
        unset($_SESSION[self::SESSION_KEY][$type]);

        if (($_SESSION[self::SESSION_KEY] ?? []) === []) {
            unset($_SESSION[self::SESSION_KEY]);
        }

        return is_string($message) ? $message : null;
    }

    public static function all(): array
    {
        Session::start();
        $messages = $_SESSION[self::SESSION_KEY] ?? [];
        unset($_SESSION[self::SESSION_KEY]);

        return is_array($messages) ? $messages : [];
    }
}
