<?php

namespace App\Models\Invoicing;

use App\Models\Invoicing\Haulmer\TaxDocumentStatus;
use GuzzleHttp\Client;

class TaxDocument
{
    /**
     *  Haulmer inviocing number
     *
     *  @var  int
     */
    const HAULMER = 1;

    /**
     *  Value for receipts or invoices which are not issued
     *
     *  @var  string
     */
    const NOT_ISSUED = 'sin emision';

    /**
     *  En el Rut Receptor se permite el uso de RUT genérico en caso de Boletas de Ventas y Servicios
     *  (no periódicos ni domiciliarios): valor: 66.666.666-6
     */
    const RUT_GENERICO = "66666666-6";

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $fillable = [
        "token",
        // Encabezado
        'Folio',
        'FchEmis',
        'FmaPago',
        "TipoDTE",
        "TpoTranVenta",
        "TpoTranCompra",
        // Totales
        'IVA',
        'MntNeto',
        'TasaIVA',
        'MntTotal',

        "Contacto",
        "DirRecep",
        "RUTRecep",
        "CmnaRecep",
        "GiroRecep",
        "RznSocRecep",

        // "CdgIntRecep",
        "NroLinDet" ,
        "TpoCodigo",
        "IndExe",
        // Detalle
        "NmbItem",
        "InfoTicket",
        "DscItem",
        "QtyItem",
    ];

    /**
     *  Base url as for developing, as for production
     *
     *  @var  string
     */
    private $baseUrl;

    /**
     *  Api key as for developing as for production
     *
     *  @var  string
     */
    private $apiKey;

    /**
     *  To ckech if the requests are goin to be through SSL
     *
     *  @var  boolean
     */
    protected $verifiedSSL;

    /**
     *  Tax data
     */
    protected $token;

    // Encabezado
    public $folio;
    public $fchemis;
    public $fmapago;
    public $tipodte;
    public $tpotranventa;
    public $tpotrancompra;
    // totales
    public $iva;
    public $mntneto;
    public $tasaiva;
    public $mnttotal;

    public $status;
    //   +"TasaIVA": "19"
    public $contacto;
    public $dirrecep;
    public $rutrecep;
    public $girorecep;
    public $rznsocrecep;
    public $cmnarecep;
    // public $cdgintrecep;
    // Detalle
    public $nrolindet;
    public $tpocodigo;
    public $indexe;
    public $nmbitem;
    public $infoticket;
    public $dscitem;
    public $qtyitem;



    /**
     *  La parte que va a emitir la boleta por el servicio o producto (ejemplo, PuraSangre, KatsuCrossFit)
     *  Contiene los siguientes datos del emisor
     *      apiKey:               haulmer_api_key
     *      rut:                  rut
     *      razon_social:         razon_social
     *      giro_emisor:          giro
     *      address:              address
     *      comuna:               comuna
     *      ciudad:               ciudad
     *      telefono:             phone
     *      email:                email
     *      codigo_sii_sucursal:  codigo_sii_sucursal
     *      acteco:               codigo_actividades_economicas  código de actividades economicas  // actividades economicas
     *
     *  @var  array
     */
    protected $emisor;

    /**
     *  Instance of the Guzzle Client to make requests to Openfactura
     *
     *  @var  Client
     */
    protected $httpRequest;

    /**
     *  At start class fill values for Haulmer API
     *  
     *  note: fillDataForInvoiceAPI must be before than the initialization of Guzzle Client,
     *        because we need to fill the baseUrl first in order to fill the base_uri of the Guzzle Client
     * 
     */
    public function __construct($token = null)
    {
        $this->setTaxIssuerData(config('app.env'));

        $this->initializeGuzzleClient();

        $this->setToken($token);
        $this->create();
    }

    /**
     * [setTaxIssuerData description]
     *
     *  @param   [type]   $environment  [$environment description]
     *  @param   sandbox                [ description]
     */
    public function setTaxIssuerData($environment = 'sandbox')
    {
        if ($environment === 'local' || $environment === 'testing') {
            $environment = 'sandbox';
        }

        $this->fillUrlAndKeys($environment);

        $this->fillEmisor($environment);
    }

    /**
     *  Set baseUrl and apiKey for Guzzle requests
     *
     *  @return  void
     */
    public function initializeGuzzleClient()
    {
        $this->httpRequest = new Client([
            'base_uri' => $this->baseUrl,
            'headers'  => [ "apikey" => $this->apiKey ]
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getBaseUri()
    {
        return $this->baseUrl;
    }

    /**
     *  Transform all the fillable value into an object,
     *  and fill them all with the tax document fetched
     *
     *  @return  object
     */
    public function getData()
    {
        $object = (object) [];
        foreach ($this->fillable as $key => $value) {
            $object->{strtolower($value)} = $this->{strtolower($value)};
        }

        return $object;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getVerifiedSsl()
    {
        return $this->verifiedSSL;
    }

    /**
     *  Check if this class has setted the apiKey
     *
     *  @return  boolean
     */
    public function hasApiKey() :bool
    {
        return boolval($this->apiKey);
    }

    function getEmisor()
    {
        return $this->emisor;
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
     *  [fillEmisor description]
     *
     *  @param   [type]  $environment  [$environment description]
     *
     */
    public function fillEmisor($environment)
    {
        $this->emisor = $this->arrayToObject(config("invoicing.haulmer.{$environment}.emisor"));
    }


    /**
     *  Get the protected token of this tax document
     *
     *  @return  string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     *  Set token value
     *
     *  @param   string  $token
     * 
     *  @return  void
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     *  Check if there is a token

     *  @return  boolean
     */
    public function tokenIsSetted()
    {
        return boolval($this->getToken());
    }

    /**
     *  The opposite of tokenIsSetted method
     *
     *  @return boolean
     */
    public function tokenIsNotSetted()
    {
        return !$this->tokenIsSetted();
    }

    /**
     *  Check if this tax document has IVA, it means affected to taxes (19%)
     *
     *  @return  boolean
     */
    public function hasIVA()
    {
        return boolval($this->tasaiva > 0);
    }

    /**
     *  Get the status of this tax document and check if has an cancellable status
     *
     *  @return  boolean
     */
    public function canBeCancelled() :bool
    {
        $this->status = $this->status($this->token);

        return in_array($this->status, TaxDocumentStatus::cancellableList());
    }

    /**
     *  Get the data of a tax document an fill it into $tax property
     *
     *  @param   string|null  $token
     *
     *  @return  void|null
     */
    public function create()
    {
        if ($this->tokenIsNotSetted()) return;

        try {
            $response = $this->httpRequest->get("/v2/dte/document/{$token}/json");

            $this->setTax(json_decode($response->getBody()->getContents()));
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            return null;
        }
    }

    /**
     *  [setTax description]
     *
     *  @param   [type]  $data  [$data description]
     *
     *  @return  void
     */
    public function setTax($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                $value = $this->setTax($value);
            }

            if (in_array($key, $this->fillable)) {
                $this->{strtolower($key)} = $value;
            }
        }
    }

    /**
     *  [issueReceipt description]
     *
     *  @param   [type]  $order  [$order description]
     *
     *  @return  [type]          [return description]
     */
    public function issueReceipt($order)
    {
        $dte = $this->fillReceiptData($order, self::BOLETA_ELECTRONICA_EXENTA);

        return $this->issue(json_encode($dte));
    }

    /**
     *  [fillReceiptData description]
     *
     *  @param   object   $receipt
     *  @param   integer  $invoiceType
     *
     *  @return  array
     */
    public function fillReceiptData($receipt, $invoiceType)
    {
        $boleta = $this->calculateValues($receipt);

        return app(ExemptElectronicInvoice::class)->get($boleta);
    }

    /**
     *  [calculateValues description]
     *
     *  @param   Request  $request
     *
     *  @return  array
     */
    public function calculateValues($request)
    {
        return [
            'monto_neto' => round((int) $request->amount / 1.19),
            'iva'        => round(((int) $request->amount / 1.19) * 0.19),
            'total'      => (int) $request->amount
        ];
    }

    /**
     *  Issue invoice to SII
     *
     *  todo: the sended dte should be $this instead
     *  @param   TaxDocument  $dte
     *
     *  @return  json
     */
    public function issue($dte)
    {
        $response = $this->httpRequest->post("/dte/document", [
            $dte
        ]);

        return json_decode($response->getBody()->getContents());
    }

    /**
     *  Fetch invoice data from SII according the given $token
     *
     *  @param   string         $token
     *
     *  @return  object|string
     */
    public function get($token)
    {
        try {
            $response = $this->httpRequest->get("/v2/dte/document/{$token}/pdf");

            return json_decode($response->getBody()->getContents());
        } catch (\Throwable $error) {
            new TaxDocumentErrors($error);
            
            return json_decode($error->getResponse()->getBody()->getContents(), true);
        }
    }

    /**
     *  Issue a credit note to cancel an specific invoice
     *
     *  @return  json
     */
    public function cancel()
    {
        try {
            $response = $this->httpRequest->post("/v2/dte/document", [
                'headers'  => [
                    'Content-Type' => 'application/json',
                ],
                'json' => app(ElectronicCreditNote::class)->get($this)
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $error) {
            return json_decode($error->getResponse()->getBody()->getContents(), true);
        }
    }


    /**
     *  Check the status of a tax document
     *
     *  @param   string  $token  Has to be a valid token by Openfactura
     *
     *  @return  json
     */
    public function status($token)
    {
        try {
            $response = $this->httpRequest->get("/v2/dte/document/{$token}/status");

            $response = json_decode($response->getBody()->getContents());

            if ($response->estado) return $response->estado;
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            return null;
        }
    }

    public function arrayToObject($array)
    {
        $object = (object) [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayToObject($value);
            }

            $object->$key = $value;
        }

        return $object;
    }
}
