<?php

declare(strict_types=1);

namespace App\Helpers;

use RuntimeException;

final class Session
{
    private function __construct()
    {
    }

    /** Start the application session once, using safe cookie defaults. */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        if (headers_sent($file, $line)) {
            throw new RuntimeException("Cannot start the session: output was already sent in {$file}:{$line}.");
        }

        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_strict_mode', '1');

        $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off';

        session_name('cisco_craft_session');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $isSecure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        if (!session_start()) {
            throw new RuntimeException('The application session could not be started.');
        }
    }
}
