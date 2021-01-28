<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        //Url a la que se le dará acceso en las peticiones
        // return $next($request)->header("Access-Control-Allow-Origin", "*")
        //     ->header("Access-Control-Allow-Methods", "POST") // Métodos que a los que se da acceso
        //     ->header("Access-Control-Allow-Headers", "X-Requested-With, Content-Type"); //Headers de la petición
        
        return $next($request)->header('Access-Control-Allow-Origin', '*')
            ->header("Access-Control-Allow-Methods", "GET, PUT, PATCH, POST") // Métodos que a los que se da acceso
            ->header("Access-Control-Allow-Headers", "X-Requested-With, X-Auth-Token, Origin, Content-Type, X-Requested-With, Access-Control-Request-Method, Access-Control-Request-Headers"); //Headers de la peticiónv
    }
}