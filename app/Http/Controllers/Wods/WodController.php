<?php

namespace App\Http\Controllers\Wods;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wods\Wod;
use App\Models\Wods\Stage;
use App\Models\Wods\StageType;
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
    $haywod = $this->hayWod($request);
    if ($haywod == true) {
      $wod = Wod::create([
      'date' => date('Y-m-d',strtotime($request->date)),
      'clase_type_id' => Session::get('clases-type-id')
     ]);

     foreach (StageType::all() as $st) {
       $id = $st->id;
       Stage::create([
         'wod_id' =>  $wod->id,
         'stage_type_id' => $id,
         'description' => $request->$id,
       ]);
     }
     return redirect('/clases');
    }
    return redirect()->back()->with('warning', 'Ya ha sido asignado un Wod para la fecha seleccionada');
  }

  protected function hayWod($request)
  {
      $wod = Wod::where('date', date('Y-m-d',strtotime($request->date)))->get();
      if (count($wod) == 0) {
        return true;
      }else{
        return false;
      }
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
    return view('wods.edit')->with('wod',$wod);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Wod $wod)
  {
    foreach (StageType::all() as $st) {
      $id = $st->id;
      $stage = $wod->stage($st->id);
      $stage->description = $request->$id;
      $stage->save();
    }
      return redirect('/clases');
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
