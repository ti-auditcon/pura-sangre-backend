<?php

namespace App\Http\Controllers\Plans;

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Invoicing\DTE;
use App\Mail\NewPlanUserEmail;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Bills\PaymentType;
use App\Models\Plans\PlanUserFlow;
use App\Models\Invoicing\DTEErrors;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Plans\PlanUserRequest;

class PlanUserController extends Controller
{
    protected $planUser;

    protected $purasangreApiUrl;

    protected $verifiedSSL;
    
    public function __construct()
    {
        // parent::__construct();
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
                     ->get(['id', 'plan', 'amount', 'custom', 'plan_period_id', 'class_numbers', 'daily_clases']);

        return view('userplans.create',
            ['user' => $user, 'plans' => $plans]
        );
    }

    // /**
    //  *  Assign plan to a user
    //  *
    //  *  @param   Request  $request
    //  *  @param   User     $user   
    //  * 
    //  *  @return  \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
    //  */
    // public function store(PlanUserRequest $request, User $user)
    // {
    //     $this->planUserRepository->store($request, $user);

        
    // }

    /**
     *  [show description]
     * 
     *  @param   User     $user [description]
     *  @param   PlanUser $plan [description]
     *  
     *  @return  [type]         [description]
     */
    public function show(User $user, PlanUser $plan)
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
            'user'          => $user,
            'payment_types' => $payment_types,
            'plan_user'     => $plan,
            'plan_status'   => new PlanStatus()
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

        return redirect("users/{$user->id}")->with('success', 'El plan se actualizó correctamente');
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

    /**
     *  @param   PlanUser  $planUser
     *
     *  @return  bool
     */
    public function store(PlanUserRequest $request, User $user)
    {
        $plan = Plan::find($request->plan_id);
        $this->planUser = $this->planUser->asignPlanToUser($request, $plan, $user);

        if ($plan->isNotcustom() && $this->shouldCreateABill($request)) {
            (new Bill)->storeBill($request, $this->planUser);
            $planUserFlow = (new PlanUserFlow)->createOne($request, $this->planUser);

            if (boolval($request->is_issued_to_sii)) {
                $this->emiteReceiptToSII($planUserFlow);
                
                if ($bill_pdf = $this->getPDF($planUserFlow)) {
                    Mail::to($user->email)->send(new NewPlanUserEmail($planUserFlow, $bill_pdf));
                }
            } else {
                Mail::to($user->email)->send(new NewPlanUserEmail($planUserFlow));
            }

        }

        return redirect("/users/{$user->id}")->with('success', 'Plan asignado con éxito');
    }

    /**
     *  [shouldCreateABill description]
     *
     *  @param   [type]  $planData  [$planData description]
     *
     *  @return  bool               [return description]
     */
    public function shouldCreateABill($planData) :bool
    {
        if ($planData->amount > 0 && boolval($planData->billed)) {
            return true;
        }

        return false;
    }

    /**
     *  [emiteReceiptToSII description]
     *
     *  @param   PlanUserFlow  $planUserflow
     *
     *  @return  null|bool|void
     */
    public function emiteReceiptToSII(PlanUserFlow $planUserflow)
    {
        if ($planUserflow->isAlreadyIssuedToSII()) {
            return;
        }

        try {
            $dte = new DTE;
            $sii_response = $dte->issueReceipt($planUserflow);

                        if (isset($sii_response->TOKEN)) {
                return $planUserflow->update(['sii_token' => $sii_response->TOKEN]);
            }

            new DTEErrors($sii_response);
        } catch (\Throwable $error) {
            new DTEErrors($error);
        }
    }

    /**
     *  [getPDF description]
     *
     *  @param   PlanUserFlow  $plan_user_flow  [$plan_user_flow description]
     *
     *  @return  [type]                         [return description]
     */
    public function getPDF(PlanUserFlow $plan_user_flow)
    {
        if ($plan_user_flow->hasPDFGeneratedAlready()) {
            return $plan_user_flow->bill_pdf;
        }

        if ($plan_user_flow->hasNotSiiToken()) {
            return false;
        }

        try {
            $response = (new DTE)->getReceipt($plan_user_flow->sii_token);

            $result = $this->savePDFThroughAPI($response, $plan_user_flow);

            if (isset($result->data->pdf)) {
                return $result->data->pdf;
            }
        } catch (\Throwable $error) {
            new DTEErrors($error);

            return true;
        }
    }
    
    /**
     *  [savePDFThroughAPI description]
     *
     *  @param   [type]  $response      [$response description]
     *  @param   [type]  $planUserFlow  [$planUserFlow description]
     *
     *  @return  [type]                 [return description]
     */
    public function savePDFThroughAPI($response, $planUserFlow)
    {
        try {
            $client = new Client(['base_uri' => $this->purasangreApiUrl]);
            $response = $client->post("/dte/save-pdf", [
                'verify' => $this->verifiedSSL,
                'headers'  => [
                    'Accept' => "application/x-www-form-urlencoded",
                ],
                'form_params' => [
                    "pdf"            => $response->pdf,
                    "token"          => $planUserFlow->sii_token,
                    "plan_user_flow" => $planUserFlow->id
                ]
            ]);
            $content = $response->getBody()->getContents();

            return json_decode($content);
        } catch (\Throwable $th) {
            new DTEErrors($th);

            return response()->json([
                'status' => 'Error - Do not respond correctly',
                'message' => 'No se ha podido guardar correctamente el pdf',
            ]);
        }
    }
}
