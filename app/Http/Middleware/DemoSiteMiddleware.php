<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class DemoSiteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the current route
        $currentRoute = Route::getCurrentRoute();

        $routeUrl = $currentRoute->uri();

        if (!$request->isMethod('GET') &&
            (str_contains($routeUrl, 'ch-admin') ||
            (str_contains($routeUrl, 'livewire') &&
            $request->input('updates.0.payload.method') != 'openChat') &&
            !str_contains($routeUrl, 'ajax')) &&
            env('DEMO_SITE')) {
            abort(403);
        }

        if (str_contains($routeUrl, 'edit-details') &&
            $request->isMethod('PUT') &&
            in_array($request->user()->email, ['demo@chargepanda.com', 'support@chargepanda.com']) &&
            $request->new_password
        ) {
            abort(403);
        }

        return $next($request);
    }
}
