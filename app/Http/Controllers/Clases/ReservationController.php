<?php

namespace App\Http\Controllers\Clases;

use Auth;
use Session;
use Redirect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Clases\Reservation;
use App\Http\Controllers\Controller;


class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $reservation = new Reservation;
        $reservation->clase_id = $request->clase_id;
        $reservation->reservation_status_id = 1;
        $reservation->user_id = $request->user_id;
        $reservation->by_god = $request->by_god;

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
        if ($request->ajax()) {
            // dd($reservation->clase->getReservationCountAttribute());
            $reservation->delete();
        }
        return response()->json([
            'reserv_numbers' => $reservation->clase->getReservationCountAttribute(),
            'quota' => $reservation->clase->quota
        ]);
        // $reservation->delete();
        // Session::flash('success','Retiro de Clase exitoso');
        // return Redirect::back();
    }


}
