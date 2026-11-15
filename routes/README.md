# Application routes

- `web.php` registers public-facing website routes.
- `admin.php` registers administrator routes.
- `middleware.php` provides a small middleware pipeline wrapper for route handlers.

Each route file returns a registrar closure. The application entry point should load the bootstrap, create an `App\Core\Router`, invoke both registrars, capture the request, and dispatch it.

Example front-controller integration (to be completed when the entry point is wired):

```php
$router = new App\Core\Router();
(require __DIR__ . '/routes/web.php')($router);
(require __DIR__ . '/routes/admin.php')($router);
$router->dispatch(App\Core\Request::capture());
```

Controller classes are intentionally referenced but will be implemented in the Controller stage. Do not dispatch these routes until those controllers exist.

All state-changing routes in these files use CSRF middleware. Authenticated admin routes use authentication middleware; login routes use guest middleware.
