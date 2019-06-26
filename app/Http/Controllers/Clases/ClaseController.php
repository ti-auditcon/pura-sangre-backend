<?php

namespace App\Http\Controllers\Clases;

use Session;
use Redirect;
use App\Models\Wods\Wod;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use Illuminate\Http\Request;
use App\Models\Clases\ClaseType;
use App\Models\Clases\Reservation;
use App\Http\Controllers\Controller;

/** [ClaseController description] */
class ClaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Session::has('clases-type-id')) {
            Session::put('clases-type-id',1);
            Session::put('clases-type-name',ClaseType::find(1)->clase_type);
        }
        //Session::put('clases-type-name',ClaseType::find($request->type)->clase_type);
        return view('clases.index');
    }

    /**
     * Get all the classes for a given type class
     * @param  request $request [description]
     * @return json
     */
    public function clases(Request $request)
    {
        $clases = Clase::where('clase_type_id', Session::get('clases-type-id'))
                       ->where('date','>=',$request->datestart)
                       ->where('date','<=',$request->dateend)
                       ->get();

        return response()->json($clases, 200);
    }

    public function wods(Request $request)
    {
        $wods = Wod::where('clase_type_id',Session::get('clases-type-id'))->where('date','>=',$request->datestart)->where('date','<=',$request->dateend)->get();

        return response()->json($wods, 200);
    } 
       /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function show(Clase $clase)
    {
        $outclase = $this->outClass($clase);
        
        $wod = $clase->wod;

        return view('clases.show')->with('clase', $clase)
                                  ->with('outclase', $outclase)
                                  ->with('wod',$wod);
    }

    public function confirm(Request $request, Clase $clase)
    {
        if (!$request->user_id) {
            foreach ($clase->reservations as $reservation) {
                $reservation->reservation_status_id = 4;
                $reservation->save();
            }
            Session::flash('success','¡Asistencia lista!');
            return Redirect::back();
        }
        $users_ids = array_map(function($value) {
            return intval($value);
        }, $request->user_id);
        foreach ($clase->reservations as $reservation) {
            if (in_array($reservation->user_id, $users_ids)){
                $reservation->reservation_status_id = 3;
                $reservation->save();
            }else{
                $reservation->reservation_status_id = 4;
                $reservation->save();
            }
        }
        Session::flash('success','¡Asistencia lista!');
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clase $clase)
    {
        $clase->delete();
        return redirect('/clases')->with('success', 'La clase ha sido borrada correctamente');
    }

    /**
     * [outClass recibe la clase, obtiene todas las reservaciones, luego obtiene
     * todos los usuarios del sistema que no tienen reservación a la clase, y los devuelve en una colección]
     * @param  [model] $clase [description]
     * @return [collection]        [description]
     */
    public function outClass($clase){
        $otro = Reservation::where('clase_id', $clase->id)->get();
        $consulta = User::whereNotIn('id', $otro->pluck('user_id'))->get();
        return $consulta;
    }

    public function typeSelect(Request $request){
        Session::put('clases-type-id',$request->type);
        Session::put('clases-type-name',ClaseType::find($request->type)->clase_type);

        return Redirect::back();
    }


    public function asistencia(Request $request)
    {
        $reservations = Reservation::where('clase_id', $request->id)
                                   ->orderBy('updated_at')
                                   ->orderBy('reservation_status_id')
                                   ->get();
        return $reservations->map(function ($reserv) {
            return [
                'alumno' => $reserv->user->first_name.' '.$reserv->user->last_name,
                'birthdate' => $reserv->user->itsBirthDay() ? '<span class="badge badge-primary" style="margin-left: 4px;"><i class="la la-birthday-cake"></i> Cumpleañero</span>' : '',
                'avatar' => $reserv->user->avatar,
                'user_status' => $reserv->user->status_user->type,
                'tipo' => $reserv->reservation_status->type,
                'estado_reserva' => $reserv->reservation_status->reservation_status,
                'user_id' => $reserv->user_id
            ];
        });
    }

    public function clasesdehoy(Request $request)
    {
        $clases = Clase::where('date', toDay())->get();
        return $clases->map(function ($clase) {
            return [
                'date' => $reserv->user->first_name.' '.$reserv->user->last_name,
                'start' => $reserv->user->avatar,
                'end' => $reserv->user->status_user->type,
                'counter' => $reserv->reservation_status->type,
                'estado_reserva' => $reserv->reservation_status->reservation_status,
                'user_id' => $reserv->user_id
            ];
        });
    }

}
