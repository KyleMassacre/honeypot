<?php

namespace Larapress\Honeypot\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HoneypotMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        dd($request);
        return $next($request);
    }
}
