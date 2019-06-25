<?php

namespace App\Http\Requests\Plans;

use Illuminate\Foundation\Http\FormRequest;

class PostponePlanRequest extends FormRequest
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
            'start_freeze_date' => 'required|before_or_equal:end_freeze_date',
            'end_freeze_date' => 'required'
        ];
    }

    /**
     * Response messages to Freeze plans form 
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'start_freeze_date.required' => 'Se requiere ingresar una fecha de inicio',
            'start_freeze_date.before' => 'La fecha de inicio no puede ser mayor a la fecha de término',
            'end_freeze_date.required' => 'Se requiere ingresar una fecha de término'
        ];
    }
}
