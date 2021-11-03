<?php

namespace App\Console\Commands\Invoicing;

use GuzzleHttp\Client;
use App\Models\Invoicing\TaxDocument;
use App\Mail\NewPlanUserEmail;
use Illuminate\Console\Command;
use App\Models\Plans\PlanUserFlow;
use App\Models\Bills\PaymentStatus;
use App\Models\Invoicing\TaxDocumentErrors;
use Illuminate\Support\Facades\Mail;

class IssueReceiptsCommand extends Command
{
    /**
     *  The name and signature of the console command.
     *
     *  @var  string
     */
    protected $signature = 'purasangre:invoicing:issue-receipts';

    /**
     *  The console command description.
     *
     *  @var  string
     */
    protected $description = 'Send TaxDocuments to SII through Haulmer API';

    /**
     *  Check if the requests are with ssl connection
     *
     *  @var  boolean
     */
    protected $verifiedSSL;
    
    /**
     *  url of the Purasangre API 
     *
     *  @var  string
     */
    protected $purasangreApiUrl;

    /**
     *  Create a new command instance.
     *
     *  @return  void
     */
    public function __construct()
    {
        parent::__construct();

        /**
         *  For production we need to send the requests through SSL connections,
         *  but not in development environment
         */
        $this->verifiedSSL = boolval(config('app.sll'));

        $this->purasangreApiUrl = config('app.api_url');
    }

    /**
     *  We make two complete processes
     * 
     *  -  The first is to issue the receipt to SII,
     *     get the document in pdf, and send a email to the user,
     *     with the receipt and the information of the contracted plan
     * 
     *  -  The second one is in order FIRST FAILS,
     *     this, with the token, request the pdf one more time, save it to API
     *     and  
     *
     *  @return  mixed
     */
    public function handle()
    {
        $this->issueAllReceiptsWithoutToken();

        $this->sendReceiptsThatHaveNotPDFYet();

        $this->info('Receipts issued');
    }

    /**
     *  [issueAllReceiptsWithoutToken description]
     *
     *  @return  void
     */
    public function issueAllReceiptsWithoutToken()
    {
        $bills = PlanUserFlow::where('created_at', '>=', now()->subHours(12)->format('Y-m-d H:i:s'))
                                ->where('paid', PaymentStatus::PAID)
                                ->whereNull('sii_token')
                                ->get();

        foreach ($bills as $bill) {
            $this->info('PlanUserFlow id being iterated is: ' . $bill->id);

            try {
                $dte = new TaxDocument;
                $sii_response = $dte->issueReceipt($bill);

                if (isset($sii_response->TOKEN)) {
                    $bill->update([
                        'payment_date' => today(),
                        'sii_token'    => $sii_response->TOKEN
                    ]);

                    $response = $this->getPDF($bill);

                    if (isset($response->data) && isset($response->data->pdf)) {
                        Mail::to($bill->user->email)->send(new NewPlanUserEmail($bill, $response->data->pdf));
                    }

                    continue;
                }

                new TaxDocumentErrors($sii_response);
            } catch (\Throwable $error) {
                new TaxDocumentErrors($error->getMessage());
            }
        }
    }

    /**
     *  Request to Haulmer the bills in pdf of the PlanUserFlows with a previous token requested
     *
     *  @return  void
     */
    public function sendReceiptsThatHaveNotPDFYet()
    {
        $billsWithoutPDF = PlanUserFlow::where('created_at', '>=', now()->subHours(12)->format('Y-m-d H:i:s'))
                                        ->where('paid', PaymentStatus::PAID)
                                        ->whereNotNull('sii_token')
                                        ->where('sii_token', '!=', TaxDocument::NOT_ISSUED)
                                        ->whereNull('bill_pdf')
                                        ->get();

        foreach ($billsWithoutPDF as $bill) {
            $response = $this->getPDF($bill);

            if (isset($response->data) && isset($response->data->pdf)) {
                Mail::to($bill->user->email)->send(new NewPlanUserEmail($bill, $response->data->pdf));
            }
        }
    }

    /**
     *  [getPDF description]
     *
     *  @param   PlanUserFlow  $plan_user_flow  [$plan_user_flow description]
     *
     *  @return  object|void
     */
    public function getPDF(PlanUserFlow $plan_user_flow)
    {
        if ($plan_user_flow->hasPDFGeneratedAlready() ||
            $plan_user_flow->hasNotSiiToken()) {
            return;
        }

        try {
            $response = (new TaxDocument)->get($plan_user_flow->sii_token);

            return $this->savePDFThroughAPI($response, $plan_user_flow);
        } catch (\Throwable $error) {
            new TaxDocumentErrors($error->getMessage());
        }
    }

    /**
     *  Save bill into database through API part
     *
     *  @param   object        $response
     *  @param   PlanUserFlow  $planUserFlow
     *
     *  @return  object|string
     */
    public function savePDFThroughAPI($response, $planUserFlow)
    {
        try {
            $client = new Client(['base_uri' => $this->purasangreApiUrl]);

            $result = $client->post("/dte/save-pdf", [
                'verify'      => $this->verifiedSSL,
                'headers'     => [
                    'Accept' => "application/x-www-form-urlencoded",
                ],
                'form_params' => [
                    "pdf"            => $response->pdf,
                    "token"          => $planUserFlow->sii_token,
                    "plan_user_flow" => $planUserFlow->id
                ]
            ]);

            return json_decode($result->getBody()->getContents());
        } catch (\Throwable $error) {
            new TaxDocumentErrors($error->getMessage());

            return response()->json([
                'status' => 'Error - Do not respond correctly',
                'message' => 'No se ha podido guardar correctamente el pdf',
            ]);
        }
    }
}
