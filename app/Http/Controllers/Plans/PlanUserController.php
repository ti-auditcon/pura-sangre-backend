<?php

namespace App\Http\Controllers\Plans;

use Session;
use Redirect;
use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Bills\Bill;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Http\Controllers\Controller;


/** [planuserController description] */
class planuserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $userPlans = planuser::all();
       return view('userplans.index')->with('userPlans', $userPlans);
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
      // dd($request->all());
      $plan = Plan::find($request->plan_id);
      $planuser = new PlanUser;
      $planuser->plan_id = $plan->id;
      $planuser->user_id = $user->id;

      // dd($user->actual_plan()->first());
      if ($user->actual_plan()->first() != null) {
        $planuser->plan_status_id = 3;      
      }else{
        $planuser->plan_status_id = 1;
      }

      $planuser->start_date = Carbon::parse($request->fecha_inicio);
      if ($plan->custom == 1) {
        $planuser->finish_date = Carbon::parse($request->fecha_termino);
        $planuser->counter = $request->counter;
      }
      elseif($plan->id == 1){
        $planuser->finish_date = Carbon::parse($request->fecha_inicio)->addWeeks(1);
        $planuser->counter = $plan->class_numbers;
      }
      else {
        $planuser->finish_date = Carbon::parse($request->fecha_inicio)->addMonths($plan->plan_period->period_number);
        $planuser->counter = $plan->class_numbers;
      }

      if($planuser->save()){
        if($plan->custom == 0)
        {
          Bill::create([
            'plan_user_id' => $planuser->id,
            'payment_type_id' => $request->payment_type_id,
            'date' => today(),
            'start_date' => $planuser->start_date,
            'finish_date' => $planuser->finish_date,
            'detail' => $request->detalle,
            'amount' => $request->amount,
          ]);
        }
        Session::flash('success','Guardado con éxito');
        return redirect('/users/'.$user->id);
      }
      else {
        return redirect('/users/'.$user->id);
      }
    }



    /**
     * [show description]
     * @param  User     $user [description]
     * @param  planuser $plan [description]
     * @return [type]         [description]
     */
    public function show(User $user, planuser $plan)
    {
      return view('userplans.show')->with('plan_user', $plan)->with('user', $user);
    }

    /**
     * [edit description]
     * @param  User     $user [description]
     * @param  planuser $plan [description]
     * @return [type]         [description]
     */
    public function edit(User $user, planuser $plan)
    {
      return view('userplans.edit')->with('user', $user)->with('plan_user', $plan);
    }

    /**
     * [update description]
     * @param  Request  $request [description]
     * @param  User     $user    [description]
     * @param  planuser $plan    [description]
     * @return [type]            [description]
     */
    public function update(Request $request, User $user, planuser $plan)
    {
      if ($plan->finish_date->lt(today())) {
        Session::flash('warning','No se puede modificar el estado de un plan que cuya fecha de termino es anterior a hoy');
        return view('userplans.show')->with(['user' => $user, 'plan_user' => $plan]);
      }else{
        $plan->update($request->all());
        Session::flash('success','Se actualizó correctamente');
        return view('userplans.show')->with(['user' => $user, 'plan_user' => $plan]);
      }
    }

    // /**
    //  * [destroy description]
    //  * @param  User     $user [description]
    //  * @param  planuser $plan [description]
    //  * @return [type]         [description]
    //  */
    // public function destroy(User $user, planuser $plan)
    // {
    //   // dd($plan);
    //   $plan->delete();
    //   // return redirect('/users/'.$user->id)->with('succes', 'Se eliminó correctamente');
    //   return redirect()->route('users.show', $user->id)->with('success', 'Se eliminó correctamente');
    // }

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
        //   $planuser = planuser::create(array_merge($request->all(), [
        //     'start_date' => $fecha_inicio,
        //     'finish_date' => $fecha_termino,
        //     'counter' => $plan->class_numbers
        //   ]));
        //   return redirect()->route('users.show', $user->id)->with('success', 'El plan ha sido asignado correctamente');
        // }


  // private function hasActivePlan($user)
  // {
  //   $active_plan = null;
  //   $active_plan = PlanUser::where('plan_status_id', 1)->where('user_id', $user->id)->first();
  //   return $active_plan;
  // }