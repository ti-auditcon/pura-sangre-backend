<?php

namespace App\Http\Controllers\Exercises;

use Session;
use Redirect;
use Illuminate\Http\Request;
use App\Models\Exercises\Exercise;
use App\Http\Controllers\Controller;

class ExerciseController extends Controller
{

    /**
     * [__construct description]
     */
    // public function __construct()
    // {
    //     $this->middleware('client.credentials')->only(['index', 'show']);
    //     $this->middleware('auth:api')->except(['index', 'show']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exercises = Exercise::all();
        return view('exercises.index')->with('exercises', $exercises);
    }

    /**
     * Show the form for creating a new exercise.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('exercises.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Exercise $exercise)
    {
        $exercise = Exercise::create($request->all());
        return redirect()->route('exercise.show', $exercise->id)->with('success', 'El ejercicio ha sido creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exercises\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {
        return view('exercises.show')->with('exercise', $exercise);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exercises\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function edit(Exercise $exercise)
    {
        return view('exercises.edit')->with('exercise', $exercise);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exercises\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exercise $exercise)
    {
        $exercise->update($request->all());
        Session::flash('success','Los datos del ejercicio han sido actualizados correctamente');
        return view('exercises.show')->with('exercise', $exercise);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exercises\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise)
    {
       $exercise->delete();
       return redirect('/exercises')->with('success','El ejercicio ha sido eliminado correctamente');
    }
}
