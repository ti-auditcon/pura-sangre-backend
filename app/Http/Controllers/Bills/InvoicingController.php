<?php

namespace App\Http\Controllers\Bills;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoicingController extends Controller
{
    /**
     *  url for developing and testing
     */
    private $urlDev;

    private $urlProduction;

    private $apiKeyDev;

    private $apiKeyProduction;

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

    public $data_response = [
        "current_page" => 1,
        "last_page" => 6,
        "recordsFiltered" => 30,
        "total" => 196,
        "data" => [
            [
                "RUTEmisor" => 10524550,
                "DV" => "5",
                "RznSoc" => "RUTH ERIKA GALLEGUILLOS ACEVEDO",
                "TipoDTE" => 33,
                "Folio" => 4,
                "FchEmis" => "2021-06-07",
                "MntExe" => 13520,
                "MntNeto" => 61900,
                "IVA" => 11761,
                "MntTotal" => 87181,
                "Acuses" => null,
                "FmaPago" => 0,
                "TpoTranCompra" => 1
            ],
            [
                "RUTEmisor" => 10524550,
                "DV" => "5",
                "RznSoc" => "RUTH ERIKA GALLEGUILLOS ACEVEDO",
                "TipoDTE" => 33,
                "Folio" => 2,
                "FchEmis" => "2021-06-07",
                "MntExe" => 0,
                "MntNeto" => 302333,
                "IVA" => 57443,
                "MntTotal" => 359776,
                "Acuses" => null,
                "FmaPago" => 0,
                "TpoTranCompra" => 1
            ],
            [
                "RUTEmisor" => 9071084,
                "DV" => "2",
                "RznSoc" => "MONICA EUGENIA NEUMANN BIRKE",
                "TipoDTE" => 34,
                "Folio" => 9,
                "FchEmis" => "2021-06-07",
                "MntExe" => 959500,
                "MntNeto" => 0,
                "IVA" => 0,
                "MntTotal" => 959500,
                "Acuses" => null,
                "FmaPago" => 0,
                "TpoTranCompra" => 1
            ]
        ]
    ];

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
            $client = new Client(['base_uri' => $this->urlProduction]);

            $response = $client->post("/v2/dte/document/received", [
                'headers'  => [
                    "apikey" => $this->apiKeyProduction,
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
            $client = new Client(['base_uri' => $this->urlProduction]);

            $response = $client->post("/v2/dte/document/issued", [
                'headers'  => [
                    "apikey" => $this->apiKeyProduction,
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
