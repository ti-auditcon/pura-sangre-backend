<?php

namespace App\Http\Controllers\Bills;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;

class InvoicingController extends Controller
{
    /**
     *  url for developing and testing
     */
    private string $urlDev;

    private string $urlProduction;

    private  string $apiKeyDev;

    private  string $apiKeyProduction;

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
        $this->urlDev = config('invoicing.haulmer.dev.base_uri');
        $this->apiKeyDev = config('invoicing.haulmer.dev.api_key');

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payments.bills');
    }

    public function getDTEs(Request $request)
    {
        $current_page = $request->query('page') ?? 1;

        // $response = json_encode($this->data_response);
        // return $response;
        // $response = json_decode($response);
        
        try {
            $client = new Client(['base_uri' => $this->urlProduction]);

            $response = $client->post("/v2/dte/document/received?page=2", [
                'headers'  => [
                    "apikey" => $this->apiKeyProduction,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    "Page" => $current_page
                ]
            ]);
            $body = $response->getBody();
            $content = $body->getContents();
            $response = json_decode($content);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsFiltered" => intval(count($response->data)),
                "recordsTotal"    => intval($response->total),
                "data"            => $response->data,
                "current_page"    => $response->current_page,
                "last_page"       => $response->last_page
            );

            echo json_encode($json_data);
        } catch (\Throwable $error) {
            dd($error);
            if ($this->hasGuzzleError($error)) {
                return response()->json([
                    'status' => 'Request failed',
                    'message' => $error->response->reasonPhrase
                ], $error->response->statusCode);
            }

            return response()->json([
                'status' => 'Error',
                'message' => 'No se han podido traer los DTEs, Inténtalo de nuevo más tarde.'
            ], 500);
        }
        
        // $json_data = array(
        //     "draw"            => intval($request->input('draw')),
        //     "recordsFiltered" => intval(10),
        //     "recordsTotal"    => intval($response->total),
        //     "data"            => $response->data
        // );

        // echo json_encode($json_data);
        // return response()->json(['data' => [$json_data]]);
    }

    public function hasGuzzleError($error)
    {
        if (isset($error->response) &&
            isset($error->response->reasonPhrase) &&
            isset($error->response->statusCode)) {
            return true;
        }

        return false;
    }
}
