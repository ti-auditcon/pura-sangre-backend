<?php

namespace App\Observers\Plans;

use App\Models\Plans\PlanUser;
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
      $exist = PlanUser::where('user_id', $planUser->user_id)->where('plan_status_id', 1)->exists();
      if($exist){
        Session::flash('error','ya existe un plan activo para el usuario');
        return false;
      }
      else {
        return true;
      }

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
