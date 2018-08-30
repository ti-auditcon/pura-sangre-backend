<?php

namespace App\Observers\Plans;

use App\Models\Plans\PlanUser;

/**
 * [PlanUserObserver description]
 */
class PlanUserObserver
{
    /**
     * Handle the plan user "created" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function created(PlanUser $planUser)
    {
      $plan = $planUser->plan;
      $periodo = $plan->number;
      if($periodo!=null){
        for ($i=0; $i < $periodo ; $i++) {
          PlanUser::create($request->all());
          //crear contador
          //agre
          ////dejarlo adentro de plan user el counter------
          ////
        }
      }
    }


    /**
     * Handle the plan user "updated" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function updated(PlanUser $planUser)
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
