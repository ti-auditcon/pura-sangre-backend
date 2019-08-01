<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings\DensityParameter;
use App\Http\Requests\Settings\DensityParameterRequest;

class DensityParameterController extends Controller
{
    /**
     * Go to index view for density parameters.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
<<<<<<< HEAD
        $densities = DensityParameter::all(['id', 'level', 'from', 'to', 'color']);
=======
        $densities = DensityParameter::all();
>>>>>>> parent of 17d130f... Revert "?"

        return view('parameters.index')->with('densities', $densities);
    }

    /**
     * Get all the densities levels parameters
     * 
     * @return json
     */
    public function clasesDensities()
    {
        $densities = DensityParameter::orderByDesc('id')->get();

        return response()->json($densities);
    }

    /**
     * Parameters Store|Update for NFIT Configurations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
<<<<<<< HEAD
        // dd($request->all());
        for ($i = 1; $i < 6; $i++) {
            if ( $request->level_.$i ) {
                DensityParameter::create([
                    'level' => request('level_'.$i),
                    'from' => (int) request('from_'.$i),
                    'to' => (int) request('to_'.$i),
                    'color' => '#27b0b6'
                ]);                
            }
        }
    }

    public function update()
    {
          foreach (DensityParameter::all() as $density) {
=======
        foreach (DensityParameter::all() as $density) {
>>>>>>> parent of 17d130f... Revert "?"
            $density->update([
                'percentage' => request($density->level)
            ]);
        }
        return back()->with('success', 'Datos actualizados correctamente');
    }

    // *
    //  * Loop over all density parameters,
    //  * check if there is one who need to be updated and then do it
    //  * 
    //  * @return json
     
    // public function update(DensityParameterRequest $request)
    // {
    //     //
    // }
}
