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
