<?php

namespace Larapress\Honeypot\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Larapress\Honeypot\Facades\Honeypot;

class HoneypotMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(Honeypot::isBot()) {
            $route = Honeypot::redirectTo() ? redirect()->route(Honeypot::redirectTo()) : back();

            return $route
                ->withInput()
                ->withErrors('You have been determined to be a bot');
        }

        return $next($request);
    }
}
