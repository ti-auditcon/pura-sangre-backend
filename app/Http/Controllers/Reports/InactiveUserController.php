<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Traits\ExpiredPlans;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InactiveUserController extends Controller
{
    use ExpiredPlans;

    /**
     * [inactive_users show users with expired plans]
     * @return [array] [description]
     */
    public function inactive_users()
    {
        $expired_plans = $this->ExpiredPlan();
        return $expired_plans->map(function ($expired_plan) {
            return [
                'alumno' => $expired_plan->user->first_name.' '.$expired_plan->user->last_name,
                'plan' => $expired_plan->plan->plan,
                'fecha_termino' => Carbon::parse($expired_plan->finish_date)->format('d-m-Y'),
                'telefono' => isset($expired_plan->phone) ? '+ 56 9 '.$expired_plan->phone : "sin número",
            ];
        });
    }
}
