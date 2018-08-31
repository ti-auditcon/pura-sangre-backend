<?php

namespace App\Http\Controllers\Plans;

use Session;
use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Http\Controllers\Controller;

/** [PlanUserController description] */
class PlanUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * [create description]
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function create(User $user)
    {
        return view('plans.create')->with('user', $user);
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function store(Request $request, User $user)
    {
      $plan = Plan::find($request->plan_id);
      $start_date = Carbon::parse($request->fecha_inicio);
      $finish_date = Carbon::parse($request->fecha_inicio)->addMonths($plan->period_number);
      // dd($plan->class_numbers);
      $peticion = array_merge($request->all(), ['start_date' => $start_date, 'finish_date' => $finish_date, 'counter' => 1]);
      dd($peticion);
      // $planuser = PlanUser::create(array_add($request->all(),'start_date', $start_date,  []));
      return view('users.show')->with('user', $user);
      Session::flash('success','El plan ha sido asignado correctamente');
    }
    //NOta: en el 'array add' tal vez agregar de a uno o verificar el enviar como una cadena completa el resto de los
    //atributos a agregar

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function show(PlanUser $planUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function edit(PlanUser $planUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PlanUser $planUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(PlanUser $planUser)
    {
        //
    }
}
