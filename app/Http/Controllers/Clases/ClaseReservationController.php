<?php

namespace App\Http\Controllers\Clases;

use Illuminate\Http\Request;
use App\Models\Clases\Clase;
use App\Http\Controllers\Controller;

class ClaseReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Clase $clase)
    {
        $students = $clase->users;
        return $students->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function destroy(Clase $clase)
    {
        //
    }
}
