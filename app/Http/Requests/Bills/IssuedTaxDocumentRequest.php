<?php

namespace App\Http\Requests\Bills;

use Illuminate\Foundation\Http\FormRequest;

class IssuedTaxDocumentRequest extends FormRequest
{
    /**
     *  Determine if the user is authorized to make this request.
     *
     *  @return  bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    /**
     *  Get the validation rules that apply to the request.
     *
     *  @return  array
     */
    public function rules()
    {
        return [
            'token' => 'required',
        ];
    }
    
    /**
     *  [messages description]
     *
     *  @return  [type]  [return description]
     */
    public function messages()
    {
        return [
            'token.required' => 'Se necesita un token para esta solicitud'
        ];
    }
}
