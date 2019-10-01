<?php

namespace App\Http\Controllers\Clases;

use App\Http\Controllers\Controller;
use App\Models\Clases\ClaseType;
use App\Models\Wods\StageType;
use Illuminate\Http\Request;

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
        // dd($request->all());
        $clases_type->update([
            'clase_type' => $request->clase_type_name
        ]);

        // Get all the stage type of an specific Clase Type
        $stage_type_ids = StageType::where('clase_type_id', $clases_type->id)
                                ->pluck('id')
                                ->toArray();

        foreach ($request->stage_type as $key => $stage) {
            // dd($key, $stage);
            if (in_array($key, $stage_type_ids)) {
                StageType::where('id', $key)->update([
                    'stage_type' => $stage,
                ]);
            } else {
                StageType::create([
                    'stage_type' => $stage,
                    'clase_type_id' => $clases_type->id
                ]);
            }
        }

        return back()->with('success', 'Actualizado correctamente');
    }

    // public function updateClaseTypeStage(Request $request)
    // {
    //     dd($request->all());
    // }

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