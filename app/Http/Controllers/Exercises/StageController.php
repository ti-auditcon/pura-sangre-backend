<?php

namespace App\Http\Controllers\Exercises;

use Session;
use Illuminate\Http\Request;
use App\Models\Exercises\Stage;
use App\Http\Controllers\Controller;

class StageController extends Controller
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
        return view('stages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Stage $stage)
    {
        Session::flash('date', $request->date);
        for ($i=0; $i < 3; $i++) { 
            Stage::create([
                'name' => $request->name[$i],
                'description' => $request->description[$i]]);
        }
        return redirect()->route('clases.index')->with('success', 'El workout se ha creado correctamente'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exercises\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function show(Stage $stage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exercises\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function edit(Stage $stage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exercises\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stage $stage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exercises\Stage  $stage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stage $stage)
    {
        //
    }
}
