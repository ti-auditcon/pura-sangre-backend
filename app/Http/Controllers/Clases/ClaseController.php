<?php

namespace App\Http\Controllers\Clases;

use App\Http\Controllers\Controller;
use App\Models\Clases\Clase;
use App\Models\Clases\ClaseType;
use App\Models\Clases\Reservation;
use App\Models\Settings\DensityParameter;
use App\Models\Users\User;
use App\Models\Wods\Stage;
use App\Models\Wods\StageType;
use App\Models\Wods\Wod;
use Illuminate\Http\Request;
use Redirect;
use Session;

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
            Session::put('clases-type-id', 1);
            Session::put('clases-type-name', ClaseType::find(1)->clase_type);
        }

        $densities = DensityParameter::orderBy('from')->get(['id', 'level', 'from', 'to', 'color']);
        
        return view('clases.index', ['densities' => $densities]);
    }

    /**
     * Get all the classes for a given type class
     * @param  request $request [description]
     * @return json
     */
    public function clases(Request $request)
    {
        $clases = Clase::where('clase_type_id', Session::get('clases-type-id'))
                       
                       ->where('date', '>=', $request->datestart)
                       
                       ->where('date', '<=', $request->dateend)
                       
                       ->with(['claseType:id,clase_color',
                               'block:id,start,end'])

                       ->get(['id', 'date', 'quota', 'block_id', 'clase_type_id']);

        return response()->json($clases, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function show(Clase $clase)
    {
        $outclase = $this->outClass($clase->id);

        $reservations = Reservation::where('clase_id', $clase->id)
                                   ->with(['user:id,first_name,last_name,avatar,status_user_id',
                                           'user.status_user:id,type',
                                           'user.status_user:id,type',
                                           'reservation_status:id,reservation_status,type'])
                                   ->get(['id', 'reservation_status_id', 'user_id', 'updated_at']);

        $auth_roles = auth()->user(['id'])->roles()->pluck('id')->toArray();

        $stages = Stage::where('wod_id', $clase->wod_id)
                       ->with('stage_type:id,stage_type')
                       ->get(['id', 'description', 'stage_type_id']);

        $reservation_count = Reservation::where('clase_id', $clase->id)->count('id');

        return view('clases.show', [
            'clase' => $clase,
            'outclase' => $outclase,
            'wod' => $clase->wod,
            'stages' => $stages,
            'reservation_count' => $reservation_count,
            'reservations' => $reservations,
            'auth_roles' => $auth_roles
        ]);
    }

    public function store(Request $request)
    {
        dd('hola store', $request->all());
        
        // $plan = Clase::create([
        //     // '' => ,
        // ]);
        
        // return redirect()->route('clases.index', $plan->id)
        //                  ->with('success', 'El plan ha sido creado correctamente');
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
     * [wods description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function wods(Request $request)
    {
        $wods = Wod::where('clase_type_id', Session::get('clases-type-id'))
                   ->where('date', '>=', $request->datestart)
                   ->where('date', '<=', $request->dateend)
                   ->get();

        return response()->json($wods, 200);
    } 

    /**
     * [confirm description]
     * @param  Request $request [description]
     * @param  Clase   $clase   [description]
     * @return [type]           [description]
     */
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
     * [outClass recibe la clase, obtiene todas las reservaciones, luego obtiene
     * todos los usuarios del sistema que no tienen reservación a la clase, y los devuelve en una colección]
     * @param  [model] $clase [description]
     * @return [collection]        [description]
     */
    public function outClass($clase) 
    {
        $otro = Reservation::where('clase_id', $clase)->pluck('user_id');

        $consulta = User::whereNotIn('id', $otro)
                        ->with(['status_user' => function ($status) {
                            $status->select('id', 'type');
                        }])
                        ->get(['id', 'avatar', 'first_name', 'last_name', 'status_user_id']);
        
        return $consulta;
    }

    /**
     * [typeSelect description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function typeSelect(Request $request)
    {
        Session::put('clases-type-id', $request->type);

        Session::put('clases-type-name', ClaseType::find($request->type)->clase_type);

        return Redirect::back();
    }

    /**
     * [asistencia description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
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

    /**
     * [clasesdehoy description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
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