<?php

namespace App\Http\Requests\Clases;

use Illuminate\Foundation\Http\FormRequest;

class ClaseTypeStoreRequest extends FormRequest
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
            'clase_type' => 'required',
            'icon_type' => 'required'
        ];
    }

    /**
     * Fields names to show in the error messages
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'clase_type' => 'Nombre',
            'icon' => 'icono para tipo de clase'
        ];
    }
}
