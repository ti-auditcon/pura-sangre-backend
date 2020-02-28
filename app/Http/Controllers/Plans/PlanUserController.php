<?php

namespace App\Http\Controllers\Plans;

use Session;
use Redirect;
use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Mail\NewPlanUserEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\Plans\PlanIncomeSummary;
use App\Http\Requests\Plans\PlanUserRequest;


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
     * 
     * @param  User   $user
     * @return \Illuminate\View\View|array
     */
    public function create(User $user)
    {
        $plans = Plan::where('plan_status_id', 1)
                     ->get(['id', 'plan', 'amount', 'custom']);

        return view('userplans.create',
            ['user' => $user, 'plans' => $plans]
        );
    }

    /**
     * [store description]
     * 
     * @param  Request $request [description]
     * @param  User    $user    [description]
     * @return [type]           [description]
     */
    public function store(PlanUserRequest $request, User $user)
    {
        $plan = Plan::find($request->plan_id);
        $finish_date = null;
        $counter = null;
        if ($plan->id == 1) {
            $finish_date = Carbon::parse($request->fecha_inicio)->addWeeks(1);
            $counter = $plan->class_numbers;
        } else {
            $finish_date = Carbon::parse($request->fecha_inicio)
                ->addMonths($plan->plan_period->period_number)
                ->subDay();
            $counter = $plan->class_numbers * $plan->plan_period->period_number * $plan->daily_clases;
        }
        if ($plan->custom == 1) {
            $finish_date = Carbon::parse($request->fecha_termino);
            $counter = $request->counter * $plan->daily_clases;
        }
        $planuser = new PlanUser;
        $planuser->start_date = Carbon::parse($request->fecha_inicio);
        $planuser->finish_date = $finish_date;
        $planuser->counter = $counter;
        $planuser->plan_status_id = 1;
        $planuser->user_id = $user->id;
        $planuser->plan_id = $plan->id;
        $planuser->observations = $request->observations;

        if ($planuser->save()) {
            if (($plan->custom == 0) && ($request->amount > 0)) {
                Bill::create([
                    'plan_user_id' => $planuser->id,
                    'payment_type_id' => $request->payment_type_id,
                    'date' => Carbon::parse($request->date),
                    'start_date' => $planuser->start_date,
                    'finish_date' => $planuser->finish_date,
                    'detail' => $request->detalle,
                    'amount' => $request->amount,
                ]);
                if (!\App::environment('local')) {
                    Mail::to($user->email)->send(new NewPlanUserEmail($user, $planuser));
                }
            }
            Session::flash('success', 'Guardado con éxito');
            return redirect('/users/' . $user->id);
        } else {
            return redirect('/users/' . $user->id);
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
        return view('userplans.edit')
             ->with('user', $user)
             ->with('plan_user', $plan);
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
        if ( $plan->finish_date->lt(today()) ) {
            Session::flash('warning', 'No se puede modificar el estado de un plan cuya fecha de término es anterior a hoy');
            
            return view('userplans.show')->with([
                'user' => $user,
                'plan_user' => $plan
            ]);
        }
 
        $plan->update([
            'start_date' => Carbon::parse($request->start_date),
            'finish_date' => Carbon::parse($request->finish_date),
            'observations' => $request->observations,
            'counter' => $request->counter,
        ]);

        if ($plan->plan_id != 1 && $plan->plan_id != 2) {
            $plan = $this->updateBillIncome($plan);

            $plan->bill->amount = $request->amount;

            $plan->bill->updated_at = now();

            $plan->bill->save();
        }

        Session::flash('success', 'El plan se actualizó correctamente');
        return redirect('users/' . $user->id);
    }

    public function updateBillIncome($plan_saved)
    {
        if ($plan_saved->bill) {
            $plan_income_sum = PlanIncomeSummary::where('month', $plan_saved->bill->date->month)
                                                ->where('year', $plan_saved->bill->date->year)
                                                ->where('plan_id', $plan_saved->bill->plan_user->plan->id)
                                                ->first();
            
            $plan_income_sum->amount -= $plan_saved->bill->amount;
            
            $plan_income_sum->quantity -= 1;
            
            $plan_income_sum->save();
        }

        return $plan_saved;
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

        if ($plan->postpone) {
            $plan->postpone->delete();
        }

        return redirect()->route('users.show', $user->id)
                         ->with('success', 'Se canceló el plan correctamente');
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
