<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;

/** Contract for middleware that may inspect or stop a request before its handler runs. */
interface MiddlewareInterface
{
    /** @param callable(Request): mixed $next */
    public function handle(Request $request, callable $next): mixed;
}
