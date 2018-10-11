<?php

namespace App\Http\Controllers\Clases;

use App\Http\Controllers\Controller;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Session;
use Redirect;

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
        $clases = Clase::where('clase_type_id',Session::get('clases-type'))->get()->toArray();
        return view('clases.index')->with('clases',json_encode($clases));
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
        return view('clases.show')->with('clase', $clase)->with('outclase', $outclase);
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
      Session::put('clases-type',$request->type);
      return Redirect::back();

    }

}
