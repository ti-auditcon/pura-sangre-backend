<?php

namespace App\Http\Controllers\Plans;

use Session;
use Redirect;
use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Mail\NewPlanUserEmail;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Bills\PaymentType;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Plans\PlanIncomeSummary;
use App\Http\Requests\Plans\PlanUserRequest;


class PlanUserController extends Controller
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
                     ->get(['id', 'plan', 'amount', 'custom']);

        return view('userplans.create',
            ['user' => $user, 'plans' => $plans]
        );
    }

    /**
     *  Assign plan to a user
     *
     *  @param   Request  $request
     *  @param   User     $user   
     * 
     *  @return  \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(PlanUserRequest $request, User $user)
    {
        $plan = Plan::find($request->plan_id);

        $plan_user = (new PlanUser)->asignPlanToUser($request, $plan, $user);

        if ($plan->isNotcustom() && $request->amount > 0) {
            $bill = (new Bill)->storeBill($request, $plan_user);

            if ($request->to_sii) {
                $bill_pdf = $this->emiteReceiptToSii($bill);
            }
        }

        if ( ! App::environment(['local', 'testing']) ) {
            Mail::to($user->email)->send(new NewPlanUserEmail($user, $bill_pdf));
        }

        return redirect("/admin/users/{$user->id}")->with('success', 'Plan asignado con éxito');

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
     *  [edit description]
     *
     *  @param  User      $user [description]
     *  @param  planuser  $plan [description]
     *
     *  @return  View
     */
    public function edit(User $user, PlanUser $plan)
    {
        $payment_types = PaymentType::all();

        return view('userplans.edit', [
            'user' => $user,
            'payment_types' => $payment_types,
            'plan_user' => $plan,
            'plan_status' => new PlanStatus()
        ]);
    }

    /**
     *  [update description]
     *
     *  @param   Request   $request
     *  @param   User      $user
     *  @param   planuser  $plan
     *
     *  @return  \Illuminate\Http\RedirectResponse
     */
    public function update(PlanUserRequest $request, User $user, planuser $plan)
    {
        $plan->update([
            'start_date'     => Carbon::parse($request->start_date),
            'finish_date'    => Carbon::parse($request->finish_date),
            'observations'   => $request->observations,
            'counter'        => $request->counter,
            'plan_status_id' => $request->reactivate ? PlanStatus::ACTIVO : $plan->plan_status_id
        ]);

        return redirect("users/{$user->id}")
                ->with('success', 'El plan se actualizó correctamente');
    }

    /**
     *  [updateBillIncome description]
     *
     *  @param   [type]  $plan_saved  [$plan_saved description]
     *
     *  @return  [type]               [return description]
     */
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
     *  [destroy description]
     *
     *  @param  User     $user [description]
     *  @param  planuser $plan [description]
     *
     *  @return [type]         [description]
     */
    public function destroy(User $user, planuser $plan)
    {
        $plan->delete();

        return redirect()->route('users.show', $user->id)
                         ->with('success', 'Se eliminó el plan correctamente');
    }

}
