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
     *  Número de tipo de TaxDocument de BOLETA_ELECTRONICA_EXENTA
     *
     *  @var  int
     */
    const BOLETA_ELECTRONICA_EXENTA = 41;

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
    public $DirRecep;
    public $rutrecep;
    public $girorecep;
    public $rznsocrecep;
    // public $cdgintrecep;
    // Detalle
    public $nrolindet;
    public $TpoCodigo;
    public $IndExe;
    public $nmbitem;
    public $infoticket;
    public $dscitem;
    public $qtyitem;

    public function getToken()
    {
        return $this->token;
    }

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
     * [$list description]
     *
     *  @var  array
     */
    public static $list = [
        30  => 'Factura',
        32  => 'Factura de ventas y servicios no afectos o exentos de IVA',
        33  => 'Factura electrónica',
        34  => 'Factura no afecta o exenta electrónica',
        35  => 'Boleta',
        38  => 'Boleta exenta',
        39  => 'Boleta electrónica',
        40  => 'Liquidación factura',
        41  => 'Boleta exenta electrónica',
        43  => 'Liquidación factura electrónica',
        45  => 'Factura de compra',
        46  => 'Factura de compra electrónica',
        48  => 'Pago electrónico',
        50  => 'Guía de despacho',
        52  => 'Guía de despacho electrónica',
        55  => 'Nota de débito',
        56  => 'Nota de débito electrónica',
        60  => 'Nota de crédito',
        61  => 'Nota de crédito electrónica',
        103 => 'Liquidación',
        110 => 'Factura de exportación electrónica',
        111 => 'Nota de débito de exportación electrónica',
        112 => 'Nota de crédito de exportación electrónica'
    ];

    /**
     *  At start class fill values for Haulmer API
     */
    public function __construct($token = null)
    {
        $this->fillDataForInvoicerAPI(config('app.env'));

        $this->create($token);
    }


    /**
     * [fillDataForInvoicerAPI description]
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

        $this->fillEmisor($environment);
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
     * [fillEmisor description]
     *
     * @param   [type]  $environment  [$environment description]
     *
     */
    public function fillEmisor($environment)
    {
        $this->emisor = config("invoicing.haulmer.{$environment}.emisor");
    }

    public function canBeCancelled() :bool
    {
        $this->status = $this->status($this->token);

        return in_array($this->status, TaxDocumentStatus::cancellableStatuses());
    }

    /**
     *  [allTaxDocumentS description]
     *
     *  @return  [type]  [return description]
     */
    public static function list()
    {
        return self::$list;
    }

    /**
     *  Get the data of a tax document an fill it into $tax property
     *
     *  @param   string|null  $token
     *
     *  @return  void|null
     */
    public function create($token)
    {
        if (is_null($token)) return;

        try {
            $client = new Client(['base_uri' => $this->baseUrl]);

            $response = $client->get("/v2/dte/document/{$token}/json", [
                'headers'  => [ "apikey" => $this->apiKey ]
            ]);

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
        // dd($data);
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

        return $this->issueToSII(json_encode($dte));
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

        return [
            'dte' => [
                'Encabezado' => [
                    'IdDoc' => [
                        "TipoTaxDocument" => $invoiceType,
                        "Folio"           => $receipt->id,
                        "FchEmis"         => today()->format('Y-m-d'), //  "2020-08-05"
                        "IndServicio"     => 3, // tipo de transacción (3 = Boletas de venta y servicios)
                    ],
                    'Emisor' => [
                        "RUTEmisor"    => $this->emisor['rut'],                     //  "76795561-8",
                        "RznSocEmisor" => $this->emisor['razon_social'],            //  "HAULMER SPA",
                        "GiroEmisor"   => $this->emisor['giro'],                    //  "VENTA AL POR MENOR POR CORREO, POR INTERNET Y VIA TELEFONICA",
                        "DirOrigen"    => $this->emisor['address'],                 //  "ARTURO PRAT 527, CURICO",
                        "CmnaOrigen"   => $this->emisor['comuna'],                  //  "Curicó",
                        "CiudadOrigen" => $this->emisor['city'],                    //  "Curicó",
                        // "CdgSIISucur"  => $this->emisor['codigo_sii_sucursal'],  //  81303347
                    ],
                    'Receptor' => [
                        "RUTRecep"  => self::RUT_GENERICO, //  "66666666-6"
                    ],
                    'Totales' => [
                        "MntExe"   => $boleta['total'],
                        "MntTotal" => $boleta['total'],
                        "VlrPagar" => $boleta['total']
                    ]
                ],
                'Detalle' => [
                    0 => [
                        "NroLinDet"       => 1,
                        "TpoCodigo"       => null,
                        "IndExe"          => 1,
                        "ItemEspectaculo" => 2,
                        "RUTMandante"     => $this->emisor['rut'],
                        "NmbItem"         => $receipt->observations,
                        "InfoTicket"      => "",
                        "DscItem"         => "",
                        "QtyItem"         => 1,
                        "UnmdItem"        => "",
                        "PrcItem"         => $boleta['total'],
                        "RecargoMonto"    => 0,
                        "MontoItem"       => $boleta['total']
                    ]
                ]
            ]
        ];
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
     * [issueToSII description]
     *
     * @param   [type]  $dte  [$dte description]
     *
     * @return  [type]        [return description]
     */
    public function issueToSII($dte)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "{$this->baseUrl}/dte/document",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => $dte,
            CURLOPT_HTTPHEADER     => array(
                "apikey: {$this->apiKey}"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }

    /**
     * [getReceipt description]
     *
     * @param   [type]  $token  [$token description]
     *
     * @return  object|string
     */
    public function getReceipt($token)
    {
        try {
            $client = new Client(['base_uri' => $this->baseUrl]);
            $response = $client->get("/v2/dte/document/{$token}/pdf", [
                'headers'  => [
                    "apikey" => $this->apiKey
                ]
            ]);
            $content = $response->getBody()->getContents();

            return json_decode($content);
        } catch (\Throwable $th) {
            new TaxDocumentErrors($th);
        }
    }

    public function cancel()
    {
        try {
            $client = new Client(['base_uri' => $this->baseUrl]);
            $response = $client->post("/v2/dte/document", [
                'headers'  => [
                    "apikey" => $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => app(ElectronicCreditNote::class)->get($this)
            ]);

            return json_decode($response->getBody()->getContents());
        } catch (\Exception $error) {

            dd($error->getResponse()->getBody()->getContents());
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
            $client = new Client(['base_uri' => $this->baseUrl]);

            $response = $client->get("/v2/dte/document/{$token}/status", [
                'headers'  => [
                    "apikey" => $this->apiKey
                ]
            ]);

            $response = json_decode($response->getBody()->getContents());

            if ($response->estado) return $response->estado;
        } catch (\GuzzleHttp\Exception\ClientException $error) {
            return null;
        }
    }

    public function hasIVA()
    {
        return boolval($this->tasaiva > 0);
    }
}
