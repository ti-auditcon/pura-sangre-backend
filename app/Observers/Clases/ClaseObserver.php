<?php

namespace App\Observers\Clases;

use App\Models\Clases\Clase;
use Carbon\Carbon;

/**
 * Event observer for Clase model
 */
class ClaseObserver
{
    /**
    * Handle the clase "deleting" event.
    *
    * @param  \App\Models\Clases\Clase  $clase
    * 
    * @return void
    */
    public function deleting(Clase $clase)
    {
        $date_class = Carbon::parse($clase->date);
        
        if ($date_class > now()) {
            foreach ($clase->reservations as $reservation) {
                $reservation->delete();
            }
        }
    }
}
