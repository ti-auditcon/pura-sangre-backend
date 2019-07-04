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
        $clase_type = ClaseType::create([
            'clase_type' => $request->clase_type,
            'clase_color' => '#27b0b6'
        ]);
        
        return response()->json([
            'success' => 'Tipo de clase creada correctamente', 
            'data' => $clase_type->id
        ], 200);
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
        $clases_type->update([
            'clase_type' => $request->clase_type
        ]);

        return response()->json([
            'success' => 'Tipo de clase actualizada correctamente', 
            'data' => $clases_type->id
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Clases\ClaseType  $claseType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClaseType $clases_type)
    {
        if ($clases_type->clase_type == request('word_confirm')) {
            $clases_type->delete();
        
            return response()->json('Tipo de clase eliminada correctamente', 201);
        }
    }
}
