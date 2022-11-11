<?php

namespace App\Http\Controllers\Clases;

use Illuminate\Http\Request;
use App\Models\Clases\ClaseType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\Clases\ClaseTypeStoreRequest;

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

        return view('clases-types.index', compact('clases_types'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clases-types.create');
    }

    /**
     * Get json of all claseType available in the system
     *
     * @return  \Illuminate\Http\Response
     */
    public function allClaseTypes()
    {
        $clases_types = ClaseType::all();

        return response()->json(['data' => $clases_types]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  ClaseTypeStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClaseTypeStoreRequest $request)
    {
        ClaseType::create([
            'clase_type' => $request->clase_type,
            'clase_color' => $request->clase_color ?? '#27b0b6',
            'icon_type' => $request->icon_type,
            'icon' => "{$request->icon_type}.svg",
            'icon_white' => "{$request->icon_type}-white.svg",
            'active' => $request->active ?? true,
        ]);

        return redirect('clases-types')->with('success', 'Tipo de clase creada correctamente');
    }

    /**
     * methodDescription
     *
     * @return  returnType
     */
    public function edit(ClaseType $clasesType)
    {
        return view('clases-types.edit', compact('clasesType'));
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
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ClaseType $clases_type, Request $request)
    {
        $request->validate(
            ['clase_type' => 'required', 'icon_type' => 'required'],
            [],
            ['clase_type' => 'Nombre', 'icon' => 'icono para tipo de clase']
        );

        $clases_type->update([
            'clase_type' => $request->clase_type,
            'icon_type'  => $request->icon_type,
            'icon'       => "{$request->icon_type}.svg",
            'icon_white' => "{$request->icon_type}-white.svg"
        ]);

        return redirect('clases-types')->with('success', 'Tipo de Clase actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   \App\Models\Clases\ClaseType  $claseType
     *
     * @return  \Illuminate\Http\Response
     */
    public function destroy(ClaseType $clases_type)
    {
        $clases_type->stageTypes->each(function ($stage) {
            $stage->delete();
        });

        $clases_type->delete();

        return response()->json('Tipo de clase eliminada correctamente', 201);
    }

    /**
     * Change the status of the claseType
     *
     * @return \Illuminate\Http\Response
     */
    public function activation(ClaseType $clases_type)
    {
        $clases_type->update([
            'active' => !$clases_type->active
        ]);

        return response()->json(['success' => 'Tipo de clase actualizada correctamente'], 200);
    }
}
