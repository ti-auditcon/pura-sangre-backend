<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Clases\Clase;
use App\Models\Users\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Session;


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
     *  Overrride the failed login response instance,
     *  for a own response instance on Failed Login.
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
