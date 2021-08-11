<?php

namespace App\Http\Requests\Plans;

use App\Models\Plans\PostponePlan;
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
            'start_freeze_date' => 'required',
            'end_freeze_date' => 'required | after_or_equal:start_freeze_date'
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
            'start_freeze_date.required'        => 'Se requiere ingresar una fecha de inicio.',
            'end_freeze_date.required'          => 'Se requiere ingresar una fecha de término.',
            'end_freeze_date.after_or_equal'    => 'La fecha de término del congelamiento debe ser igual o mayor a la de inicio.',
        ];
    }
}
