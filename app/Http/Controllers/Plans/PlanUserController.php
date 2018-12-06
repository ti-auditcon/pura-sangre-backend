<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanUserPeriod;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Redirect;
use Session;


/** [planuserController description] */
class planuserController extends Controller
{
	public function __construct()
    {
      // parent::__construct();
      $this->middleware('can:view,user')->only('show');
    }
    
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
		$plan = Plan::find($request->plan_id);
		$planuser = new PlanUser;
		$planuser->plan_id = $plan->id;
		$planuser->user_id = $user->id;
		$planuser->start_date = Carbon::parse($request->fecha_inicio);

		if ($plan->custom == 1) {
			$planuser->finish_date = Carbon::parse($request->fecha_termino);
			$planuser->counter = $request->counter;
		}

		if($plan->id == 1){
        	$planuser->finish_date = Carbon::parse($request->fecha_inicio)->addWeeks(1);
        	$planuser->counter = $plan->class_numbers;
      	}
	    else {
	        $planuser->finish_date = Carbon::parse($request->fecha_inicio)
	                                       ->addMonths($plan->plan_period->period_number)
	                                       ->subDay();
	        $planuser->counter = $plan->class_numbers * $plan->plan_period->period_number;
	    }
	    // $planuser->plan_status_id = 3;
		
		if($planuser->save()){
			if($plan->custom == 0){
				Bill::create([
					'plan_user_id' => $planuser->id,
					'payment_type_id' => $request->payment_type_id,
					'date' => Carbon::parse($request->date),
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

	/**
	 * [destroy description]
	 * @param  User     $user [description]
	 * @param  planuser $plan [description]
	 * @return [type]         [description]
	 */
	public function annul(User $user, planuser $plan)
	{
		$plan->update(['plan_status_id' => 5]);
		return redirect()->route('users.show', $user->id)->with('success', 'Se canceló el plan correctamente');
	}

	/**
	 * [destroy description]
	 * @param  User     $user [description]
	 * @param  planuser $plan [description]
	 * @return [type]         [description]
	 */
	public function destroy(User $user, planuser $plan)
	{
		$plan->delete();
		return redirect()->route('users.show', $user->id)->with('success', 'Se eliminó el plan correctamente');
	}

}
