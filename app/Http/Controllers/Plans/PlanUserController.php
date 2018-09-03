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
      $response = $this->uniquePlan($user, $request);
      if ($response != null) {
        return back()->with('error', $response);
      }else {
        $plan = Plan::findOrFail($request->plan_id);
        $start_date = Carbon::parse($request->fecha_inicio);
        $finish_date = Carbon::parse($request->fecha_inicio)->addMonths($plan->period_number);
        $planuser = PlanUser::create(array_merge($request->all(), [
          'start_date' => $start_date,
          'finish_date' => $finish_date,
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
   * [uniquePlan description]
   * @param  [type] $user    [description]
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  protected function uniquePlan($user, $request)
  {
    $response = '';
    foreach ($user->plan_users as $plan_user) {
      if (Carbon::parse($request->fecha_inicio)->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date))) {
        $response = 'El usuario tiene un plan '.$plan_user->plan->plan.', que topa con la fecha seleccionada de inicio del plan';
      }
      elseif ($plan_user->plan_state == 'activo') {
        $response = 'El usuario tiene un plan activo que choca con la fecha de inicio seleccionada';
      }
      else {
        $response = null;
      }
    }
    return $response;
  }

}
