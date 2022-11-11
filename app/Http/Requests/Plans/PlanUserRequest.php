<?php

namespace App\Http\Requests\Plans;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PlanUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return  bool
     */
    public function authorize()
    {
        return Auth::user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        // Get plan based on request id
        $plan = Plan::find((int) $request->plan_id);

        // Carbonized start_date
        $start_date = Carbon::parse($request->start_date);

        // Get the finish date of the actual Plan
        $finish_date = Carbon::parse($request->start_date);

        $user_plans = PlanUser::whereIn('plan_status_id', [PlanStatus::ACTIVO, PlanStatus::PRECOMPRA])
                            ->where('user_id', (int) $request->user_id)
                            ->when($this->method() === 'PUT', function ($query) use ($request) {
                                $plan_user = PlanUser::find((int) $request->plan_user_id, ['id']);

                                return $query->where('id', '!=', $plan_user->id);
                            })
                            ->get();

        foreach ($user_plans as $plan) {
            $res = $this->planDatesOverlap($start_date, $finish_date, $plan);

            if ($res) {
                return $res;
            }
        }

        return [
            'class_numbers' => $this->method() === 'PUT' ? '' : 'required',
            'clases_by_day' => $this->method() === 'PUT' ? '' : 'required',
            'finish_date' => 'after_or_equal:start_date',
        ];
    }

    /**
     * Calculate when would finish the plan User.
     *
     * @param   [type]  $plan     [$plan description]
     * @param   [type]  $request  [$request description]
     *
     * @return  Carbon instance
     */
    public function calculateFinishDate($plan, $request)
    {
        if ($plan->custom || $this->method() === 'PUT') {
            return Carbon::parse($request->finish_date);
        }

        if ($plan->id === Plan::PRUEBA) {
            // For "plan de prueba" the period_plan_id gonna be the days to add to the start_date
            return Carbon::parse($request->start_date)->assDays($plan->plan_period_id);
        }

        return Carbon::parse($request->start_date)
                     ->addMonths($plan->plan_period->period_number)
                     ->subDay();
    }

    /**
     * Check if plan dates overlapping.
     *
     * @param   [type]  $fecha_inicio        of the plan that will be created
     * @param   [type]  $fecha_termino       of the plan that will be created
     * @param   [type]  $actualOrFuturePlan  of the requested plan
     *
     * @return  array|void
     */
    private function planDatesOverlap($fecha_inicio, $fecha_termino, $actualOrFuturePlan)
    {
        /** Check if plan dates are in beetwen to some othe plan */
        $planDatesAreInBetween = $this->planDatesInBetween(
            $fecha_inicio, $fecha_termino, $actualOrFuturePlan
        );
        if ($planDatesAreInBetween) {
            return $planDatesAreInBetween;
        }

        /** Check if plan dates are before to some othe plan */
        $planDatesCollidesBeforeRequestPlan = $this->planDatesBeforeRequestPlan(
            $fecha_inicio, $fecha_termino, $actualOrFuturePlan
        );
        if ($planDatesCollidesBeforeRequestPlan) {
            return $planDatesCollidesBeforeRequestPlan;
        }

        /** Check if plan dates are after to some othe plan */
        $planDatesCollidesAfterRequestPlan = $this->planDatesAfterRequestPlan(
            $fecha_inicio, $fecha_termino, $actualOrFuturePlan
        );
        if ($planDatesCollidesAfterRequestPlan) {
            return $planDatesCollidesAfterRequestPlan;
        }
    }

    /**
     * Check if the start and / or end date of the plan that will be created
     * is within the date of the plan.
     *
     * @param   [type]  $fecha_inicio        of the plan that will be created
     * @param   [type]  $fecha_termino       of the plan that will be created
     *
     * @return  array|void
     */
    private function planDatesInBetween($fecha_inicio, $fecha_termino, $requestedPlan)
    {
        if (($fecha_inicio->between($requestedPlan->start_date, $requestedPlan->finish_date)) ||
            ($fecha_termino->between($requestedPlan->start_date, $requestedPlan->finish_date))
           ) {
            return [
                'start_date' => function ($attribute, $value, $fail) {
                    if (true) {
                        $fail('La fecha de inicio o término choca con un plan que ya tiene el usuario.');
                    }
                },
            ];
        }
    }

    /**
     * [planDatesBeforeRequestPlan description].
     *
     * @param   [type]  $fecha_inicio        of the plan that will be created
     * @param   [type]  $fecha_termino       of the plan that will be created
     *
     * @return  array|void
     */
    private function planDatesBeforeRequestPlan($fecha_inicio, $fecha_termino, $requestedPlan)
    {
        if (($fecha_inicio->lt($requestedPlan->start_date)) &&
             ($fecha_termino->gte($requestedPlan->start_date))
           ) {
            return [
                'start_date' => function ($attribute, $value, $fail) {
                    if (true) {
                        $fail('La fecha de inicio o término choca con un plan que ya tiene el usuario.');
                    }
                },
            ];
        }
    }

    /**
     * [planDatesAfterRequestPlan description].
     *
     * @param   [type]  $fecha_inicio        of the plan that will be created
     * @param   [type]  $fecha_termino       of the plan that will be created
     *
     * @return  array|void
     */
    private function planDatesAfterRequestPlan($fecha_inicio, $fecha_termino, $requestedPlan)
    {
        if (($fecha_inicio->gt($requestedPlan->finish_date)) &&
             ($fecha_termino->lte($requestedPlan->finish_date))) {
            return [
                'start_date' => function ($attribute, $value, $fail) {
                    if (true) {
                        $fail('La fecha de inicio o término choca con un plan que ya tiene el usuario.');
                    }
                },
            ];
        }
    }

    /**
     * [attributes description].
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'plan_id'         => 'plan',
            'counter'         => 'número de clases',
            'clases_by_day'   => 'número de clases por día',
            'start_date'      => 'fecha de inicio',
            'finish_date'     => 'fecha de término',
            'payment_type_id' => 'tipo de pago',
            'date'            => 'fecha',
            'amount'          => 'monto del plan',
            'observations'    => 'observaciones',
        ];
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function messages()
    {
        return [
            'finish_date.after_or_equal' => 'La fecha de término debe ser mayor o igual a la fecha de inicio del plan',
            // 'plan_id.required'           => 'Seleccione un plan para asignar',
            // 'counter.required'           => 'Campo numero de clases vacío',
            // 'finish_date.after_or_equal' => 'La fecha de termino del plan debe ser igual o mayor que la fecha de inicio',
        ];
    }
}
