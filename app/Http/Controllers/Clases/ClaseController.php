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
        $clases = Clase::where('clase_type_id',Session::get('clases-type-id'))->get()->toArray();
        $wods = Wod::where('clase_type_id',Session::get('clases-type-id'))->get();

        return view('clases.index')
          ->with('clases',json_encode($clases))
          ->with('wods',$wods);
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
<<<<<<< HEAD

        return view('clases.show')->with('clase', $clase)->with('outclase', $outclase);
=======
        $wod = $clase->wod;

        return view('clases.show')
        ->with('clase', $clase)
        ->with('outclase', $outclase)
        ->with('wod',$wod);
>>>>>>> raul
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clase $clase)
    {
        $clase->reservations()->delete();
        $clase->delete();
        return redirect('/clases')->with('success', 'La clase ha sido borrada correctamente');
    }

    /**
     * [outClass recibe la clase, obtiene todas las reservaaciones, luego obtiene
     * todos los usurios del sistema que no tienen reservación a la clase, y los devuelve en una colección]
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

}
