<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Helpers\Auth;
use App\Helpers\Response;

/** Keep authenticated users away from guest-only routes such as the login page. */
final class GuestMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly string $destination = '/admin/dashboard'
    ) {
    }

    public function handle(Request $request, callable $next): mixed
    {
        if (Auth::check()) {
            if ($this->expectsJson($request)) {
                Response::json([
                    'success' => false,
                    'message' => 'You are already authenticated.',
                    'redirect' => $this->destination,
                ], 403);
            }

            \redirect($this->destination);
        }

        return $next($request);
    }

    private function expectsJson(Request $request): bool
    {
        $accept = strtolower((string) $request->header('accept', ''));
        $requestedWith = strtolower((string) $request->header('x-requested-with', ''));

        return str_contains($accept, 'application/json') || $requestedWith === 'xmlhttprequest';
    }
}
