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
    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        Reservation::create([
            'clase_id' => $request->clase_id,
            'reservation_status_id' => 1,
            'user_id' => $request->user_id,
            'by_god' => 1,
        ]);

        Session::flash('success','Agregado correctamente a la clase');
        return back();
    }

    /**
     * [confirm description]
     * @param  Request     $request     [description]
     * @param  Reservation $reservation [description]
     * @return [type]                   [description]
     */
    public function confirm(Request $request, Reservation $reservation)
    {
        if ($request->ajax()) {
            $reservation->update(['reservation_status_id' => 2]);
            return response()->json([
                'reservation_id' => $reservation->id 
            ]);
        }
    }

    /**
     * [destroy description]
     * @param  Request     $request     [description]
     * @param  Reservation $reservation [description]
     * @return [type]                   [description]
     */
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
