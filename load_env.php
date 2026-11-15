<?php

declare(strict_types=1);

$autoloadPath = __DIR__ . '/vendor/autoload.php';

if (!is_file($autoloadPath)) {
    throw new RuntimeException(
        'Composer dependencies are missing. Run "composer install" from the project root first.'
    );
}

require_once $autoloadPath;

$envPath = __DIR__ . '/.env';

if (!is_file($envPath)) {
    throw new RuntimeException(
        '.env file is missing from the project root.'
    );
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();