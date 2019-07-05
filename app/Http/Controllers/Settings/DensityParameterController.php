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
     * [configOptions description]
     * @return [type] [description]
     */
    public function configOptions()
    {
        $config_data = DensityParameter::first();
        return ['data' => $config_data];
    }

    /**
     * Store deparameters to nFIT configurations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Settings\Parameter  $parameter
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // $confirm = $request->checkboxConfirm == 'true' ? true : false;
        // $quite = $request->checkboxQuite == 'true' ? true : false;
        // $mins_confirm_clases = $confirm === true ? $request->mins_confirm_clases : null;
        // $mins_quite_alumnos = $quite === true ? $request->mins_quite_alumnos : null;

        // $param = DensityParameter::updateOrCreate(
        //     ['id' => 1],
        //     [
        //         'id' => 1,
        //         'mins_confirm_clases' => $mins_confirm_clases,
        //         'mins_quite_alumnos' => $mins_quite_alumnos,
        //         'calendar_start' => $request->calendar_start,
        //         'calendar_end' => $request->calendar_end,
        //         'check_confirm_clases' => $confirm,
        //         'check_quite_alumnos' => $quite,
        //     ]
        // );
        // return response()->json(['success' => 'Parámetros actualizados']);
    }

    /**
     * Loop over all density parameters,
     * check if there is one who need to be updated and then do it
     * 
     * @return json
     */
    public function update(DensityParameterRequest $request)
    {
        foreach (DensityParameter::all() as $density) {
            
            if ($density->percentage != request($density->level)) {
                
                $density->update([
                    'percentage' => request($density->level)
                ]);
            
            }
        
        }
    }
}
