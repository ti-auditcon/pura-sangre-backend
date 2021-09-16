<?php

namespace App\Http\Controllers\Bills;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUserFlow;
use App\Http\Controllers\Controller;
use App\Models\Invoicing\TaxDocument;
use App\Models\Plans\FlowOrderStatus;
use Symfony\Component\HttpFoundation\Response;

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
     *  Instance of a tax document
     *
     *  @var  TaxDocument
     */
    protected $documents;

    protected $document;

    /**
     *  [$fakeTaxDocument description]
     *
     *  @var  object
     */
    public $data_response = array(
        "current_page"    => 1,
        "last_page"       => 6,
        "recordsFiltered" => 30,
        "total"           => 196,
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
                "TpoTranCompra" => 1,
                "Token" => "54ksjgd84gjrwo8hgrjwg8932jrewgjuqw899gj9"
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
                "TpoTranCompra" => 1,
                "Token" => "alkjewyurj89gj893a4hj42398v8sdfjga8dsfgdgdfdssd"
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
                "TpoTranCompra" => 1,
                "Token" => "skadljflj4klwjt689j8493vjkfds98gj9w823232223"
            ],
            [
                "RUTEmisor" => 9071084,
                "DV" => "2",
                "RznSoc" => "MONICA EUGENIA NEUMANN BIRKE",
                "TipoDTE" => 41,
                "Folio" => 9,
                "FchEmis" => "2021-06-07",
                "MntExe" => 959500,
                "MntNeto" => 0,
                "IVA" => 0,
                "MntTotal" => 959500,
                "Acuses" => null,
                "FmaPago" => 0,
                "TpoTranCompra" => 1,
                "Token" => "33333333333333333333333333333"
            ],
            [
                "RUTEmisor" => 9071084,
                "DV" => "2",
                "RznSoc" => "MONICA EUGENIA NEUMANN BIRKE",
                "TipoDTE" => 41,
                "Folio" => 9,
                "FchEmis" => "2021-06-07",
                "MntExe" => 959500,
                "MntNeto" => 0,
                "IVA" => 0,
                "MntTotal" => 959500,
                "Acuses" => null,
                "FmaPago" => 0,
                "TpoTranCompra" => 1,
                "Token" => "fsadg44444444_we46444444"
            ]
        ]
    );

    /**
     *  Instanciate urls for this class
     */
    public function __construct()
    {
        $this->fillDataForInvoicerAPI(config('app.env'));
    }

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
     * [getTaxDocuments description]
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

            return response()->json($json_data);
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            $response = json_decode($error->getResponse()->getBody()->getContents(), true);

            return response()->json([
                'status' => 'Request failed', 'message' => $response['message']
            ], $response['statusCode']);
        }
    }


    /**
     *  [getTaxDocuments description]
     *
     *  @param   Request  $request  [$request description]
     *
     *  @return  json
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

            // $response = json_decode(json_encode($this->data_response));
            $response = $this->addClientAndServiceToReceipts($response);

            $json_data = array(
                "draw"            => intval($request->input('draw')),
                "recordsFiltered" => intval(count($response->data)),
                "recordsTotal"    => intval($response->total),
                "data"            => $response->data,
                "current_page"    => $response->current_page,
                "last_page"       => $response->last_page
            );

            return response()->json($json_data);
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
     *  @return  array
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

    /**
     *  Add name of the client receipt to the data
     *
     *  @param   \stdClass
     *
     *  @return  \stdClass
     */
    public function addClientAndServiceToReceipts($response)
    {
        foreach ($response->data as $data) {
            if ($planUserFlow = PlanUserFlow::join('users', 'users.id', '=', 'plan_user_flows.user_id')
                                            ->join('plans', 'plans.id', '=', 'plan_user_flows.plan_id')
                                            ->where('plan_user_flows.sii_token', $data->Token)
                                            ->first([
                                                'plan_user_flows.id', 'plan_user_flows.paid',
                                                'users.id as user_id', 'users.first_name', 'users.last_name',
                                                'plans.id', 'plans.plan'
                                            ])) {

                $data->full_name = ucwords("{$planUserFlow->first_name} {$planUserFlow->last_name}");
                $data->service = ucfirst($planUserFlow->plan);
                $data->user_id = ucfirst($planUserFlow->user_id);
                $data->paid = $planUserFlow->paid;
            } else {
                $data->full_name = 'sin nombre';
                $data->service = 'sin servicio';
                $data->user_id = null;
                $data->paid = 0;
            }
        }

        return $response;
    }

    public function cancel($token)
    {
        if ($document = PlanUserFlow::where('sii_token', $token)->first()) {
            if ($document->paid === FlowOrderStatus::CANCELED) {
                return response()->json([
                    'status' => 'Request failed', 'message' => "Este documento ya ha sido anulado anteriormente."
                ], Response::HTTP_UNAUTHORIZED);
            }

            $this->documents->cancel($document);

            return response()->json([
                'status' => 'Ok', 'message' => "Se ha anulado el documento correctamente."
            ], Response::HTTP_CREATED);
        }

        // get document data
        $document = new TaxDocument($token);

        if ($document && $document->canBeCancelled()) {
            $response = $document->cancel();

            if ($response->TOKEN) {
                PlanUserFlow::create([
                    'start_date'   => $document->fchemis,
                    'finish_date'  => $document->fchemis,
                    'counter'      => 0,
                    'paid'         => FlowOrderStatus::CANCELED,
                    'amount'       => $document->mnttotal,
                    'observations' => $document->nmbitem,
                    'payment_date' => $document->fchemis,
                    'sii_token'    => $response->TOKEN
                ]);

                return response()->json([
                    'status' => 'Ok', 'message' => "Se ha anulado el documento correctamente."
                ], Response::HTTP_CREATED);
            }
        }

        return response()->json([
            'status' => 'Ok', 'message' => "No se ha podido anular el documento."
        ], Response::HTTP_UNAUTHORIZED);

        // check if the bill is already canceled
        // response with json "Esta boleta ya ha sido anulada"

        // annul bill
        // change the status of the bill
        //
    }
}
