<?php

namespace App\Http\Controllers\Plans;

use Session;
use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Bills\Bill;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Http\Controllers\Controller;
use Redirect;


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
        return view('userplans.create')->with('user', $user);
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function store(Request $request, User $user)
    {
      //dd($request->all());

      $plan = Plan::find($request->plan_id);

      $planUser = new PlanUser;
      $planUser->plan_id = $plan->id;
      $planUser->user_id = $user->id;
      $planUser->plan_status_id = 1;
      $planUser->counter = $plan->class_numbers;
      $planUser->start_date = Carbon::parse($request->fecha_inicio);
      if($plan->id == 1){
        $planUser->finish_date = Carbon::parse($request->fecha_inicio)->addWeeks(1);
      }
      else {
        $planUser->finish_date = Carbon::parse($request->fecha_inicio)->addMonths($plan->plan_period->period_number);
      }

      if($planUser->save()){
        if($planUser->plan_id > 2)
        {
          $bill = new Bill;
          $bill->plan_user_id = $planUser->id;
          $bill->payment_type_id = $request->payment_type_id;
          $bill->date = today();
          $bill->detail = $request->detalle;
          $bill->amount = $request->amount;
          $bill->save();
        }
        Session::flash('success','guardado con existo');
        return redirect('/users/'.$user->id);
      }
      else {
        return redirect('/users/'.$user->id);
      }



      // if($user->plans()->where('plan_status_id',1) ) {
      //   return back()->with('error', $response);
      // }
      // else {
      //   dd('sin plan');
      // }

      // list($response, $fecha_inicio, $fecha_termino, $plan) = $this->uniquePlan($user, $request);
      // if ($response != null) {
      //   return back()->with('error', $response);
      // }else {
      //   $planuser = PlanUser::create(array_merge($request->all(), [
      //     'start_date' => $fecha_inicio,
      //     'finish_date' => $fecha_termino,
      //     'counter' => $plan->class_numbers
      //   ]));
      //   return redirect()->route('users.show', $user->id)->with('success', 'El plan ha sido asignado correctamente');
      // }


    }

    /**
     * [show description]
     * @param  User     $user [description]
     * @param  PlanUser $plan [description]
     * @return [type]         [description]
     */
    public function show(User $user, PlanUser $plan)
    {
      return view('userplans.show')->with('plan_user', $plan)->with('user', $user);
    }

    /**
     * [edit description]
     * @param  User     $user [description]
     * @param  PlanUser $plan [description]
     * @return [type]         [description]
     */
    public function edit(User $user, PlanUser $plan)
    {
      return view('userplans.edit')->with('user', $user)->with('plan_user', $plan);
    }

    /**
     * [update description]
     * @param  Request  $request [description]
     * @param  User     $user    [description]
     * @param  PlanUser $plan    [description]
     * @return [type]            [description]
     */
    public function update(Request $request, User $user, PlanUser $plan)
    {
      // dd($plan);
      $plan->update($request->all());
      Session::flash('success','Se actualizó correctamente');
      return view('userplans.show')->with(['user' => $user, 'plan_user' => $plan]);
    }

    /**
     * [destroy description]
     * @param  User     $user [description]
     * @param  PlanUser $plan [description]
     * @return [type]         [description]
     */
    public function destroy(User $user, PlanUser $plan)
    {
      // dd($plan);
      $plan->delete();
      // return redirect('/users/'.$user->id)->with('succes', 'Se eliminó correctamente');
      return redirect()->route('users.show', $user->id)->with('success', 'Se eliminó correctamente');
    }

  /**
   * [uniquePlan si la fecha a no esta entre c y d y la fecha b tampoco entonces que pase, ademas
   * si $fecha_inicio es menor que $plan_user->start_date y $fecha_termino es mayor que $plan_user->finish_date que no pase]
   * si la fecha a no esta entre c y d y la fecha b tampoco entonces que pase
   * @param  [type] $user    [description]
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  // protected function uniquePlan($user, $request)
  // {
  //   $plan = Plan::findOrFail($request->plan_id);
  //   $fecha_inicio = Carbon::parse($request->fecha_inicio);
  //   $fecha_termino = Carbon::parse($request->fecha_inicio)->addMonths($plan->plan_period->period_number);
  //   $response = '';
  //   foreach ($user->plan_users as $plan_user) {
  //     if (($fecha_inicio->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date))) || ($fecha_termino->between(Carbon::parse($plan_user->start_date), Carbon::parse($plan_user->finish_date)))) {
  //       $response = 'El usuario tiene un plan activo que choca con la fecha de inicio y el período seleccionados';
  //     }elseif (($fecha_inicio->lt(Carbon::parse($plan_user->start_date))) && ($fecha_termino->gt(Carbon::parse($plan_user->start_date)))) {
  //       $response = 'El usuario tiene un plan activo que choca con la fecha de inicio seleccionada';
  //     }
  //   }
  //   return array($response, $fecha_inicio, $fecha_termino, $plan);
  // }

}
