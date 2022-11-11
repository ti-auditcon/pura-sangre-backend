<?php

namespace App\Http\Controllers\Clases;

use App\Models\Clases\ClaseType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ClaseTypeStageTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClaseType $clase_type)
    {
        return Cache::remember("clases_types_{$clase_type->id}_stages_types", 60*60, function () use ($clase_type) {
            return response()->json(['data' => $clase_type->stageTypes]);
        });
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Clases\ClaseType  $claseType
     * @return \Illuminate\Http\Response
     */
    public function show(ClaseType $claseType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clases\ClaseType  $claseType
     * @return \Illuminate\Http\Response
     */
    public function edit(ClaseType $claseType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clases\ClaseType  $claseType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClaseType $claseType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clases\ClaseType  $claseType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClaseType $claseType)
    {
        //
    }
}
