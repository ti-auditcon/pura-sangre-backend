<?php

namespace App\Http\Requests\Users;

use App\Rules\RutUnique;
use App\Rules\RutValidate;
use App\Models\Users\User;
use Illuminate\Validation\Rule;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole(1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // dd($this->contact_phone);
        switch ($this->method()){
            case 'POST': {
                return [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'rut' => [new RutValidate, new RutUnique],
                    'email' => 'required|email|unique:users',
                    'since' => 'after_or_equal:' . today(),
                    'phone' => $this->phone != null ? 'digits:8': '',
                    'contact_phone' => $this->contact_phone ? 'digits:8' : ''
                ];
            }

            case 'PUT': {
                $case = $this->route('user')->email != $this->email ?
                        '|unique:users,email' :
                        null;

                $required = auth()->user()->hasRole(1) ?
                            'required' :
                            '';

                return [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'rut' => [new RutUnique($this->user), new RutValidate],
                    'image' => 'mimes:jpeg,png|max:1024',
                    'email' => $required.'|email'.$case,
                    'phone' => $this->phone != null ? 'digits:8': '',
                    'contact_phone' => $this->contact_phone ? 'digits:8' : ''
                ];
           }
           default:break;
        }
    }

   /**
    * [messages description]
    * @return [array] [description]
    */
    public function messages()
    {
        return [
            'first_name.required' => 'Debe ingresar un nombre.',
            'last_name.required' => 'Debe ingresar un apellido.',
            'email.required' => 'Debe ingresar un e-mail.',
            'email.email' => 'El formato del email es incorrecto.',
            'email.unique' => 'El email ya ha sido tomado.',
            'phone.digits' => 'El número de teléfono debe contener :digits dígitos de solo números.',
            'image.mimes' => 'El formato de imagen debe ser jpeg o png',
            'image.max' => 'La imagen no debe se mas grande que 1 MB',
            'contact_phone.digits' => 'El campo Teléfono de contacto de emergencia debe contener :digits dígitos de solo números.',
            'after_or_equal' => 'La fecha del campo Atleta desde no puede ser menor a hoy'
        ];
    }
 }

