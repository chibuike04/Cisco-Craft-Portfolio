<?php

declare(strict_types=1);

use App\Core\Request;
use App\Middleware\MiddlewareInterface;

/**
 * Wrap a route handler in an ordered middleware pipeline.
 * Middleware is executed in the order supplied.
 *
 * @param callable(Request, array<string, string>): mixed $handler
 * @param list<MiddlewareInterface> $middleware
 */
return static function (callable $handler, array $middleware = []): callable {
    return static function (Request $request, array $parameters = []) use ($handler, $middleware): mixed {
        $destination = static fn (Request $currentRequest): mixed => $handler($currentRequest, $parameters);

        foreach (array_reverse($middleware) as $layer) {
            $next = $destination;
            $destination = static fn (Request $currentRequest): mixed => $layer->handle($currentRequest, $next);
        }

        return $destination($request);
    };
};
