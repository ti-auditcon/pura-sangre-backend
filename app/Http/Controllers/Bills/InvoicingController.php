<?php

namespace App\Http\Controllers\Bills;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoicingController extends Controller
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

    /**
     *  Check if the requests are with ssl connection
     *
     *  @var  boolean
     */
    protected $verifiedSSL;

    /**
     *  [fillDataForInvoicerAPI description]
     *
     *  @param   [type]   $environment  [$environment description]
     *  @param   sandbox                [ description]
     */
    public function fillDataForInvoicerAPI($environment = 'sandbox')
    {
        if ($environment === 'local' || $environment === 'testing') {
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

        $this->verifiedSSL = boolval(config('app.ssl'));
    }

    /**
     *  Display a listing of the resource.
     *
     *  @return  \Illuminate\Http\Response
     */
    public function issued()
    {
        return view('payments.bills_issued');
    }
    
    /**
     *  Display a listing of the resource.
     *
     *  @return  \Illuminate\Http\Response
     */
    public function recevied()
    {
        return view('payments.bills_received');
    }

    /**
     * [getDTEs description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  json
     */
    public function receivedJson(Request $request)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUrl]);

            $response = $client->post("/v2/dte/document/received", [
                'headers'  => [
                    "apikey" => $this->apiKey,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    "Page" => $request->query('page') ?? 1
                ]
            ]);
            $response = json_decode($response->getBody()->getContents());

            if (is_null($response)) {
                return $this->voidDataTableResponse(); 
            } 

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsFiltered" => intval(count($response->data)),
                "recordsTotal"    => intval($response->total),
                "data"            => $response->data,
                "current_page"    => $response->current_page,
                "last_page"       => $response->last_page
            );

            echo json_encode($json_data);
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            $response = json_decode($error->getResponse()->getBody()->getContents(), true);

            return response()->json([
                'status' => 'Request failed', 'message' => $response['message']
            ], $response['statusCode']);
        }
    }
    
    /**
     * [getDTEs description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  json
     */
    public function issuedJson(Request $request)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUrl]);

            $response = $client->post("/v2/dte/document/issued", [
                'headers'  => [
                    "apikey" => $this->apiKey,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    "Page" => $request->query('page') ?? 1
                ]
            ]);
            $response = json_decode($response->getBody()->getContents());

            if (is_null($response)) {
                return $this->voidDataTableResponse(); 
            } 
            
            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsFiltered" => intval(count($response->data)),
                "recordsTotal"    => intval($response->total),
                "data"            => $response->data,
                "current_page"    => $response->current_page,
                "last_page"       => $response->last_page
            );

            echo json_encode($json_data);
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            $response = json_decode($error->getResponse()->getBody()->getContents(), true);

            return response()->json([
                'status' => 'Request failed', 'message' => $response['message']
            ], $response['statusCode']);
        }
    }

    /**
     *  Simulate a void datatable response
     *
     * @return  array
     */
    public function voidDataTableResponse(): array
    {
        return [
            "draw"            => 1,
            "recordsFiltered" => 0,
            "recordsTotal"    => 0,
            "data"            => [],
            "current_page"    => 1,
            "last_page"       => 1,
        ];
    }
}
