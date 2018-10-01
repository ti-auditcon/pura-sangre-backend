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
        $inclase = $this->inClass($clase, $request);
        if (count($inclase) == 0) {
            Reservation::create(array_merge($request->all(), [
                    'clase_id' => $clase->id,
                    'reservation_status_id' => 1
            ]));
            Session::flash('success','Usuario agregado');
            return Redirect::back();
        }
        else{
            Session::flash('warning','El usuario ya esta en esta clase');
            return Redirect::back();
        }
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

    public function inclass($clase, $request){
        $consulta = Reservation::where('clase_id', $clase->id)
                ->where('user_id', $request->user_id)->get();
        return $consulta;
    }   
}
