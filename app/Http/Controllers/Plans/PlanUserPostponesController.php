<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plans\PostponePlanRequest;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PostponePlan;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

class PlanUserPostponesController extends Controller
{
    /**
     * Freeze a PlanUser resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostponePlanRequest $request, PlanUser $plan_user)
    {
        // Parse Dates
        $start = Carbon::parse($request->start_freeze_date);
        $finish = Carbon::parse($request->end_freeze_date);

        PostponePlan::create([
            'plan_user_id' => $plan_user->id,
            'start_date' => $start,
            'finish_date' => $finish
        ]);

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

        $plan_user->update([
            'plan_status_id' => $start->isToday() ? 2 : $plan_user->plan_status_id,
            'finish_date' => $plan_user->finish_date->addDays($diff_in_days)
        ]);

        Session::flash('success', 'Plan Congelado Correctamente');
        return back();
    }

    /**
     * Unfreeze a PlanUser resource from storage.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanUser $plan_user, PostponePlan $postpone)
    {
        $plan_user->update([
            'plan_status_id' => PlanStatus::ACTIVO
        ]);

        $postpone->delete();

        return redirect('users/' . $plan_user->user->id)
                 ->with('success', 'Plan reanudado correctamente');
    }
}

    // Cambiar la fecha de termino del plan con la nueva de acuerdo a los días que se corrieron
    // $first_try = $plan_user->update([
    //     'plan_status_id' => 2,
    //     'finish_date' => $plan_user->finish_date->addDays($diff_in_days)
    // ]);

    // $diff_in_days_plan = $plan_que_choca->start_date->diffInDays($plan_user->finish_date->addDays($diff_in_days));

    // $plan_que_choca->start_date->addDays($diff_in_days_plan + 1); 
    
    // $plan_que_choca->finish_date->addDays($diff_in_days_plan + 1);

    // $plan_que_choca->save();
