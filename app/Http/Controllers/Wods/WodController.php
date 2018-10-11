<?php

namespace App\Http\Controllers\Wods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wods\Wod;
use App\Models\Wods\Stage;
use Session;

class WodController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {

  }

  /**
   * Show the form for creating a new exercise.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('wods.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $wod = Wod::create([
      'date' => date('Y-m-d',strtotime($request->date)),
      'clase_type_id' => Session::get('clases-type-id')
     ]);

     Stage::create([
       'wod_id' =>  $wod->id,
       'stage_type_id' => 1,
       'description' => $request->warm,
     ]);
     Stage::create([
       'wod_id' =>  $wod->id,
       'stage_type_id' => 2,
       'description' => $request->skill,
     ]);
     Stage::create([
       'wod_id' =>  $wod->id,
       'stage_type_id' => 3,
       'description' => $request->wod,
     ]);

    return redirect('/clases');
  }

  /**
   * Display the specified resource.
   *
   *
   * @return \Illuminate\Http\Response
   */
  public function show(Wod $wod)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   *
   * @return \Illuminate\Http\Response
   */
  public function edit(Wod $wod)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Wod $wod)
  {

  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Exercises\Exercise  $exercise
   * @return \Illuminate\Http\Response
   */
  public function destroy(Wod $wod)
  {

  }
}
