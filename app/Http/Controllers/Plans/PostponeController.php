<?php

namespace App\Http\Controllers\Plans;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;
use App\Http\Controllers\Controller;

class PostponeController extends Controller
{
    /**
     * [index description]
     *
     *  @return  [type]  [return description]
     */
    public function index()
    {
        return view('postpones.index');
    }

    /**
     *  Postpone all plan by certains time
     *
     *  @return  view
     */
    public function postponeAll(Request $request)
    {
        $plans_to_postpone = PlanUser::whereIn('plan_status_id', [PlanStatus::ACTIVO, PlanStatus::PRECOMPRA])
                                     ->get();

        foreach($plans_to_postpone as $planToPostpone) {
            $this->postpone($request, $planToPostpone);
        }

        return view('postpones.index')->with('success', 'Todo pospuesto');
    }

    /**
     *  methodDescription
     *
     *  @return  returnType
     */
    public function postpone($request, $plan_user)
    {
        // Parse Dates
        $start = Carbon::parse($request->start_date);
        $finish = Carbon::parse($request->finish_date);

        if ($plan_user->plan_status_id === PlanStatus::ACTIVO) {
            PostponePlan::create([
                'plan_user_id' => $plan_user->id,
                'start_date' => $start,
                'finish_date' => $finish
            ]);
        }

        $diff_in_days = $start->diffInDays($finish) + 1;

        $planes_posteriores = $plan_user->user->plan_users->where('start_date', '>', $plan_user->start_date)
                                                          ->where('id', '!=', $plan_user->id)
                                                          ->sortByDesc('finish_date');

        foreach ($planes_posteriores as $plan) {
            $plan->update([
                'start_date' =>$plan->start_date->addDays($diff_in_days),
                'finish_date' => $plan->finish_date->addDays($diff_in_days)
            ]);
        }

        if ($plan_user->plan_status_id === PlanStatus::PRECOMPRA) {
            $start_date_plan = $plan_user->start_date->addDays($diff_in_days);
        } else {
            $start_date_plan = $plan_user->start_date;
        }

        $plan_user->update([
            'plan_status_id' => $start->isToday() ? PlanStatus::INACTIVO : $plan_user->plan_status_id,

            'finish_date' => $plan_user->finish_date->addDays($diff_in_days)
        ]);

        return true;
    }
}