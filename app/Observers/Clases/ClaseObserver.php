<?php

namespace App\Observers\Clases;

use App\Models\Clases\Clase;

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
