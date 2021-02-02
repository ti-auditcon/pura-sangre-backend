<?php

namespace App\Http\Controllers\Web;

use App\Models\Flow\Flow;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Mail\VerifyExternalUser;
use App\Models\Plans\PlanUserFlow;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Web\NewUserRequest;
use Illuminate\Support\Facades\Validator;

class NewUserController extends Controller
{
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
     *
     *  @var  App\Models\Flow\Flow
     */
    private $flow;

    /**
     *  Bill instance
     *
     *  @var  App\Models\Bills\Bill
     */
    private $bill;

    /**
     *  Instanciate Flow
     *  Instanciate PlanUserFlow
     *  Instanciate Plan
     *  Instanciate PlanUser
     *
     *  @return  void
     */
    public function __construct()
    {
        $this->instanciateFlow('sandbox');
        $this->planUserFlow = new PlanUserFlow;
        $this->plan = new Plan;
        $this->plan_user = new PlanUser;
        $this->bill = new Bill;
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
        $dispatcher = $this->disableObservers(User::class);
        $user = User::create(array_merge($request->all(), ['password' => bcrypt('purasangre')]));
        /*** 
         *  todo: extract the generate a new token
         */
        $token = Str::random(150);
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
        ]);
        Mail::to($user->email)->send(new VerifyExternalUser($user, $token, $request->plan_id));
        $this->enableObservers(User::class, $dispatcher);

        return response()->json([
            'success' => 'Has sido registrado, te hemos enviado un link a tu correo para verificar tu correo y pagar directamente tu plan',
            'user_id' => $user->id,
            'plan_id' => $request->plan_id
        ]);
    }

    /**
     *  [disableObservers description]
     *
     *  @param   [type]  $class  [$class description]
     *
     *  @return  [type]          [return description]
     */
    public function disableObservers($class)
    {
        // getting the dispatcher instance (needed to enable again the event observer later on)
        $dispatcher = $class::getEventDispatcher();
        // disabling the events
        $class::unsetEventDispatcher();

        return $dispatcher;
    }

    /**
     * [enableObservers description]
     *
     * @param   [type]  $class       [$class description]
     * @param   [type]  $dispatcher  [$dispatcher description]
     *
     * @return  void
     */
    public function enableObservers($class, $dispatcher)
    {
        // enabling the event dispatcher
        $class::setEventDispatcher($dispatcher);
    }
    /**
     *  Show the form for editing the specified resource.
     *
     *  @param  int  $id
     * 
     *  @return \Illuminate\Http\Response
     */
    public function edit($userId, Request $request)
    {
        // find a plan
        $this->plan = Plan::find($request->plan_id);
        /**
         *  todo: add the page to choose a contractable plan
         */
        if (!isset($this->plan->id)  || $this->plan->IsNotContractable()) return; // pagina para elegir el plan a pagar
        
        /** 
         *  todod: extract this block
         */
        if ($error = $this->verifyToken($request->token)) {
            $type = 'email';
            return view('web.flow.error', compact('error', 'type'));
        } else {
            $this->spendToken($request->token);
        }

        $user = User::find($userId);
        if (!isset($user->id)) return view('web.flow.error', compact('error', 'type'));; // pagina type error to choose between email token or create  in the system
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
            return view('web.flow.error', [
                'error' => 'Ups, algo ha salido mal, no desesperes, si crees que has realizado bien el pago, comunicate con nosotros al +569 56488542 para que podamos ayudarte', 
                'type' => 'payment'
            ]);
        }

        return Redirect::to($paymentResponse->getUrl());
    }

    /**
     *  [verifyToken description]
     *
     *  @param   [type]  $token  [$token description]
     *
     *  @return  [type]          [return description]
     */
    public function verifyToken($token)
    {
        if (!isset($token) || $token === null) {
            return 'No tiene un token valido para poder seguir, puedes solicitar uno a tu correo';
        }

        if (DB::table('password_resets')->where('token', $token)->where('expired', false)->exists('id')) {
            return false;
        }

        return 'El token es invalido, puedes solicitar uno a tu correo';
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
        $this->makeFlowPayment($request);
    }

    /**
     *  Confirm payment
     *
     *  @param   Request  $request
     *
     *  @return  view
     */
    public function confirmFlow(Request $request)
    {
        $this->makeFlowPayment($request);
    }

    /**
     * [makeFlowPayment description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function makeFlowPayment(Request $request)
    {
        $payment = $this->flow->payment()->get($request->token); // get the payment process
        $planUserFlow = $this->planUserFlow->find((int) $payment->commerceOrder);

        /** Plan has been paid already */
        if ($planUserFlow->isPaid()) {
            return response()->json(['data' => 'ok']);
        }
        
        /** Plan wasn't paid, then anuul payment */
        if ($payment->paymentData['date'] === null) {
            $planUserFlow->annul('Error fecha desde flow. Posiblemente error en el pago');

            return response()->json(['data' => 'no']);
        }

        /**  Chage status plan user flow to paid  */
        $planUserFlow->toPay('Pago realizado desde web');
        $user = User::find($planUserFlow->user_id);

        /** Register Plan User on system */
        $plan_user = PlanUser::makePlanUser($planUserFlow, $user);

        $this->bill->makeFlowBill($plan_user, $payment->paymentData);

        return response()->json(['data' => 'ok']);
    }

    /**
     * [finishing description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function finishing(Request $request)
    {
        if ($error = $this->verifyToken($request->token)) {
            $type = 'email';
            return view('web.flow.error', compact('error', 'type'));
        }

        /**
         *  todo: Pass the next two lines to the User class
          */
        $user = User::where('email', $request->email)->first(['id', 'email_verified_at']);
        $user->update(['email_verified_at' => now()]);

        return redirect("/new-user/{$user->id}/edit?plan_id={$request->plan_id}&token={$request->token}");
    }

    /**
     * [requestInstructions description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  json
     */
    public function requestInstructions(Request $request)
    {
        /** The email is valid and exists a user in the system with this email */
        if($this->validateEmail($request)) return $this->validateEmail($request);

        $token = $this->generateNewToken($request->email);

        $user = User::where('email', $request->email)->first();
        Mail::to($user->email)->send(new VerifyExternalUser($user, $token, $request->plan_id));

        return response()->json(['success' => 'Genial, te hemos enviado las instrucciones, por favor revisa tu correo']);
    }

    /**
     *  Expired all token related to the email, and generate and return a brand new token
     *
     *  @param   string  $email  has to receive an valid email of the system
     *
     *  @return  string  $token  150 characters
     */
    public function generateNewToken($email)
    {
        DB::table('password_resets')->where('email', $email)->update(['expired' => true]);

        $token = Str::random(150);
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
        ]);

        return $token;
    }

    /**
     *  [validateEmail description]
     *
     *  @param   [type]  $request  [$request description]
     *
     *  @return  [type]            [return description]
     * 
     *  todo: maybe refactor to a Request
     */
    public function validateEmail($request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email'
        ], [
            'email.required' => 'Por favor ingresa tu correo',
            'email.email' => 'El correo debe tener un formato valido'
        ])->validate();

        if (User::where('email', $request->email)->doesntExist('id')) {
            return response([
                'message' => 'La informacion es incorrecta',
                'errors'  => [
                    'email' => ['No existe este email en sistema']
                ],
                'status' => 422
            ], 422);
        }
    }
}
