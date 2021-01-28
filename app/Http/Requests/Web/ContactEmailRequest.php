<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class ContactEmailRequest extends FormRequest
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
        if (request()->ajax()) {
            return ['error' => 'La solicitud debe ser ajax'];
        }
        // check RECAPTCHA validation
            
        return [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required|max:700'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'El correo debe ser valido.',
            'message.required' => 'El motivo del mensaje es obligatorio.',
        ];
    }
}
