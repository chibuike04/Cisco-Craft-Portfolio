<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

require_once dirname(__DIR__) . '/load_env.php';

final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    /** Return the shared PDO connection. */
    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = (int) ($_ENV['DB_PORT'] ?? 3306);
        $name = $_ENV['DB_NAME'] ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

        if ($name === '' || $user === '') {
            throw new RuntimeException('Database settings are incomplete. Check DB_NAME and DB_USER in your .env file.');
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $host,
            $port,
            $name,
            $charset
        );

        try {
            self::$connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $exception) {
            throw new RuntimeException(
                'Could not connect to MySQL. Check that MySQL is running and your .env credentials are correct.',
                0,
                $exception
            );
        }

        return self::$connection;
    }

    /** Clear the shared connection (useful in tests). */
    public static function disconnect(): void
    {
        self::$connection = null;
    }
}
