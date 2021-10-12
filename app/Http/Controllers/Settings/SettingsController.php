<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Models\Settings\Setting;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SettingRequest;

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
     * [updateAll description]
     * 
     * @return [type] [description]
     */
    public function update(Setting $setting, SettingRequest $request)
    {
        $setting->update($request->all());

        return redirect("/settings")->with('success', 'Configuración actualizada correctamente');
    }
}
