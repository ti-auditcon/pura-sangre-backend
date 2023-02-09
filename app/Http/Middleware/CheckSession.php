<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Models\Users\Session as UserSession;

class CheckSession
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
        $session = Session::where('id', $request->session()->getId())
                         ->where('user_id', auth()->id())
                         ->first();

        if (!$session) {
            // Return appropriate response if session is not valid
            return redirect()->route('login');
        }

        return $next($request);
    }
}
