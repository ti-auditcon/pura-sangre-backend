<?php

namespace App\Http\Controllers\Wods;

use Session;
use Carbon\Carbon;
use App\Models\Wods\Wod;
use App\Models\Wods\Stage;
use Illuminate\Http\Request;
use App\Models\Wods\StageType;
use App\Http\Controllers\Controller;

class WodController extends Controller
{
    /**
     * Show the form for creating a new exercise.
     *
     * @return View
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
        
        if (! $haywod) {
            $wod = Wod::create([
                'date' => $request->date,
                'clase_type_id' => $request->clase_type_id
            ]);
    
            foreach (StageType::all() as $stage_type) {
                if (request($stage_type->id)) {
                    Stage::create([
                        'wod_id' =>  $wod->id,
                        'stage_type_id' => $stage_type->id,
                        'description' => request($stage_type->id)
                    ]);
                }
            }
            return redirect('/clases');
        }
        return back()->with('warning', 'Ya ha sido asignado un Wod para la fecha seleccionada');
    }

    protected function hayWod($request)
    {
        $wod = Wod::where('date', date('Y-m-d', strtotime($request->date)))
                  ->where('clase_type_id', $request->clase_type_id)
                  ->count();
 
        return $wod ?? null;
    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Wod $wod)
    {
        $stages = Stage::where('wod_id', $wod->id)
                        ->with(['stage_type' => function($stage) {
                            $stage->select('id', 'stage_type', 'clase_type_id');
                        }])
                       ->get(['id', 'stage_type_id', 'wod_id', 'description']);
        return view('wods.edit', ['wod' => $wod, 'stages' => $stages]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wod $wod)
    {
        $wod->stages->map(function ($stage) {

            $stage->update([

                'description' => request($stage->id)
            
            ]);

        });
        return redirect('/clases')->with('success', 'Rutina actualizada correctamente');
    }

    /**
     * Remove the specified wod from storage.
     *
     * @param  \App\Models\Wods\Wod  $wod
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wod $wod)
    {
        $wod->stages->map(function ($stage) {
            $stage->delete();
        });

        $wod->delete();
        
        return redirect('/clases')->with('success', 'Wod eliminado correctamente');
    }
}
