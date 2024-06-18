<?php

namespace App\Http\Middleware;

use Closure;

class ChekForPrivateMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(setting('make_site_private') == 'on') {
            if(!auth()->check()) {
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
