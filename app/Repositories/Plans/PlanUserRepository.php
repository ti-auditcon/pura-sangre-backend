<?php 

namespace App\Repositories\Plans;

use GuzzleHttp\Client;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Invoicing\DTE;
use App\Mail\NewPlanUserEmail;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanUserFlow;
use App\Models\Invoicing\DTEErrors;
use Illuminate\Support\Facades\Mail;

class PlanUserRepository 
{
    protected $planUser;

    protected $purasangreApiUrl;

    protected $verifiedSSL;

    /**
     *  @param   PlanUser  $planUser
     */
    public function __construct(PlanUser $planUser)
    {
        $this->planUser = $planUser;

        $this->purasangreApiUrl = config('app.api_url');
        $this->verifiedSSL = config('app.ssl');
    }

    /**
     *  @param   PlanUser  $planUser
     *
     *  @return  bool
     */
    public function store($data, $user)
    {
        $plan = Plan::find($data->plan_id);
        $this->planUser = $this->planUser->asignPlanToUser($data, $plan, $user);

        if ($plan->isNotcustom() && $this->shouldCreateABill($data)) {
            (new Bill)->storeBill($data, $this->planUser);
            $planUserFlow = (new PlanUserFlow)->createOne($data, $this->planUser);
            
            if (boolval($data->is_issued_to_sii)) {
                $this->emiteReceiptToSII($planUserFlow);

                $response = $this->getPDF($planUserFlow);

                return Mail::to($user->email)->send(new NewPlanUserEmail($planUserFlow, $response->data->pdf));
            }

            Mail::to($user->email)->send(new NewPlanUserEmail($planUserFlow));
        }
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
