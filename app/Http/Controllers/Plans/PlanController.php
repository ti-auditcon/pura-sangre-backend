<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use App\Models\Plans\Plan;
use Illuminate\Http\Request;
use Session;

/** [PlanController description] */
class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = Plan::with('plan_period:id,period')
                     ->where('plan_status_id', 1)
                     ->get(['id', 'plan', 'class_numbers', 'daily_clases', 'plan_period_id', 'contractable', 'convenio']);

        return view('plans.index')->with('plans', $plans);
    }

    /**
     * [create description]
     * @param  Plan   $plan [description]
     * @return [type]       [description]
     */
    public function create(Plan $plan)
    {
        return view('plans.create_plan')->with('plan', $plan);
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @param  Plan    $plan    [description]
     * @return [type]           [description]
     */
    public function store(Request $request, Plan $plan)
    {
        $plan = Plan::create(array_merge($request->all(), [
            'contractable' => $request->contractable ? 1 : 0,
            'convenio' => $request->convenio ? 1 : 0,
            'custom' => $request->custom ? 1 : 0,
        ]));
        
        return redirect()->route('plans.show', $plan->id)
                         ->with('success', 'El plan ha sido creado correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show(Plan $plan)
    {
        return view('plans.show')->with('plan', $plan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit(Plan $plan)
    {
        return view('plans.edit')->with('plan', $plan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plan $plan)
    {
        $plan->update(array_merge($request->all(), [
            'contractable' => $request->contractable ? 1 : 0,
            'convenio' => $request->convenio ? 1 : 0
        ]));
        
        return back()->with('success', 'Los datos del plan han sido actualizados correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        $plan->plan = $plan->plan . ' (descontinuado)';
        $plan->plan_status_id = 5;
        $plan->save();

        return redirect('/plans')->with('success', 'El plan ha sido eliminado correctamente');
    }
}
