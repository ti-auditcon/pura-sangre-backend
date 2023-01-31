<?php

namespace App\Http\Controllers\Auth;

use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Users\Session as UserSession;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * redirectTo method
     *
     * @return string
     */
    public function redirectTo()
    {
        $authenticatedUser = auth()->user();

        if ($authenticatedUser) {
            $auth_roles = $authenticatedUser->roles()->orderBy('role_id')->pluck('id')->toArray();

            view()->share(compact('auth_roles'));

            return '/';
        }
    }

    /**
     * Overrride the failed login response instance,
     * for a own response instance on Failed Login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ( ! User::where('email', $request->email)->first() ) {
            return redirect()->back()
                             ->withInput($request->only($this->username(), 'remember'))
                             ->withErrors([$this->username() => 'Correo o contraseña incorrecta']);
        }

        if ( ! User::where('email', $request->email)->where('password', bcrypt($request->password))->first() ) {
            return redirect()->back()
                             ->withInput($request->only($this->username(), 'remember'))
                             ->withErrors([$this->username() => 'Correo o contraseña incorrecta']);
        }
    }
}
