<?php

declare(strict_types=1);

namespace App\Helpers;

use RuntimeException;

final class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    private function __construct()
    {
    }

    public static function token(): string
    {
        Session::start();

        if (empty($_SESSION[self::SESSION_KEY]) || !is_string($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    /** Return a hidden input suitable for use inside an HTML form. */
    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '">';
    }

    public static function verify(?string $submittedToken): bool
    {
        if ($submittedToken === null || $submittedToken === '') {
            return false;
        }

        Session::start();
        $knownToken = $_SESSION[self::SESSION_KEY] ?? null;

        return is_string($knownToken) && hash_equals($knownToken, $submittedToken);
    }

    /** Verify a submitted token or stop the request with an exception. */
    public static function validate(?string $submittedToken): void
    {
        if (!self::verify($submittedToken)) {
            throw new RuntimeException('Your session token is invalid or expired. Please refresh the page and try again.');
        }
    }
}
