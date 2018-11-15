<?php

namespace App\Observers\Clases;

use App\Models\Clases\Reservation;
use Auth;
use Session;

class ReservationObserver
{
    public function creating(Reservation $reservation)
    {
      if(!Auth::guest())
      {
        if (Auth::user()->hasRole(1)) {
            return true;

        }else {

            Session::flash('warning', 'errrrorrr');
            return false;
        }
      }

    }
    public function created(Reservation $reservation)
    {

      return false;
    }



}
