<?php

namespace App\Http\Requests\Clases;

use Illuminate\Foundation\Http\FormRequest;

class BlockRequest extends FormRequest
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
               'coach_id' => 'required',
               'day' => 'required',
               'start' => 'required',
               'end' => 'required',
               'quota' => 'required',
               // 'last_name' => 'required',
               // 'email' => 'required|email|unique:users',
               // 'phone' => $this->phone != null ? 'digits:8': '',
             ];
           }
           // case 'PUT': {
           //   if($this->route('user')->email != $this->email){
           //     $case = '|unique:users,email';
           //   }
           //   else {
           //     $case = '';
           //   }
           //   return [
           //     'first_name' => 'required',
           //     'last_name' => 'required',
           //     'email' => 'required|email'.$case,
           //     'phone' => $this->phone != null ? 'digits:8': '',
           //   ];
           // }
           default:break;
         }
    }

    public function messages()
    {
        return [
           'coach_id.required' => 'Debe seleccionar un profesor.',
           'day.required' => 'Debe elegir al menos un día.',
           'start.required' => 'Seleccione una hora de inicio.',
           'end.required' => 'Seleccione una hora de término.',
           'quota.required' => 'Ingrese un número de clases.',
        ];
    }
}
