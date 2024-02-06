<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
//        return $request->expectsJson() ? null : route('login');

//        dd($request->expectsJson());
        if ($request->expectsJson()) {
            return null;
        } elseif (strpos($request->getRequestUri(), "/api")) {
            return null;
        } else {
            return route('login');
        }
    }
}
