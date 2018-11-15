<?php

namespace App\Http\Controllers\Clases;

use Auth;
use Session;
use Redirect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clases\Reservation;


class ReservationController extends Controller
{
    public function store(Request $request)
    {
      $plans = Auth::user()->reservable_plans;
      dd($plans);
      $reservation = new Reservation;
      $reservation->clase_id = $request->clase_id;
      $reservation->reservation_status_id = 1;
      $reservation->user_id = $request->user_id;

      if($reservation->save()){
        Session::flash('success','Agregado correctamente a la clase');
        return Redirect::back();
      } else {
        return Redirect::back();
      }

    }

    public function update(Request $request, Reservation $reservation)
    {


    }

    public function destroy(Request $request, Reservation $reservation)
    {

    }


}
