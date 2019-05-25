<?php

namespace App\Http\Requests\Users;

use App\Models\Users\User;
use App\Rules\RutUnique;
use Auth;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        switch ($this->method()){
          case 'POST': {
            return [
              'first_name' => 'required',
              'last_name' => 'required',
              'rut' => new RutUnique,
              'email' => 'required|email|unique:users',
              'phone' => $this->phone != null ? 'digits:8': '',
              ];
            }
            case 'PUT': {
                if($this->route('user')->email != $this->email){
                    $case = '|unique:users,email';
                }else {
                    $case = '';
                }
                if (Auth::user()->hasRole(1)) {
                    $required = 'required';
                }else {
                    $required = '';
                }
                return [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'image' => 'mimes:jpeg,png|max:1024',
                    'email' => $required.'|email'.$case,
                    'phone' => $this->phone != null ? 'digits:8': '',
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
       'phone.digits' => 'El número de teléfono debe contener :digits dígitos.',
       'phone.digits' => 'El número de teléfono debe contener :digits dígitos.',
       'image.mimes' => 'El formato de imagen debe ser jpeg o png',
       'image.max' => 'La imagen no debe se mas grande que 1 MB',
     ];
   }
 }

