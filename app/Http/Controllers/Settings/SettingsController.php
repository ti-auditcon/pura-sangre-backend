<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Models\Settings\Setting;
use App\Http\Controllers\Controller;
use App\Models\Settings\DensityParameter;
use App\Http\Requests\Settings\SettingRequest;
use App\Http\Requests\Settings\DensityParameterRequest;

class SettingsController extends Controller
{
    /**
     * Go to index view for density parameters.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = Setting::first();

        return view('settings.index', compact('settings'));
    }


    /**
     * Parameters Store|Update for Pura Sangre Configurations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SettingRequest $request)
    {
        Setting::create($request->all());

        return back()->with('success', 'Datos guardados correctamente');
    }

    /**
     * [updateAll description]
     * 
     * @return [type] [description]
     */
    public function update(Setting $setting, SettingRequest $request)
    {
        $setting->update($request->all());

        return redirect("/settings/{$setting->id}")->with('success', 'Configuración actualizada correctamente');
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
