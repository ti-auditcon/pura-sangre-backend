<?php

namespace App\Observers\Clases;

use App\Models\Users\User;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;

/**
 * [ClaseObserver description]
 */
class ClaseObserver
{
    /**
    * Handle the clase "deleting" event.
    *
    * @param  \App\Models\Clases\Clase  $clase
    * @return void
    */
    public function deleted(Clase $clase)
    {
        $clase->reservations()->each(function ($reservation){
            $user = User::where('id', $reservation->user_id)->first();
            // dd($user->id);
            $plan_user = PlanUser::where('user_id', $user->id)
                                 ->where('plan_status_id', 1)
                                 ->first();
            // dd($plan_user);
            if ($plan_user != null) {
                $plan_user->counter = $plan_user->counter + 1;
                $plan_user->save();
            }
            $reservation->delete();
        });
    }

    /**
     * Handle the clase "created" event.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return void
     */
    public function created(Clase $clase)
    {
        //
    }

    /**
     * Handle the clase "updated" event.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return void
     */
    public function updated(Clase $clase)
    {
        //
    }


    /**
     * Handle the clase "restored" event.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return void
     */
    public function restored(Clase $clase)
    {
        //
    }

    /**
     * Handle the clase "force deleted" event.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return void
     */
    public function forceDeleted(Clase $clase)
    {
        //
    }
}
