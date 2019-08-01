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
            "bajo" => 'required|lt:medio|min:1|max:100',
            "medio" => 'required|lt:alto|min:1|max:100',
            "alto" => 'required|gt:medio|min:1|max:100',
        ];
    }

    // public function attributes()
    // {
    //     return [
    //         'calendar_start' => 'Hora de Inicio',
    //         'calendar_end' => 'Hora de Término',
    //     ];
    // }

    // public function messages()
    // {
    //     return [
    //         'calendar_end.after' => 'Hora de Término debe ser una hora posterior a Hora de Inicio.',
    //     ];
    // }
}
