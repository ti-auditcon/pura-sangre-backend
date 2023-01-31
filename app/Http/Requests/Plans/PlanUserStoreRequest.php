<?php

namespace App\Http\Requests\Plans;

use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use Illuminate\Foundation\Http\FormRequest;

class PlanUserStoreRequest extends FormRequest
{
    public const DATES_OVERLAPPED_MESSAGE = 'Las fechas chocan con un plan que ya tiene el usuario.';
    
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
    public function rules(Request $request)
    {
        /** Carbonized start_date  */
        $startDate = Carbon::parse($request->start_date);
        /** Get the finish date of the actual Plan  */
        $endDate = Carbon::parse($request->finish_date);

        $user = User::find((int) $request->user_id);
        $withBilling = (bool) $request->billed;

        $hasOverlappedDates = app(PlanUser::class)->hasOverlappedDates($user, $startDate, $endDate);
        
        return [
            'start_date' => ['required', function($attribute, $value, $fail) use ($hasOverlappedDates) {
                if ($hasOverlappedDates) {
                    $fail(self::DATES_OVERLAPPED_MESSAGE);
                }
            }],
            'finish_date' => 'required|after_or_equal:start_date',
            'class_numbers' => 'required',
            'clases_by_day' => 'required',
            'date' => $withBilling ? 'required' : '',
            'amount' => $withBilling ? 'required' : '',
        ];
    }

    /**
     * Name of the fields that will be translated.
     *
     * @return  array
     */
    public function attributes()
    {
        return [
            'plan_id'         => 'plan',
            'counter'         => 'número de clases',
            'clases_by_day'   => 'número de clases por día',
            'class_numbers'   => 'número de clases del plan',
            'start_date'      => 'fecha de inicio',
            'finish_date'     => 'fecha de término',
            'payment_type_id' => 'tipo de pago',
            'date'            => 'fecha',
            'amount'          => 'monto del plan',
            'observations'    => 'observaciones',
        ];
    }

    /**
     * Messages for the validator.
     *
     * @return  array
     */
    public function messages()
    {
        return [
            'finish_date.after_or_equal' => 'La fecha de término debe ser mayor o igual a la fecha de inicio del plan.',
        ];
    }
}
