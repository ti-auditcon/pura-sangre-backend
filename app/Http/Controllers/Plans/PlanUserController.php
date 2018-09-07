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
      list($response, $fecha_inicio, $fecha_termino, $plan) = $this->uniquePlan($user, $request);
      if ($response != null) {
        return back()->with('error', $response);
      }else {
        $planuser = PlanUser::create(array_merge($request->all(), [
          'start_date' => $fecha_inicio,
          'finish_date' => $fecha_termino,
          'counter' => $plan->class_numbers
        ]));
        return redirect()->route('users.show', $user->id)->with('success', 'El plan ha sido asignado correctamente');
      }
    }

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

  /**
   * [uniquePlan si la fecha a no esta entre c y d y la fecha b tampoco entonces que pase, ademas
   * si $fecha_inicio es menor que $plan_user->start_date y $fecha_termino es mayor que $plan_user->finish_date que no pase]
   * si la fecha a no esta entre c y d y la fecha b tampoco entonces que pase
   * @param  [type] $user    [description]
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  protected function uniquePlan($user, $request)
  {
    $plan = Plan::findOrFail($request->plan_id);
    $fecha_inicio = Carbon::parse($request->fecha_inicio);
    $fecha_termino = Carbon::parse($request->fecha_inicio)->addMonths($plan->period_number);
    $response = '';
    foreach ($user->plan_users as $plan_user) {
      if (($fecha_inicio->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date))) || ($fecha_termino->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date)))) {
        $response = 'El usuario tiene un plan activo que choca con la fecha de inicio y el período seleccionados';
      }elseif (($fecha_inicio->lt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->gt(Carbon::parse($plan_user->start_date)))) {
        $response = 'El usuario tiene un plan activo que choca con la fecha de inicio seleccionada';
      }
    }
    return array($response, $fecha_inicio, $fecha_termino, $plan);
  }

}
