<?php

namespace App\Http\Controllers\Clases;

use Illuminate\Http\Request;
use App\Models\Clases\ClaseType;
use App\Http\Controllers\Controller;

class ClaseTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clases_types = ClaseType::all();

        return response()->json($clases_types, 200);
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
     * @return json
     */
    public function show(ClaseType $clases_type)
    {
        return response()->json($clases_type, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Clases\ClaseType  $claseType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClaseType $clases_type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clases\ClaseType  $claseType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClaseType $clases_type)
    {
        $clases_type->delete();
        return response()->json('Tipo de clase eliminada correctamente', 201);
    }
}
