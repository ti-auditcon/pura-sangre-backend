<?php

namespace App\Http\Requests\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Foundation\Http\FormRequest;

class PostponePlanRequestStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_freeze_date' => 'required|date:d-m-Y',
            'end_freeze_date' => 'required|date:d-m-Y|after_or_equal:start_freeze_date',
        ];
    }

    public function attributes()
    {
        return [
            'start_freeze_date' => 'fecha de inicio',
            'end_freeze_date' => 'fecha de término',
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
