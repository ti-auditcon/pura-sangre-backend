<?php

namespace App\Http\Controllers\Plans;

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
      list($number, $string) = $this->getPeriod($request);
      $plan = Plan::create(array_merge($request->all(), [ 'period' => $string, 'period_number', $number]));
      // dd($plan);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plans\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plan $plan)
    {
        //
    }

    /**
     * [getPeriod split period, to number and string and return both]
     * @param  [type] $request [description]
     * @return [array]          [description]
     */
    protected function getPeriod($request)
    {
      $periodo = $request->period;
      $string = substr($periodo,0, strpos( $periodo, '-'));
      $number = substr($periodo, strpos( $periodo, '-')+1, strlen($periodo));
      return array($number, $string);
    }
}
