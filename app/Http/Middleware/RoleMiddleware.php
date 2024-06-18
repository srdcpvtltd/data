<?php

namespace App\Http\Middleware;

use Closure;
use \Zizaco\Entrust\Middleware\EntrustRole;

class RoleMiddleware extends EntrustRole
{
    public function handle($request, Closure $next, $roles)
    {
        if (strpos($request->path(), 'admin') !== false) {
            if ($request->getMethod() != 'GET' && $request->user() && $request->user()->username == 'demo') {
                exit('Sorry, but you are not allowed to make changes in the demo site.');
            }
        }

        return parent::handle($request, $next, $roles);
    }
}
