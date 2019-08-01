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
        $densities = DensityParameter::all();

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
        foreach (DensityParameter::all() as $density) {
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
