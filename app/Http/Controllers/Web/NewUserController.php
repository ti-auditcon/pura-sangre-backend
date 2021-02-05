<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\NewUserRequest;
use App\Mail\VerifyExternalUser;
use App\Models\Bills\Bill;
use App\Models\Flow\Flow;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanUserFlow;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewUserController extends Controller
{
    /**
     *  Plan instance.
     *
     *  @var  App\Models\Plans\Plan
     */
    protected $plan;

    /**
     *  Instance of FlowOrder.
     *
     *  @var  App\Models\Plans\PlanUserFlow
     */
    protected $PlanUserFlow;

    /**
     *  Flow instance for purchases.
     *
     *  @var  App\Models\Flow\Flow
     */
    private $flow;

    /**
     *  Bill instance.
     *
     *  @var  App\Models\Bills\Bill
     */
    private $bill;

    /**
     *  Instanciate Flow
     *  Instanciate PlanUserFlow
     *  Instanciate Plan
     *  Instanciate PlanUser.
     *
     *  @return  void
     */
    public function __construct()
    {
        $this->instanciateFlow('sandbox');

        $this->planUserFlow = new PlanUserFlow();

        $this->plan = new Plan();

        $this->plan_user = new PlanUser();

        $this->bill = new Bill();
    }

    /**
     *  Make an instance of Flow on Production.
     *
     *  @param  $environment  ('production', 'sandbox')
     */
    private function instanciateFlow($environment)
    {
        $this->flow = Flow::make($environment, [
            /*  Credentials for FLOW platform */
            'apiKey' => config('flow.sandbox.apiKey'),
            'secret' => config('flow.sandbox.secret'),
        ]);
    }

    /**
     *  Show the form for creating a new resource.
     *
     *  @return  \Illuminate\Http\Response
     */
    public function create(Plan $plan, Request $request)
    {
        return view('web.new_user', compact('plan'));
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

        $user = User::create(array_merge($request->all(), [
            'password' => $request->password,
            'gender' => $request->gender ? $request->gender : 'otro',
        ]));
        $token = $this->generateNewToken($user->email);
        Mail::to($user->email)->send(new VerifyExternalUser($user, $token, $request->plan_id));

        $this->enableObservers(User::class, $dispatcher);

        return response()->json([
            'success' => 'Has sido registrado, te hemos enviado un link a tu correo para verificar tu correo y pagar directamente tu plan',
            'user_id' => $user->id,
            'plan_id' => $request->plan_id,
        ]);
    }

    /**
     *  [disableObservers description].
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
     * [enableObservers description].
     *
     *  @param   [type]  $class       [$class description]
     *  @param   [type]  $dispatcher  [$dispatcher description]
     *
     *  @return  void
     */
    public function enableObservers($class, $dispatcher)
    {
        // enabling the event dispatcher
        $class::setEventDispatcher($dispatcher);
    }

    /**
     *  Show the form for editing the specified resource.
     *
     *  @return \Illuminate\Http\Response
     */
    public function edit($userId, Request $request)
    {
        $user = $this->validateUserData($userId, $request);
        if (isset($user['error'])) {
            $plans = Plan::where('contractable', true)->get(['id', 'plan', 'amount']);

            return view('web.flow.error')->with([
                'error' => $user['error'],
                'type' => $user['type'],
                'plans' => $plans,
            ]);
        }

        $this->planUserFlow = $this->planUserFlow->makeOrder($this->plan, $user->id);

        try {
            $paymentResponse = $this->flow->payment()->commit([
                'commerceOrder' => $this->planUserFlow->id,
                'subject' => "Compra de plan {$this->plan->plan}",
                'amount' => $this->planUserFlow->amount,
                'email' => $this->planUserFlow->user->email,
                'urlConfirmation' => url('/').'/flow/confirm',
                'urlReturn' => url('/').'/flow/return',
                'optional' => [
                    'Message' => 'Tu orden esta en proceso!',
                ],
            ]);
        } catch (\Exception $e) {
            return view('web.flow.error', [
                'error' => 'Ups, algo ha salido mal, no desesperes, si crees que has realizado bien el pago, comunicate con nosotros al +569 56488542 para que podamos ayudarte',
                'type' => 'payment',
            ]);
        }

        return Redirect::to($paymentResponse->getUrl());
    }

    /**
     *  [validateUserData description].
     *
     *  @param   [type]  $userId   [$userId description]
     *  @param   [type]  $request  [$request description]
     *
     *  @return  [type]            [return description]
     */
    public function validateUserData($userId, $request)
    {
        /* find a plan */
        $this->plan = Plan::find($request->plan_id);
        if (!isset($this->plan->id) || $this->plan->IsNotContractable()) {
            $error = 'Parece que no has elegido un plan o no esta diponible para contratar, por favor selecciona uno de estos';
            $type = 'email';

            return compact('error', 'type');
        }

        $user = User::find($userId);
        if (!isset($user->id)) {
            $error = 'No hemos podido encontrarte en sistema, por favor pide nuevamente las instrucciones';
            $type = 'email';

            return compact('error', 'type');
        }

        if ($error = $this->verifyToken($request->token)) {
            $type = 'email';

            return view('web.flow.error', compact('error', 'type'));
        } else {
            $this->spendToken($request->token);
        }

        return $user;
    }

    /**
     *  [spendToken description].
     *
     *  @param   [type]  $token  [$token description]
     *
     *  @return  [type]          [return description]
     */
    public function spendToken($token)
    {
        DB::table('password_resets')->where('token', $token)
                                    ->update(['expired' => true]);
    }

    /**
     *  [verifyToken description].
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

        if ($this->tokenIsInvalid($token)) {
            return 'El token es invalido, puedes solicitar uno a tu correo';
        }

        return false;
    }

    /**
     * [tokenIsInvalid description].
     *
     * @param [type] $token [$token description]
     *
     * @return [type] [return description]
     */
    public function tokenIsInvalid($token)
    {
        return DB::table('password_resets')
                    ->where('token', $token)
                    ->where('expired', false)
                    ->doesntExist('id');
    }

    /**
     *  desc.
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
     *  Confirm payment.
     *
     *  @return  view
     */
    public function confirmFlow(Request $request)
    {
        $this->makeFlowPayment($request);
    }

    /**
     * [makeFlowPayment description].
     *
     * @param Request $request [$request description]
     *
     * @return [type] [return description]
     */
    public function makeFlowPayment(Request $request)
    {
        $payment = $this->flow->payment()->get($request->token); // get the payment process
        $planUserFlow = $this->planUserFlow->find((int) $payment->commerceOrder);

        /* Plan has been paid already */
        if ($planUserFlow->isPaid()) {
            return response()->json(['data' => 'ok']);
        }

        /* Plan wasn't paid, then anuul payment */
        if ($payment->paymentData['date'] === null) {
            $planUserFlow->annul('Error fecha desde flow. Posiblemente error en el pago');

            return response()->json(['data' => 'no']);
        }

        /*  Chage status plan user flow to paid  */
        $planUserFlow->toPay('Pago realizado desde web');
        $user = User::find($planUserFlow->user_id);

        /** Register Plan User on system */
        $plan_user = PlanUser::makePlanUser($planUserFlow, $user);

        $this->bill->makeFlowBill($plan_user, $payment->paymentData);

        return response()->json(['data' => 'ok']);
    }

    /**
     * [finishing description].
     *
     * @param Request $request [$request description]
     *
     * @return [type] [return description]
     */
    public function finishing(Request $request)
    {
        if ($error = $this->verifyToken($request->token)) {
            $type = 'email';

            return view('web.flow.error', compact('error', 'type'));
        }

        /**
         *  todo: Pass the next two lines to the User class.
         */
        $user = User::where('email', $request->email)->first(['id', 'email_verified_at']);
        $user->update(['email_verified_at' => now()]);

        return redirect("/new-user/{$user->id}/edit?plan_id={$request->plan_id}&token={$request->token}");
    }

    /**
     *  Create a token for a valid email and send a email with instructions
     *  It needs a valid email that belongs to a user.
     *
     *  @param   Request  $request  [$request description]
     *
     *  @return  json
     */
    public function requestInstructions(Request $request)
    {
        /* The email is valid and exists a user in the system with this email */
        if ($this->validateEmail($request)) {
            return $this->validateEmail($request);
        }

        $token = $this->generateNewToken($request->email);

        $user = User::where('email', $request->email)->first();
        Mail::to($user->email)->send(new VerifyExternalUser($user, $token, $request->plan_id));

        return response()->json(['success' => 'Genial, te hemos enviado las instrucciones, por favor revisa tu correo']);
    }

    /**
     *  Expired all token related to the email, and generate and return a brand new token.
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
     *  [validateEmail description].
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
            'email' => 'required|email',
            'plan_id' => 'required',
        ], [
            'email.required' => 'Por favor ingresa tu correo',
            'email.email' => 'El correo debe tener un formato valido',
            'plan_id.required' => 'Elige un plan',
        ])->validate();

        if (User::where('email', $request->email)->doesntExist('id')) {
            return response([
                'message' => 'La informacion es incorrecta',
                'errors' => [
                    'email' => ['Lo siento pero ste correo no existe en nuestro sistema'],
                ],
                'status' => 422,
            ], 422);
        }
    }
}
