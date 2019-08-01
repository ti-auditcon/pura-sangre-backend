<?php

namespace App\Http\Requests\Messages;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
          // 'to[]' => 'required',
          'subject' => 'required',
          'text' => 'required',
      ];
    }

   /**
    * [messages description]
    * @return [array] [description]
    */
   public function messages()
   {
     return [
       // 'to[].required' => 'Debe seleccionar al menos un usuario para enviar este correo.',
       'subject.required' => 'El campo asunto es requerido.',
       'text.required' => 'El campo contenido es requerido.',
     ];
   }
 }
