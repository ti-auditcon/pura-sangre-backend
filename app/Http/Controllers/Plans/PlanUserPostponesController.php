<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use Carbon\Carbon;
use Session;
use Illuminate\Http\Request;

class PlanUserPostponesController extends Controller
{
    /**
     * Freeze a PlanUser resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, PlanUser $plan_user)
    {
        // Parse Dates
        $start = Carbon::parse($request->start_freeze_date);

        $finish = Carbon::parse($request->end_freeze_date);

        // Validar que la fecha de inicio sea menor a la de término
        if($start > $finish) {
            return back()->with('error', 'La fecha de inicio no puede ser mayor a la de término');
        }

        $diff_in_days = $start->diffInDays($finish) + 1; 
        
        $planes_posteriores = $plan_user->user->plan_users->where('start_date', '>', $plan_user->start_date)
                                                          ->where('id', '!=', $plan_user->id)
                                                          ->sortBy('finish_date');

        foreach ($planes_posteriores as $plan) {
            $plan->update([
                'start_date' =>$plan->start_date->addDays($diff_in_days),
                'finish_date' => $plan->finish_date->addDays($diff_in_days)
            ]);
        }

        $plan_user->update([
            'plan_status_id' => 2,
            'finish_date' => $plan_user->finish_date->addDays($diff_in_days)
        ]);

        // $plan_user->plan_status_id = 2;
        // $plan_user->finish_date = $plan_user->finish_date->addDays($diff_in_days);
        // $plan_user->save();

        Session::flash('success', 'Plan Congelado Correctamente');
        return back();
    }

    /**
     * Unfreeze a PlanUser resource from storage.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanUser $planUser)
    {
        //
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
