<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Users\Role;

class HasAnyRole
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->roles()->count() || 
            !$request->user()->hasRole([Role::ADMIN, Role::COACH])) {
            return redirect()->route('login')
                ->with('error', 'Acceso denegado. Recuerda que puedes usar la app móvil.');
        }

        return $next($request);
    }
}