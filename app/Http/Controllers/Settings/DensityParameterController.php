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
        $densities = DensityParameter::all(['id', 'level', 'from', 'to', 'color']);

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
        for ($i = 1; $i < 6; $i++) {
            if ( request('level_'.$i) ) {
                DensityParameter::create([
                    'level' => request('level_'.$i),
                    
                    'from' => (int) request('from_'.$i),
                    
                    'to' => (int) request('to_'.$i),
                    
                    'color' => '#27b0b6'
                ]);                
            }
        }

        return back()->with('success', 'Datos guardados correctamente');
    }

    public function updateAll()
    {
        $densities = DensityParameter::all(['id', 'level', 'from', 'to', 'color']);

        foreach ($densities as $key => $density) {
            $key += 1;

            $density->update([
                'level' => request('level_' . $key),
                'from' => (int) request('from_' . $key),
                'to' => (int) request('to_' . $key),
                'color' =>request('color_' . $key) 
            ]);
        }
        return back()->with('success', 'Datos actualizados correctamente');
    }
}
