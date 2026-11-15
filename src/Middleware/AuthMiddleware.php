<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Helpers\Auth;
use App\Helpers\Flash;
use App\Helpers\Response;
use App\Models\User;

/** Restrict routes to authenticated and approved administrators. */
final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly string $loginPath = '/admin/login'
    ) {
    }

    public function handle(Request $request, callable $next): mixed
    {
        // Check whether the visitor has an authenticated session.
        if (!Auth::check()) {
            if ($this->expectsJson($request)) {
                Response::json([
                    'success' => false,
                    'message' => 'Authentication is required.'
                ], 401);
            }

            Flash::error('Please log in to continue.');
            \redirect($this->loginPath);
        }

        // Retrieve the currently authenticated user from the database.
        $userId = Auth::id();
        $user = $userId !== null
            ? (new User())->find($userId)
            : null;

        // Only approved administrators can access protected routes.
        if ($user === null || ($user['status'] ?? '') !== 'approved') {
            Auth::logout();

            if ($this->expectsJson($request)) {
                Response::json([
                    'success' => false,
                    'message' => 'Your account is not approved or is no longer active.'
                ], 403);
            }

            Flash::error(
                'Your account has not been approved or your access has been suspended.'
            );

            \redirect($this->loginPath);
        }

        // The authenticated user is approved. Continue to the requested route.
        return $next($request);
    }

    private function expectsJson(Request $request): bool
    {
        $accept = strtolower(
            (string) $request->header('accept', '')
        );

        $requestedWith = strtolower(
            (string) $request->header('x-requested-with', '')
        );

        return str_contains($accept, 'application/json')
            || $requestedWith === 'xmlhttprequest';
    }
}
