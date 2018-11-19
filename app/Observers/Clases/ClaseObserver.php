<?php

namespace App\Observers\Clases;

use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use Carbon\Carbon;

/**
 * [ClaseObserver description]
 */
class ClaseObserver
{
    public function retrieved(Clase $clase)
    {
      $date = $clase->date;
      $time = $clase->start_at;
      $dateTime = $date.' '.$time;
      if ($dateTime < now()) {

        $clase->reservations()->update(['reservation_status_id' => 3]);
      }

    }
    /**
    * Handle the clase "deleting" event.
    *
    * @param  \App\Models\Clases\Clase  $clase
    * @return void
    */
    public function deleting(Clase $clase)
    {
        $date_class = Carbon::parse($clase->date);
        foreach ($clase->reservations as $reservation) {
            $user = User::find($reservation->user_id);
            $planusers = PlanUser::whereIn('plan_status_id', [1,3])->where('user_id', $user->id)->get();

            if(count($planusers) != 0){
                $period_plan = null;
                foreach ($planusers as $planuser) {
                    foreach ($planuser->plan_user_periods as $pup) {
                        if ($date_class->between(Carbon::parse($pup->start_date), Carbon::parse($pup->finish_date))) {
                            $period_plan = $pup; 
                        }
                    }
                }
                if ($period_plan) {
                    $period_plan->counter = $period_plan->counter + 1;
                    $period_plan->save();
                }
                $reservation->delete();
            }else{
                $reservation->delete();
            }
        }
    }
}
