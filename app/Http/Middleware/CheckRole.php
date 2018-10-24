<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Session;

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
        // dd($request->all());
         if (!Auth::user()->hasRole($role)) {
            Session::flash('error', 'No tiene los permisos para realizar esta acción');
            return redirect('/');
        }
        return $next($request);
    }
}
