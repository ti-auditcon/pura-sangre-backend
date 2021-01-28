<?php

namespace App\Http\Controllers\Web;

use App\Models\Flow\Flow;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Bills\PaymentType;
use App\Models\Plans\PlanUserFlow;
use App\Http\Controllers\Controller;
use App\Models\Plans\FlowOrderStatus;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Web\NewUserRequest;

class NewUserController extends Controller
{
    /**
     *  GuzzleClient
     *
     *  @var Client
     */
    protected $client;

    /**
     *  Plan instance
     *
     *  @var  App\Models\Plans\Plan
     */
    protected $plan;

    /**
     *  Instance of FlowOrder
     *
     *  @var  App\Models\Plans\PlanUserFlow
     */
    protected $PlanUserFlow;

    /**
     *  Flow instance for purchases
     */
    private $flow;

    /**
     *  Instanciate Flow
     *  Instanciate PlanUserFlow
     *  Instanciate Plan
     *
     *  @return  void
     */
    public function __construct()
    {
        $this->instanciateFlow('sandbox');
        $this->planUserFlow = new PlanUserFlow;
        $this->plan = Plan::class;
        $this->plan_user = PlanUser::class;
    }

    /**
     *  Make an instance of Flow on Production
     *
     *  @param  $environment  ('production', 'sandbox')
     */
    private function instanciateFlow($environment)
    {
        $this->flow = Flow::make($environment, [
            'apiKey' => config('flow.sandbox.apiKey'), /**  Credentials for FLOW platform */
            'secret' => config('flow.sandbox.secret'), /**  Credentials for FLOW platform */
        ]);
    }
    
    /**
     *  Show the form for creating a new resource.
     *
     *  @return  \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->plan = Plan::find($request->plan_id);
        // check if there is a plan with the given id and check if the plan_id is contractable
        if (!isset($this->plan->id)  || $this->plan->IsNotContractable()) {
            $contractable_plans = Plan::whereContractable(true)->get();

            return view('web.new_user', compact('contractable_plans'));
        }

        return view('web.new_user', ['plan' => $this->plan]);
    }

    /**
     *  Store a newly created resource in storage.
     *
     *  @param   \Illuminate\Http\Request  $request
     *  
     *  @return  \Illuminate\Http\Response
     */
    public function store(NewUserRequest $request)
    {
        $user = User::create($request->all());

        return response()->json([
            'success' => 'Genial, ahora estas siendo redirigido para pagar el plan que elegiste :)',
            'user_id' => $user->id,
            'plan_id' => $request->plan_id
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($userId, Request $request)
    {
        $user = User::find($userId);
        $this->plan = Plan::find($request->plan_id);
        $this->planUserFlow = $this->planUserFlow->makeOrder($this->plan, $user->id);

        try {
            $paymentResponse = $this->flow->payment()->commit([
                'commerceOrder'     => $this->planUserFlow->id,
                'subject'           => "Compra de plan {$this->plan->plan}",
                'amount'            => $this->planUserFlow->amount,
                'email'             => $this->planUserFlow->user->email,
                'urlConfirmation'   => url('/') . '/flow/confirm',
                'urlReturn'         => url('/') . '/flow/return',
                'optional'          => [
                    'Message' => 'Tu orden esta en proceso!'
                ]
            ]);
        } catch (\Exception $e) {
            return back()->with(
                'warning',
                'Ups, algo ha salido mal, no se ha realizado la transacción, verifica que tu correo sea válido, o puedes comunicarte con el administrador'
            );
        }

        return Redirect::to($paymentResponse->getUrl());
    }

    /**
     *  desc
     *
     *  @param   Request  $request  [$request description]
     *
     *  @return  [type]             [return description]
     */
    public function returnFlow(Request $request)
    {
        $payment = $this->flow->payment()->get($request->token);
        $this->planUserFlow = PlanUserFlow::find($payment->commerceOrder);

        if ($this->planUserFlow->isPaid()) {
            return view('web.flow.return');
        }

        $user = User::find($this->planUserflow->user_id);
        $paymentData = $payment->paymentData;
        
        // todo: extract error payment
        if ($paymentData['date'] === null) {
            $this->planUserFlow->paid = FlowOrderStatus::ANULADO;
            $this->planUserFlow->observations = 'Error fecha desde flow. Posiblemente error en el pago';
            $this->planUserFlow->save();

            return view('web.flow.error');
        }

        $this->planUserFlow->paid = FlowOrderStatus::PAGADO;
        $this->planUserFlow->save();
         
        $this->plan_user = $this->plan_user->makePlanUser($this->planUserFlow, $user);
        
        //  TODO: refactor to Bill model 
        $bill = new Bill;
        $bill->payment_type_id = PaymentType::FLOW;
        $bill->plan_user_id = $this->plan_user->id;
        $bill->date = today();
        $bill->start_date = $this->plan_user->start_date;
        $bill->finish_date = $this->plan_user->finish_date;
        $bill->amount = $paymentData['balance'];
        $bill->total_paid = $paymentData['amount'];
        $bill->save();

        // todo: chequear de donde viene este return con el otro codigo
        return view('flow.return');
            \DB::table('errors')->insert([
                'error' => 'entre returnFlow, userId: ' .  $user->id . ' - ' .
                    $user->full_name . 'status_user_id: ' . $user->status_user_id .  ', con plan planUserflow: ' . $planUserflow->id,
                'where' => 'FlowController',
                'created_at' => now(),
            ]);

            $planUserflow->paid = 0;
            $planUserflow->save();
                
        return view('flow.error');
    }

    // public function confirmFlow(Request $request)
    // {
    //     $payment = $this->flow->payment()->get($request->token);
    //     $paymentData = $payment->paymentData;
    //     $planUserflow = FlowOrder::find($payment->commerceOrder);
    //     $user = User::find($planUserflow->user_id);

    //     if ($planUserflow->paid == FlowOrderStatus::PAGADO) {
    //         return response()->json(['data' => 'no']);
    //     }

    //     if ($paymentData['date'] == null) {
    //         $planUserflow->paid = 3;
    //         $planUserflow->observations = 'Error fecha desde flow. Posiblemente error en el pago';
    //         $planUserflow->save();
    //         return response()->json(['data' => 'no']);
    //     } else {

    //         $planUserflow->paid = FlowOrderStatus::PAGADO;
    //         $planUserflow->save();
    //         $planUser = new PlanUser;
    //         $planUser->start_date = $planUserflow->start_date;
    //         $planUser->finish_date = $planUserflow->finish_date;
    //         $planUser->counter = $planUserflow->counter;
    //         $planUser->user_id = $planUserflow->user_id;
    //         $planUser->plan_id = $planUserflow->plan_id;
    //         if (count($user->plan_users()->where('plan_status_id', 1)->get()) > 0) {
    //             $planUser->plan_status_id = 3;
    //         } else {
    //             $planUser->plan_status_id = 1;
    //             $user->status_user_id = 1;
    //             $user->save();
    //         }

    //         if ($planUser->save()) {


    //             $bill = new Bill;
    //             $bill->payment_type_id = 6;
    //             $bill->plan_user_id = $planUser->id;
    //             $bill->date = today();
    //             $bill->start_date = $planUser->start_date;
    //             $bill->finish_date = $planUser->finish_date;
    //             $bill->amount = $paymentData['balance'];
    //             $bill->total_paid = $paymentData['amount'];
    //             $bill->save();

    //             $month = $bill->date->month;
    //             $year = $bill->date->year;
    //             $plan_id = $bill->plan_user->plan->id;
    //             $amount = $bill->amount;

    //             \DB::table('errors')->insert([
    //                 'error' => 'entre confirmFlow, userId: ' .  $user->id . ' - ' .
    //                 $user->full_name . 'status_user_id: ' . $user->status_user_id .
    //                     ', con plan planUserflow: ' . $planUserflow->id,
    //                 'where' => 'FlowController',
    //                 'created_at' => now(),
    //             ]);

    //             return response()->json([
    //                 'data' => 'ok',
    //             ]);
    //         } else {
    //             $planUserflow->paid = 0;
    //             $planUserflow->save();
    //             return response()->json([
    //                 'data' => 'no',
    //             ]);
    //         }
    //     }


    //     return response()->json([
    //         'data' => 'no',
    //     ]);
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
