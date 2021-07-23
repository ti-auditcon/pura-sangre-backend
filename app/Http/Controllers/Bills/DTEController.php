<?php

namespace App\Http\Controllers\Bills;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUserFlow;
use App\Models\Invoicing\DTEErrors;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bills\IssuedDTERequest;

class DTEController extends Controller
{
    /**
     *  url for developing and testing
     */
    private string $urlDev;

<<<<<<< Updated upstream
    private string $urlProduction;

    private  string $apiKeyDev;

    private  string $apiKeyProduction;
=======
    /**
     *  Api key for developing as for production
     *
     *  @var  string
     */
    private $apiKey;
    

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
     * [__construct description]
     */
    public function __construct()
    {
        $this->fillDataForInvoicer(config('app.env'));

        /**
         *  For production we need to send the requests through SSL connections,
         *  but not in development environment
         */
        $this->verifiedSSL = boolval(config('app.sll'));

        $this->purasangreApiUrl = config('app.api_url');
    }

    /**
     *  [fillDataForInvoicerAPI description]
     *
     *  @param   [type]   $environment  [$environment description]
     *  @param   sandbox                [ description]
     */
    public function fillDataForInvoicer($environment = 'sandbox')
    {
        if ($environment === 'local' || $environment === 'testing') {
            $environment = 'sandbox';
        }
>>>>>>> Stashed changes

    public function __construct()
    {
        $this->fillProperties();
    }

    /**
     *  Fill url and apis for requests
     *
     *  @return  void
     */
    public function fillProperties(): void
    {
        $this->urlDev = config('invoicing.haulmer.sandbox.base_uri');
        $this->apiKeyDev = config('invoicing.haulmer.sandbox.api_key');

        $this->apiKeyProduction = config('invoicing.haulmer.production.api_key');
        $this->urlProduction = config('invoicing.haulmer.production.base_uri');
    }

    /**
     *  Display the specified resource.
     *
     *  @param   Request
     * 
     *  @return  \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $rut = "{$request->rut}-{$request->dv}";
        try {
            $client = new Client(['base_uri' => $this->urlProduction]);

            $response = $client->get("/v2/dte/document/{$rut}/{$request->type}/{$request->document_number}/pdf", [
                'headers'  => [
                    "apikey" => $this->apiKeyProduction
                ]
            ]);
            $body = $response->getBody();
            $content = $body->getContents();
            $decoded_json = json_decode($content);

            if ($decoded_json->pdf) {
                return response()->json([
                    'status' => 'Ok - Successful',
                    'data'   => $decoded_json->pdf
                ]);
            }
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            $response = json_decode($error->getResponse()->getBody()->getContents(), true);

            return response()->json([
                'status' => 'Request failed', 'message' => $response['message']
            ], $response['statusCode']);
        }
    }

    /**
     *  Display the specified resource.
     *
     *  @param   Request
     * 
     *  @return  \Illuminate\Http\Response
     */
    public function getIssuedPDF(IssuedDTERequest $request)
    {
        try {
            $client = new Client(['base_uri' => $this->urlProduction]);

            $response = $client->get("/v2/dte/document/{$request->token}/pdf", [
                'headers'  => [
                    "apikey" => $this->apiKeyProduction
                ]
            ]);
            $body = $response->getBody();
            $content = $body->getContents();
            $decoded_json = json_decode($content);

            if ($decoded_json->pdf) {
                return response()->json([
                    'status' => 'Ok - Successful',
                    'data'   => $decoded_json->pdf
                ]);
            }
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            $response = json_decode($error->getResponse()->getBody()->getContents(), true);

            return response()->json([
                'status' => 'Request failed', 'message' => $response['message']
            ], $response['statusCode']);
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
    public function savePDFThroughAPI(PlanUserFlow $plan_user_flow, Request $request)
    {
        try {
            $client = new Client(['base_uri' => $this->purasangreApiUrl]);

            $result = $client->post("/dte/save-pdf", [
                'verify' => $this->verifiedSSL,
                'headers'  => [
                    'Accept' => "application/x-www-form-urlencoded",
                ],
                'form_params' => [
                    "pdf"            => $request->pdf,
                    "token"          => $plan_user_flow->sii_token,
                    "plan_user_flow" => $plan_user_flow->id
                ]
            ]);
            
            return response()->json(
                json_decode($result->getBody()->getContents())
            ); 
        } catch (\Throwable $error) {
            new DTEErrors($error);

            return response()->json([
                'status' => 'Error - Do not respond correctly',
                'message' => isset($error->mesage) ? $error->message : 'No se ha podido guardar correctamente el pdf',
            ]);
        }
    }
}
