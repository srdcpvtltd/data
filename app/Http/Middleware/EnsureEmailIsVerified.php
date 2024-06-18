<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as Middleware;

class EnsureEmailIsVerified extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user()->hasRole('administrator') && setting('email_verification') == 'on') {
            return parent::handle($request, $next, $redirectToRoute);
        }
        return $next($request);
    }
}
