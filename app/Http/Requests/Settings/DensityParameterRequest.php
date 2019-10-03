<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class DensityParameterRequest extends FormRequest
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
            "level" => 'required',
            "from" => 'required',
            "to" => 'required',
            "color" => 'required',
        ];
    }

    // /**
    //  * Get custom attributes for validator errors.
    //  *
    //  * @return array
    //  */
    // public function attributes()
    // {
    //     return [
    //         'level' => 'Nivel',
    //         'from' => 'Desde',
    //         'to' => 'Hasta',
    //     ];
    // }

    public function messages()
    {
        return [
            'level.required' => 'El campo nivel es requerido.',
            'from.required' => 'El campo desde es requerido.',
            'to.required' => 'El campo hasta es requerido.',
            'color.required' => 'El campo color es requerido.',
        ];
    }
}
