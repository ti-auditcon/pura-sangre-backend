<?php

namespace App\Http\Requests\Plans;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PlanUserRequest extends FormRequest
{
    const ADMIN = 1;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole(self::ADMIN);
    }

    /**
     *  Get the validation rules that apply to the request.
     *
     *  @return  array
     */
    public function rules(Request $request)
    {
        // If plan is INVITADO and the counter field is empty, it's required to add counters (class numbers)
        $counterFieldIsRequired = ((int) $request->plan_id === 2 && !$request->counter) ? 'required' : '';

        return [
            'counter' => $counterFieldIsRequired,
            'finish_date' => 'after_or_equal:start_date',
            'plan_id' => 'required'
        ];
    }

    /**
     *  Messages
     *
     *  @return  array
     */
    public function messages()
    {
        return [
            'plan_id.required'           => 'Seleccione un plan para asignar',
            'counter.required'           => 'Campo numero de clases vacío',
            'finish_date.after_or_equal' => 'La fecha de termino del plan debe ser igual o mayor que la fecha de inicio',
        ];
    }
}
