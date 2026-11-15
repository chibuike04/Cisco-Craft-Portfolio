<?php

declare(strict_types=1);

$autoloadPath = __DIR__ . '/vendor/autoload.php';

if (!is_file($autoloadPath)) {
    throw new RuntimeException(
        'Composer dependencies are missing. Run "composer install" from the project root first.'
    );
}

require_once $autoloadPath;
require_once __DIR__ . '/load_env.php';
require_once __DIR__ . '/Core/utilities.php';
