<?php

namespace App\Http\Controllers\Plans;

use Session;
use App\Models\Plans\Plan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/** [PlanController description] */
class PlanController extends Controller
{

    /**
     * [__construct description]
     */
    // public function __construct()
    // {
    //     $this->middleware('plan.credentials')->only(['index', 'show']);
    //     $this->middleware('auth:api')->except(['index', 'show']);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $plans = Plan::all();
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
      $plan = Plan::create($request->all());
      return redirect()->route('plans.show', $plan->id)->with('success', 'El plan ha sido creado correctamente');
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
      $plan->update($request->all());
      Session::flash('success','Los datos del plan han sido actualizados correctamente');
      return view('plans.show')->with('plan', $plan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
       $plan->delete();
       return redirect('/plans')->with('success','El plan ha sido eliminado correctamente');
    }

}
