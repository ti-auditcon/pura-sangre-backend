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
        $densities = DensityParameter::orderBy('from')->get(['id', 'level', 'from', 'to', 'color']);

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
     * Parameters Store|Update for Pura Sangre Configurations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DensityParameterRequest $request)
    {
        DensityParameter::create($request->all());

        return back()->with('success', 'Datos guardados correctamente');
    }

    /**
     * [updateAll description]
     * 
     * @return [type] [description]
     */
    public function update(DensityParameter $density_parameter, DensityParameterRequest $request)
    {
        $density_parameter->update($request->all());

        return redirect('density-parameters')->with('success', 'Parámetro actuaqlizado correctamente');
    }

    /**
     * [destroy description]
     * @param  DensityParameter $density_parameter [description]
     * @return [type]                              [description]
     */
    public function destroy(DensityParameter $density_parameter)
    {
        $density_parameter->delete();
        
        return back()->with('succes', 'Parámetro eliminado correctamente');
    }
}
