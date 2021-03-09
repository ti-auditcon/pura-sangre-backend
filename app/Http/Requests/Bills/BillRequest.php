<?php

namespace App\Http\Requests\Bills;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
{
    /**
     *  Determine if the user is authorized to make this request.
     *
     *  @return  bool
     */
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    /**
     *  Get the validation rules that apply to the request.
     *
     *  @return  array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'payment_type_id' => 'required',
            'plan_user_id' => 'required',
            'amount' => 'required'
        ];
    }
    
    /**
     *  [messages description]
     *
     *  @return  [type]  [return description]
     */
    public function messages()
    {
        return [
            'amount.required' => 'El monto es requerido',
            'amount.numeric' => 'El monto debe contener solo numeros',
            'date.required' => 'La fecha de la boleta es requerida',
            'date.date' => 'La fecha no es valida',
            'payment_type_id.required' => 'Debe elegir un tipo de pago',
            'plan_user_id.required' => 'Falta el plan al cual sera asociado el plan',
        ];
    }
}
