<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // $role is a string that could be 1, 1|2, 1|2|3, etc.
        $roles = explode('|', $role);

        // user has not one of the roles
        if ($request->user()->hasNotRole($roles)) {
            return redirect('/')->with('error', 'No tiene los permisos para realizar esta acción');
        }

        return $next($request);
    }
}
