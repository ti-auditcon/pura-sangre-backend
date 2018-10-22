<?php

namespace App\Observers\Plans;

use App\Models\Plans\PlanUser;
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
      //verificamos que no exista uij plan activo para el usuario
      // $exist = PlanUser::where('user_id', $planUser->user_id)->where('plan_status_id', 1)->exists();
      // if($exist){
      //   Session::flash('error','ya existe un plan activo para el usuario');
      //   return false;
      // }
      // else {
      //   return true;
      // }


        $plan = Plan::findOrFail($planUser->plan_id);
        $user = User::findOrFail($planUser->user_id);
        $fecha_inicio = Carbon::parse($planUser->start_date);
        $fecha_termino = Carbon::parse($planUser->finish_date);
        foreach ($user->plan_users as $plan_user) {
          if (($fecha_inicio->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date))) || ($fecha_termino->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date)))) {
            Session::flash('error','El usuario tiene un plan activo que choca con la fecha de inicio y el período seleccionados');
            return false;
          }
          elseif (($fecha_inicio->lt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->gt(Carbon::parse($plan_user->start_date)))) {
            Session::flash('error','El usuario tiene un plan activo que choca con la fecha de inicio seleccionada');
            return false;
          }
        }

        return true;




    }

    /**
     * Handle the plan user "created" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function created(PlanUser $planUser)
    {
      //
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
