<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Helpers\Csrf;
use App\Helpers\Response;

/** Verify CSRF tokens on state-changing requests. */
final class CsrfMiddleware implements MiddlewareInterface
{
    private const SAFE_METHODS = ['GET', 'HEAD', 'OPTIONS'];

    public function handle(Request $request, callable $next): mixed
    {
        if (in_array($request->method(), self::SAFE_METHODS, true)) {
            return $next($request);
        }

        $submittedToken = $request->input('_token');
        if (!is_string($submittedToken) || $submittedToken === '') {
            $headerToken = $request->header('x-csrf-token');
            $submittedToken = is_string($headerToken) ? $headerToken : null;
        }

        if (!Csrf::verify($submittedToken)) {
            if ($this->expectsJson($request)) {
                Response::json([
                    'success' => false,
                    'message' => 'Your security token is invalid or expired. Refresh the page and try again.',
                ], 419);
            }

            Response::text('419 - Page Expired: invalid or missing CSRF token.', 419);
        }

        return $next($request);
    }

    private function expectsJson(Request $request): bool
    {
        $accept = strtolower((string) $request->header('accept', ''));
        $contentType = strtolower((string) $request->header('content-type', ''));
        $requestedWith = strtolower((string) $request->header('x-requested-with', ''));

        return str_contains($accept, 'application/json')
            || str_contains($contentType, 'application/json')
            || $requestedWith === 'xmlhttprequest';
    }
}
