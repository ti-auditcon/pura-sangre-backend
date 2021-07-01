<?php

namespace App\Http\Controllers\Bills;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bills\IssuedDTERequest;

class DTEController extends Controller
{
    /**
     *  Base url for developing as for production
     *
     *  @var  string
     */
    private $baseUrl;

    /**
     *  Api key for developing as for production
     *
     *  @var  string
     */
    private $apiKey;

    public function __construct()
    {
        $this->fillDataForInvoicer(config('app.env'));
    }

    /**
     *  [fillDataForInvoicerAPI description]
     *
     *  @param   [type]   $environment  [$environment description]
     *  @param   sandbox                [ description]
     */
    public function fillDataForInvoicer($environment = 'sandbox')
    {
        if ($environment === 'local') {
            $environment = 'sandbox';
        }

        $this->fillUrlAndKeys($environment);
    }

    /**
     *  Fill url and apis for requests
     *
     *  @return  void
     */
    public function fillUrlAndKeys($environment = 'sandbox')
    {
        $this->baseUrl = config("invoicing.haulmer.{$environment}.base_uri");

        $this->apiKey = config("invoicing.haulmer.{$environment}.api_key");

        $this->verifiedSSL = config('app.ssl');
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
            $client = new Client(['base_uri' => $this->baseUrl]);

            $response = $client->get("/v2/dte/document/{$rut}/{$request->type}/{$request->document_number}/pdf", [
                'headers'  => [
                    "apikey" => $this->apiKey
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
            $client = new Client(['base_uri' => $this->baseUrl]);

            $response = $client->get("/v2/dte/document/{$request->token}/pdf", [
                'headers'  => [
                    "apikey" => $this->apiKey
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
            dd($response);

            return response()->json([
                'status' => 'Request failed', 'message' => $response['message']
            ], $response['statusCode']);
        }
    }
}
