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
      $user = User::findOrFail($planUser->user_id);
      $fecha_inicio = Carbon::parse($planUser->start_date);
      $fecha_termino = Carbon::parse($planUser->finish_date);
      // whereIn('plan_status_id', [1,3])->
      $plan_users = PlanUser::where('user_id', $user->id)->get();
      foreach ($plan_users as $plan_user) {
        if (($fecha_inicio->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date))) || ($fecha_termino->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date)))) {

          Session::flash('error','El usuario tiene un plan activo que choca con la fecha de inicio y período seleccionados');
          return false;
        }

         elseif (($fecha_inicio->lt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->gt(Carbon::parse($plan_user->finish_date)))) {

           Session::flash('error','El usuario tiene un plan activo que choca con la fecha de inicio y período seleccionados');
           return false;
         }

         elseif (($fecha_inicio->gt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->lt(Carbon::parse($plan_user->finish_date)))) {
           Session::flash('error','El usuario tiene un plan activo que choca con la fecha de inicio y período seleccionados');
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
      // dd($planUser);
      if ($planUser->user->actual_plan && $planUser->user->actual_plan->id != $planUser->id) {
         if ($planUser->start_date > today()) {
            $planUser->plan_status_id = 3;
         }
      }elseif (!$planUser->user->actual_plan && $planUser->start_date > today()) {
        $planUser->plan_status_id = 3;
      }elseif ($planUser->start_date <= today() && $planUser->finish_date >= today()) {
        $planUser->plan_status_id = 1;
      }else {
        $planUser->plan_status_id = 4;
      }
      $planUser->save();
      $user = $planUser->user;
      $user->status_user_id = 1;
      $user->save();
   }


    public function updated(PlanUser $planUser)
    {
      if ($planUser->plan_status_id == 5) {
        $planUser->reservations()->each(function ($reserv){
          if ($reserv->reservation_status_id == 1 || $reserv->reservation_status_id == 2) {
            $reserv->delete();
          }
        });
        $planUser->bill()->delete();
      }
    }

    /**
     * Handle the plan user "restored" event.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return void
     */
    public function deleted(PlanUser $planUser)
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
