<?php

namespace App\Http\Controllers\Bills;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoicingController extends Controller
{
    /**
     *  url for developing and testing
     *
     *  @var  string
     */
    protected $urlDev = 'https://dev-api.haulmer.com/v2';

    protected $urlProduction = 'https://api.haulmer.com/v2';

    protected $apiKeyDev = '928e15a2d14d4a6292345f04960f4bd3';

    protected $apiKeyProduction = 'bab4ce50d3c9406b86ae536d44d6b172';


    public $data_response = [
        "current_page" => 1,
        "last_page" => 6556,
        "total" => 196654,
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
        $current_page = $request->page ?? 1;

        // $response = json_encode($this->data_response);
        // $response = json_decode($response);

        try {
            $client = new Client(['base_uri' => $this->urlDev]);

            $response = $client->post("/v2/dte/document/received", [
                'headers'  => [
                    "apikey" => $this->apiKeyDev
                ],
                'json' => [
                    'page' => $current_page,
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
        dd($error);
        if (isset($error->response) &&
            isset($error->response->reasonPhrase) &&
            isset($error->response->statusCode)) {
            return true;
        }

        return false;
    }
}
