<?php

namespace App\Observers\Plans;

use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanUserPeriod;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Carbon\Carbon;
use Session;

/**
 * [PlanUserObserver description]
 */
class PlanUserObserver
{
    /**
     * [creating description]
     * @param  PlanUser $planUser [description]
     * @return [type]             [description]
     */
   public function creating(PlanUser $planUser)
   {
      $plan = Plan::findOrFail($planUser->plan_id);
      $user = User::findOrFail($planUser->user_id);
      $fecha_inicio = Carbon::parse($planUser->start_date);
      $fecha_termino = Carbon::parse($planUser->finish_date);
      $plan_users = PlanUser::whereIn('plan_status_id', [1, 2, 3])->where('user_id', $user->id)->get();
      foreach ($plan_users as $plan_user) {
         if ($fecha_inicio->lte(Carbon::parse($plan_user->finish_date)) || $fecha_termino->gte(Carbon::parse($plan_user->start_date))) {
            Session::flash('error','El usuario tiene un plan activo o precompra, que choca con la fecha de inicio y período seleccionados');
            return false;
         }
      }

        return true;
   }

          // if (($fecha_inicio->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date))) || ($fecha_termino->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date)))) {
          //   Session::flash('error','El usuario tiene un plan activo que choca con la fecha de inicio y período seleccionados');
          //   return false;
          // }
          // elseif (($fecha_inicio->lt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->gt(Carbon::parse($plan_user->finish_date)))) {
          //   Session::flash('error','El usuario tiene un plan activo que choca con la fecha de inicio y período seleccionados');
          //   return false;
          // }
          // elseif (($fecha_inicio->gt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->lt(Carbon::parse($plan_user->finish_date)))) {
            
          // }
    /**
     * Handle the plan user "created" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function created(PlanUser $planUser)
    {

      if($planUser->plan->plan_period!=null)
      {
        for ($i=0; $i < $planUser->plan->plan_period->period_number; $i++) {
          $planuserperiod = new PlanUserPeriod;
          $planuserperiod->start_date = Carbon::parse($planUser->start_date)
                            ->addMonths($i);
          $planuserperiod->finish_date = Carbon::parse($planUser->start_date)									 	 				 ->addMonths($i+1)
                             ->subDay();

          $planuserperiod->counter = $planUser->plan->class_numbers;
          $planuserperiod->plan_user_id = $planUser->id;
          $planuserperiod->save();
        } 
      }


    }

    /**
     * Handle the plan user "deleted" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function deleted(PlanUser $planUser)
    {
        //
    }

    /**
     * Handle the plan user "restored" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function restored(PlanUser $planUser)
    {
        //
    }

    /**
     * Handle the plan user "force deleted" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function forceDeleted(PlanUser $planUser)
    {
        //
    }
}
