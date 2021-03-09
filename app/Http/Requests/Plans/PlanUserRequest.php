<?php

namespace App\Http\Requests\Plans;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PlanUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->hasRole(1);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        // if plan is INVITADO and hasn't counter, the it's need to be add some counters
        $counterFieldIsRequired = ((int)$request->plan_id === 2 && !$request->counter) ? 'required' : '';

        return [
            'counter' => $counterFieldIsRequired,
            'finish_date' => 'after_or_equal:start_date',
        ];
    }

    /**
     *  @return  array
     */
    public function messages()
    {
        return [
            'counter.required'           => 'Campo numero de clases vacío',
            'finish_date.after_or_equal' => 'La fecha de termino del plan debe ser igual o mayor que la fecha de inicio',
        ];
    }

    /**
     *  @return  bool
     */
    public function requestIsAnUpdate()
    {
        return in_array($this->method(), ['PUT', 'PATCH']);
    }
}
