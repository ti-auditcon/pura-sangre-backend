<?php

namespace App\Http\Controllers\Web;

use GuzzleHttp\Client;
use App\Models\Flow\Flow;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Invoicing\DTE;
use App\Mail\NewPlanUserEmail;
use App\Models\Plans\PlanUser;
use App\Mail\VerifyExternalUser;
use App\Models\Users\StatusUser;
use App\Models\Plans\PlanUserFlow;
use App\Models\Invoicing\DTEErrors;
use App\Models\Users\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Web\NewUserRequest;
use Illuminate\Support\Facades\Validator;

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
    protected PlanUserFlow $PlanUserFlow;

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

    protected $purasangreApiUrl;

    protected $verifiedSSL;

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

        $this->verifiedSSL =  !config('app.debug');
        $this->purasangreApiUrl = config('app.api_url');
    }

    /**
     *  Make an instance of Flow with PuraSangre credentials
     *
     *  @param   string  $environment  ('production', 'sandbox')
     * 
     *  @return  void
     */
    private function instanciateFlow($environment = 'sandbox'): void
    {
        $this->flow = Flow::make($environment, [
            'apiKey' => config("flow.${environment}.apiKey"),
            'secret' => config("flow.${environment}.secret"),
        ]);
    }

    /**
     *  Show the form for creating a new resource.
     *
     *  @param   Plan     $plan   
     *  @param   Request  $request
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
            'password'       => bcrypt('purasangre'),
            'gender'         => $request->gender ? $request->gender : 'otro',
            'status_user_id' => StatusUser::INACTIVE
        ]));

        $token = PasswordReset::generateNewToken($user->email);
        
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
                'type'  => $user['type'],
                'plans' => $plans,
            ]);
        }
        $planUserFlow = $this->planUserFlow->makeOrder($this->plan, $user->id);

        try {
            $paymentResponse = $this->flow->payment()->commit([
                'commerceOrder'   => $planUserFlow->id,
                'subject'         => "Compra de plan {$this->plan->plan}",
                'amount'          => $planUserFlow->amount,
                'email'           => $planUserFlow->user->email,
                'urlConfirmation' => url('/flow/confirm-payment'),
                'urlReturn'       => url('/flow/return-from-payment'),
                'optional' => [
                    'Message' => 'Tu orden esta en proceso!',
                ],
            ]);
        } catch (\Exception $exception) {
            return view('web.flow.error', [
                'error' => 'Algo no salió como esperabamos, no desesperes, si crees que has realizado bien el pago, comunícate con nosotros al +569 56488542 para que podamos ayudarte',
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
            PasswordReset::spendToken($request->token);
        }

        return $user;
    }

    /**
     *  [verifyToken description].
     *
     *  @param   [type]  $token  [$token description]
     *
     *  @return  string|boolean
     */
    public function verifyToken(string $token)
    {
        if (!isset($token) || $token === null) {
            return 'No tiene un token valido para poder seguir, puedes solicitar uno a tu correo';
        }

        if (PasswordReset::tokenDoesntExists($token)) {
            return 'El token es invalido, puedes solicitar uno a tu correo';
        }

        return false;
    }

    /**
     *  desc.
     *
     *  @param   Request  $request  [$request description]
     *
     *  @return  [type]             [return description]
     */
    public function finishFlowPayment(Request $request)
    {
        if(! $this->makeFlowPayment($request)) {
            return redirect('/flow/error');
        }

        return redirect('/flow/return');
    }

    /**
     *  Confirm payment.
     *
     *  @return  view
     */
    public function confirmFlowPayment(Request $request)
    {
        if(! $this->makeFlowPayment($request)) {
            return redirect('/flow/error');

        }

        return redirect('/flow/return');
    }

    /**
     *  [makeFlowPayment description].
     *
     *  @param Request $request [$request description]
     *
     *  @return [type] [return description]
     */
    public function makeFlowPayment(Request $request)
    {
        $payment = $this->flow->payment()->get($request->token); // get the payment process
        $planUserFlow = $this->planUserFlow->find((int) $payment->commerceOrder);

        /* Plan has been paid already */
        if ($planUserFlow->isPaid()) {
            return true;
        }

        /* Plan wasn't paid, then anuul payment */
        if ($payment->paymentData['date'] === null) {
            $planUserFlow->annul('Error fecha desde flow. Posiblemente error en el pago');

            return false;
        }

        /*  Chage status plan user flow to paid  */
        $planUserFlow->changeStatusToPaid('Pago realizado desde web');
        $user = User::find($planUserFlow->user_id);

        /** Register Plan User on system */
        $plan_user = PlanUser::makePlanUser($planUserFlow, $user);
        
        $this->bill->makeFlowBill($plan_user, $payment->paymentData);

        $this->emiteReceiptToSII($planUserFlow);

        $response = $this->getPDF($planUserFlow);

        Mail::to($user->email)->send(new NewPlanUserEmail($planUserFlow, $response->data->pdf));

        return true;
    }

    /**
     *  [emiteReceiptToSII description]
     *
     *  @param   [type]  $planUserflow  [$planUserflow description]
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
                return $planUserflow->update([
                    'payment_date' => today(),
                    'sii_token' => $sii_response->TOKEN
                ]);
            }

            new DTEErrors($sii_response);
        } catch (\Throwable $error) {
            new DTEErrors($error);
        }
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
            $plans = Plan::whereContractable(true)->get();

            return view('web.flow.error', compact('error', 'type', 'plans'));
        }

        /**
         *  todo: Pass the next two lines to the User class.
         */
        $user = User::where('email', $request->email)->first(['id', 'email_verified_at']);
        if (! $user->email_verified_at) {
            $user->update(['email_verified_at' => now()]);
        }

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

        $token = PasswordReset::getNewToken($request->email);

        $user = User::where('email', $request->email)->first();
        Mail::to($user->email)->send(new VerifyExternalUser($user, $token, $request->plan_id));

        return response()->json(['success' => 'Te hemos enviado las instrucciones, por favor revisa tu correo']);
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
                    'email' => ['Lo siento pero este correo no existe en nuestro sistema'],
                    'not_founded' => true
                ],
                'status' => 422,
            ], 422);
        }
        
        if (User::whereEmail($request->email)->where('email_verified_at', '!=', null)->first()) {
            return response([
                'message' => 'Usuario existente',
                'errors' => [
                    'email' => ['Este correo ya ha sido validado y puede comprar directamente desde la App movil'],
                ],
                'status' => 422,
            ], 422);
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
            return true;
        }

        if ($plan_user_flow->hasNotSiiToken()) {
            return true;
        }

        try {
            $response = (new DTE)->getReceipt($plan_user_flow->sii_token);

            return $this->savePDFThroughAPI($response, $plan_user_flow);
        } catch (\Throwable $error) {
            new DTEErrors($error);

            return true;
        }
    }

    /**
     * [getPlanUserFlowDTE description]
     *
     * @param   PlanUserFlow  $plan_user_flow  [$plan_user_flow description]
     *
     * @return  [type]                         [return description]
     */
    public function getPlanUserFlowDTE(PlanUserFlow $plan_user_flow)
    {
        if ($plan_user_flow->hasPDFGeneratedAlready()) {
            return response()->json([
                'status'  => 'Ok - Successful',
                'message' => 'El PDF se ha guardado correctamente.',
                'data' => [
                    'pdf' => $plan_user_flow->bill_pdf
                ]
            ]);
        }

        if ($plan_user_flow->hasNotSiiToken()) {
            return response()->json([
                'status' => 'Failed - Missing value',
                'message' => 'El DTE no posee un Token del SII',
            ]);
        }

        try {
            $response = (new DTE)->getReceipt($plan_user_flow->sii_token);

            return $this->savePDFThroughAPI($response, $plan_user_flow);;
        } catch (\Throwable $error) {
            new DTEErrors($error);
            
            return response()->json([
                'status' => 'Error - Do not respond correctly',
                'message' => 'No se ha podido guardar correctamente el pdf',
            ]);
        }
    }

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
