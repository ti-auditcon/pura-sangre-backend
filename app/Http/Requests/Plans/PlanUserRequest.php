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
        if ((int) $request->plan_id === 2 && !$request->counter) {
            return [
                'counter' => 'required',
            ];
        }

        // if (\in_array($this->method(), ['PUT','PATCH'])) {
        //     if ( $plan->finish_date->lt(today()) ) {
        //     Session::flash('warning', 'No se puede modificar el estado de un plan cuya fecha de término es anterior a hoy');

        //     return view('userplans.show')->with([
        //         'user' => $user,
        //         'plan_user' => $plan
        //     ]);
        // }

        // }
        return [
            //
        ];
    }

    public function messages()
    {
        return [
            'counter.required' => 'Campo numero de clases vacío',
        ];
    }
}
