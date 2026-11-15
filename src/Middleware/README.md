# Cisco Craft middleware

Middleware runs before a route handler and either allows the request to continue or stops it.

- `AuthMiddleware`: requires a logged-in user. Defaults to redirecting browser requests to `/admin/login`; JSON requests receive HTTP 401.
- `GuestMiddleware`: keeps logged-in users away from guest-only pages. Defaults to redirecting them to `/admin/dashboard`.
- `CsrfMiddleware`: verifies `_token` on state-changing requests. It accepts the token from the request input or the `X-CSRF-TOKEN` header. `GET`, `HEAD`, and `OPTIONS` requests are exempt.
- `MiddlewareInterface`: shared contract for middleware classes.

These classes are prepared for route integration. The current `Router` does not yet register or execute middleware; we will add that integration when we build the routes layer.

## Expected usage when routes support middleware

```php
$auth = new \App\Middleware\AuthMiddleware();

$auth->handle($request, function ($request) {
    // Protected route handler runs here.
});
```

For every HTML form that submits with POST, include:

```php
<?= \App\Helpers\Csrf::field() ?>
```

For JSON/fetch requests, send the token in the `X-CSRF-TOKEN` header or as `_token` in the JSON body.
