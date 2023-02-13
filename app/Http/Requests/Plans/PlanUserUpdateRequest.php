<?php

namespace App\Http\Requests\Plans;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use Illuminate\Foundation\Http\FormRequest;

class PlanUserUpdateRequest extends FormRequest
{
    public const DATES_OVERLAPPED_MESSAGE = 'Las fechas chocan con un plan que ya tiene el usuario.';
    public const PLAN_IS_FREEZED_MESSAGE = 'El plan no puede ser actualizado porque está congelado.';
    
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
        // if plan user status is freezed it cannot be updated
        $planUser = PlanUser::find($request->plan_user_id);

        if ($planUser->isFrozen()) {
            return [
                'plan_user_id' => function($attribute, $value, $fail) {
                    if (true) {
                        $fail(self::PLAN_IS_FREEZED_MESSAGE);
                    }
                },
            ];
        }

        /** Carbonized start_date  */
        $startDate = Carbon::parse($request->start_date);
        /** Get the finish date of the actual Plan  */
        $endDate = Carbon::parse($request->finish_date);

        $withBilling = (bool) $request->billed;

        $updatingPlanId = PlanUser::find((int) $request->plan_user_id, ['id']);

        $hasOverlappedDates = app(PlanUser::class)->hasOverlappedDates($this->user, $startDate, $endDate, $updatingPlanId->id);
        
        return [
            'start_date' => ['required', function($attribute, $value, $fail) use ($hasOverlappedDates) {
                if ($hasOverlappedDates) {
                    $fail(self::DATES_OVERLAPPED_MESSAGE);
                }
            }],
            'finish_date' => 'required|after_or_equal:start_date',
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
            'user_id'         => 'usuario',
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
