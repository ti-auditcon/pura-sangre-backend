<?php

namespace App\Http\Requests\Messages;

use Illuminate\Foundation\Http\FormRequest;

class AlertRequest extends FormRequest
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
        return [
            'from' => 'required|date',
            'to' => 'required|date|after:from',
            'message' => 'required'
        ];
    }

   /**
    * [messages description]
    * @return [array] [description]
    */
   public function messages()
   {
        return [
            'from.required' => 'El campo desde es requerido.',
            'from.date' => 'El campo desde debe ser una fecha válida.',
            'to.required' => 'El campo hasta es requerido.',
            'to.date' => 'El campo hasta debe ser una fecha válida.',
            'to.after' => 'El campo hasta debe ser una fecha mayor al campo desde.',
            'message.required' => 'El campo contenido es requerido.',
         ];
   }
}
