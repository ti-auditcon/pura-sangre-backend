<?php 

namespace App\Http\Controllers\Web;

use App\Models\Plans\Plan;
use App\Http\Controllers\Controller;

class PlanController extends Controller
{
    /**
     *  Get just the contractable plans
     *
     * @return  [type]  [return description]
     */
    public function index()
    {
        $plans = Plan::whereContractable(true)
                    ->join('plan_periods', 'plan_periods.id', '=', 'plans.plan_period_id')
                    ->get(['plans.id', 'plan', 'description', 'plan_period_id', 'amount', 'plan_periods.period']);

        return response()->json(compact('plans'));
    }    
}
