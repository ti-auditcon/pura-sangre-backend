<?php

namespace App\Http\Controllers\Plans;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Bills\PaymentType;
use App\Models\Plans\PlanUserFlow;
use App\Http\Controllers\Controller;
use App\Http\Requests\Plans\PlanUserRequest;
use App\Http\Requests\Plans\PlanUserStoreRequest;

class PlanUserController extends Controller
{
    protected $planUser;

    protected $purasangreApiUrl;

    protected $verifiedSSL;
    
    public function __construct()
    {
        $this->middleware('can:view,user')->only('show');

        $this->planUser = new PlanUser;

        $this->purasangreApiUrl = config('app.api_url');
        $this->verifiedSSL = config('app.ssl');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userPlans = PlanUser::all();

        return view('userplans.index', compact('userPlans'));
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
                        ->with('plan_period:id,period_number')
                        ->get(['id', 'plan', 'amount', 'custom', 'plan_period_id', 'class_numbers', 'daily_clases']);

        return view('userplans.create',
            ['user' => $user, 'plans' => $plans]
        );
    }

    /**
     * @param   PlanUser  $planUser
     *
     * @return  bool
     */
    public function store(PlanUserStoreRequest $request, User $user, Plan $plan)
    {
        $plan = Plan::find($request->plan_id);
        $planUser = (new PlanUser)->asignPlanToUser($request, $plan, $user);

        if ($plan->isNotcustom() && $this->shouldCreateABill($request)) {
            (new Bill)->storeBill($request, $planUser);
            
            (new PlanUserFlow)->createOne($request, $planUser);
        }

        return redirect("/users/{$user->id}")->with('success', 'Plan asignado con éxito');
    }

    /**
     * [shouldCreateABill description]
     *
     * @param   [type]  $planData  [$planData description]
     *
     * @return  bool               [return description]
     */
    public function shouldCreateABill($planData) :bool
    {
        if ($planData->amount > 0 && boolval($planData->billed)) {
            return true;
        }

        return false;
    }

    /**
     * [show description]
     * 
     * @param   User     $user [description]
     * @param   PlanUser $plan [description]
     * 
     * @return  [type]         [description]
     */
    public function show(User $user, PlanUser $plan)
    {
        return view('userplans.show')->with('plan_user', $plan)->with('user', $user);
    }

    /**
     * [edit description]
     *
     * @param  User      $user [description]
     * @param  planuser  $plan [description]
     *
     * @return  View
     */
    public function edit(User $user, PlanUser $plan)
    {
        $payment_types = PaymentType::all();

        return view('userplans.edit', [
            'user'          => $user,
            'payment_types' => $payment_types,
            'plan_user'     => $plan,
            'plan_status'   => new PlanStatus()
        ]);
    }

    /**
     * [update description]
     *
     * @param   Request   $request
     * @param   User      $user
     * @param   planuser  $plan
     *
     * @return  \Illuminate\Http\RedirectResponse
     */
    public function update(PlanUserRequest $request, User $user, PlanUser $plan)
    {
        if ($plan->isFreezed()) {
            return redirect("users/{$user->id}")->with('warning', 'El plan no puede ser editado estando congelado.');
        }

        $plan->update([
            'start_date'     => Carbon::parse($request->start_date),
            'finish_date'    => Carbon::parse($request->finish_date),
            'observations'   => $request->observations,
            'counter'        => $request->counter,
            'plan_status_id' => $request->reactivate ? PlanStatus::ACTIVO : $plan->plan_status_id
        ]);

        return redirect("users/{$user->id}")->with('success', 'El plan se actualizó correctamente');
    }

    /**
     * Change the status of the plan to CANCELADO,
     * if it's associated to a feezed plan, delete it
     * 
     * @param   User      $user
     * @param   planuser  $plan

     * @return  \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function annul(User $user, planuser $plan)
    {
        $plan->update(['plan_status_id' => PlanStatus::CANCELADO]);

        if ($plan->postpone) {
            $plan->postpone->delete();
        }

        return redirect()->route('users.show', $user->id)
                         ->with('success', 'Se canceló el plan correctamente');
    }

    /**
     * [destroy description]
     *
     * @param  User     $user [description]
     * @param  planuser $plan [description]
     *
     * @return [type]         [description]
     */
    public function destroy(User $user, planuser $plan)
    {
        $plan->delete();

        return redirect()->route('users.show', $user->id)
                         ->with('success', 'Se eliminó el plan correctamente');
    }
}
