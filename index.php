
<?php

use App\Core\Request;
use App\Core\Router;

// 1. Load application bootstrap.
require_once __DIR__ . '/bootstrap.php';

// 2. Create the router.
$router = new Router();

// 3. Register public routes.
$webRoutes = require __DIR__ . '/routes/web.php';

if (is_callable($webRoutes)) {
    $webRoutes($router);
}

// 4. Register admin routes.
$adminRoutes = require __DIR__ . '/routes/admin.php';

if (is_callable($adminRoutes)) {
    $adminRoutes($router);
}

// 5. Capture the incoming request.
$request = Request::capture();

// 6. Dispatch the request.
$router->dispatch($request);
