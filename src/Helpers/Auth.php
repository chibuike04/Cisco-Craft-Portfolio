<?php

declare(strict_types=1);

namespace App\Helpers;

final class Auth
{
    private const SESSION_KEY = 'auth_user';

    private function __construct()
    {
    }

    /** Store only the non-sensitive user details needed by the application. */
    public static function login(array $user): void
    {
        Session::start();

        if (!isset($user['id'])) {
            throw new \InvalidArgumentException('A user ID is required to establish an authenticated session.');
        }

        session_regenerate_id(true);

        $_SESSION[self::SESSION_KEY] = [
            'id' => (int) $user['id'],
            'name' => isset($user['name']) ? (string) $user['name'] : '',
            'email' => isset($user['email']) ? (string) $user['email'] : '',
            'role_id' => isset($user['role_id']) ? (int) $user['role_id'] : null,
        ];
    }

    public static function check(): bool
    {
        Session::start();
        return isset($_SESSION[self::SESSION_KEY]['id']);
    }

    public static function user(): ?array
    {
        Session::start();
        return $_SESSION[self::SESSION_KEY] ?? null;
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user === null ? null : (int) $user['id'];
    }

    public static function logout(): void
    {
        Session::start();
        unset($_SESSION[self::SESSION_KEY]);
        session_regenerate_id(true);
    }

    /** Redirect unauthenticated visitors to the supplied login path. */
    public static function requireAuth(string $loginPath = '/admin/login'): void
    {
        if (!self::check()) {
            \redirect($loginPath);
        }
    }

    /** Redirect authenticated visitors away from guest-only pages. */
    public static function requireGuest(string $destination = '/admin/dashboard'): void
    {
        if (self::check()) {
            \redirect($destination);
        }
    }
}
