<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    // use MyResetPasswordTrait;
    use ResetsPasswords;

    /**
     *  Where to redirect users after resetting their password. 
     *  REDIRIGIR A UN OK SIMPLE
     *
     *  @var  string
     */
    
    protected $redirectTo = RouteServiceProvider::SUCCESS_RESET_PASSWORD;

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email|exists:users',
            'password' => 'required|confirmed|min:6',
        ];
    }


    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'token.required'     => 'No hay un token para un cambio de contraseña.',
            'email.required'     => 'Debe ingresar un email.',
            'email.email'        => 'El formato del email es incorrecto.',
            'password.required'  => 'Debe ingresar una contraseña',
            'password.confirmed' => 'deben coincidir los campos de contraseña',
            'password.min'       => 'la contraseña debe tener un mínimo de :min dígitos',
            'email.exists'       => 'Email incorrecto'
        ];
    }


    // /**
    //  * Create a new controller instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }
}
