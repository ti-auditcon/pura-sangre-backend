<?php

namespace App\Http\Controllers\Clases;

use Session;
use Redirect;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use Illuminate\Http\Request;
use App\Models\Clases\Reservation;
use App\Http\Controllers\Controller;

class ClaseUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Clase $clase)
    {
        Reservation::create(array_merge($request->all(), [
            'clase_id' => $clase->id,
            'reservation_status_id' => 1
        ]));
        $outclase = $this->outClass($clase);
        Session::flash('success','wiiiii');
        return Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function show(Clase $clase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function edit(Clase $clase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clase $clase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clases\Clase  $clase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clase $clase, User $user)
    {
        $clase->reservations()->where('user_id', $user->id)->delete();
        Session::flash('success','El usuario se eliminó de la clase');
        return Redirect::back();
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
}
