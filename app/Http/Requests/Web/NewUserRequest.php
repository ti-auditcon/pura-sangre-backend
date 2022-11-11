<?php

namespace App\Http\Requests\Web;

use App\Models\Users\User;
use Illuminate\Foundation\Http\FormRequest;

class NewUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return  array
     */
    public function rules()
    {
        return [
            'rut'        => 'required',
            'first_name' => 'required',
            'last_name'  => 'required',
            'birthdate'  => 'required',
            'email'      => ['required', 'email', function($attribute, $value, $fail) {
                if ($this->emailIsAlreadyRegistered(request('email'))) {
                    return $fail('El :attribute ya ha sido registrado.');
                }
            }],
            'phone'      => $this->phone ? 'digits:8' : '',
        ];
    }

    /**
     * @return  boolean
     */
    public function emailIsAlreadyRegistered($email)
    {
        return User::where('email', $email)->exists('id');
    }

    /**
     * 
     */
    public function messages()
    {
        return [
            'first_name.required' => 'El campo nombre es obligatorio',
            'last_name.required'  => 'El campo apellido es obligatorio',
            'rut.required'        => 'El campo rut es obligatorio',
            'birthdate.required'  => 'El campo fecha de nacimiento es obligatorio',
            'email.required'      => 'El campo correo electronico es obligatorio',
            'email.email'         => 'El campo correo electronico debe contener un correo valido',
            'phone.digits'        => 'El numero de telefono debe contener solo :digits numeros',
        ];        
    }
}
