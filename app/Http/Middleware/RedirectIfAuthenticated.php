<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Clases\Clase;


class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(!Session::has('clases-type-id')){
              Session::put('clases-type-id',1);
              Session::put('clases-type-name',Clase::find(1)->clase_type);
            }
            return redirect('/home');
        }

        return $next($request);
    }
}
