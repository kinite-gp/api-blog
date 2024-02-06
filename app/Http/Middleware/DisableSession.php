<?php

namespace App\Http\Middleware;

use Closure;

class DisableSession
{
    public function handle($request, Closure $next)
    {
        config(['session.driver' => 'array']);
        return $next($request);
    }
}
