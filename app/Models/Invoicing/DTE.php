<?php

namespace App\Models\Invoicing;

use Carbon\Carbon;
use GuzzleHttp\Client;

class DTE
{
    const HAULMER = 1;

    /**
     *  Número de tipo de DTE
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
     *  Base url for developing as for production
     *
     *  @var  string
     */
    private string $baseUrl;

    /**
     *  Api key for developing as for production
     *
     *  @var  string
     */
    private string $apiKey;


    public function __construct()
    {
        $this->fillProperties('sandbox');

        $this->fillEmisor();
    }

    public function fillEmisor()
    {
        $this->emisor = [
            'rut'                        => '76795561-8',
            "razon_social"               => "HAULMER SPA",
            "giro"                       => "VENTA AL POR MENOR EN EMPRESAS DE VENTA A DISTANCIA VÍA INTERNET; COMERCIO ELEC",
            "address"                    => "ARTURO PRAT 527 CURICO",
            "comuna"                     => "Curicó",
            "city"                       => "Curicó",
            "phone"                      => "954514528",
            "email"                      => "correo@correo.com",
            "codigo_sii_sucursal"        => 81303347,
            "codigo_actividad_economica" => 479100,
        ];
    }

    /**
     *  Fill url and apis for requests
     *
     *  @return  void
     */
    public function fillProperties($environment = 'sandbox'): void
    {
        if ($environment === 'production') {
            $this->apiKey = config('invoicing.haulmer.production.api_key');
            $this->baseUrl = config('invoicing.haulmer.production.base_uri');
            return;
        }

        $this->baseUrl = config('invoicing.haulmer.sandbox.base_uri');
        $this->apiKey = config('invoicing.haulmer.sandbox.api_key');
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
    protected array $emisor;

    public static array $dtes = [
        30 => 'Factura',
        32 => 'Factura de ventas y servicios no afectos o exentos de IVA',
        33 => 'Factura electrónica',
        34 => 'Factura no afecta o exenta electrónica',
        35 => 'Boleta',
        38 => 'Boleta exenta',
        39 => 'Boleta electrónica',
        40 => 'Liquidación factura',
        41 => 'Boleta exenta electrónica',
        43 => 'Liquidación factura electrónica',
        45 => 'Factura de compra',
        46 => 'Factura de compra electrónica',
        48 => 'Pago electrónico',
        50 => 'Guía de despacho',
        52 => 'Guía de despacho electrónica',
        55 => 'Nota de débito',
        56 => 'Nota de débito electrónica',
        60 => 'Nota de crédito',
        61 => 'Nota de crédito electrónica',
        103 => 'Liquidación',
        110 => 'Factura de exportación electrónica',
        111 => 'Nota de débito de exportación electrónica',
        112 => 'Nota de crédito de exportación electrónica'
    ];

    /**
     * [allDTES description]
     *
     * @return  [type]  [return description]
     */
    public static function allDTES()
    {
        return self::$dtes;
    }

    /**
     * [issueReceipt description]
     *
     * @param   [type]  $order  [$order description]
     *
     * @return  [type]          [return description]
     */
    public function issueReceipt($order)
    {
        $dte = $this->fillReceiptData($order);

        return $this->issueToSII(json_encode($dte));
    }

    /**
     *  methodDescription
     *
     *  @return  returnType
     */
    public function fillReceiptData($receipt)
    {
        $boleta = $this->calculateValues($receipt);

        return [
            'dte' => [
                'Encabezado' => [
                    'IdDoc' => [
                        "TipoDTE"     => self::BOLETA_ELECTRONICA_EXENTA,
                        "Folio"       => $receipt->id,
                        "FchEmis"     => today()->format('Y-m-d'), //  "2020-08-05"
                        "IndServicio" => 3, // tipo de transacción (3 = Boletas de venta y servicios)
                    ],
                    'Emisor' => [
                        "RUTEmisor"    => $this->emisor['rut'],                  //  "76795561-8",
                        "RznSocEmisor" => $this->emisor['razon_social'],         //  "HAULMER SPA",
                        "GiroEmisor"   => $this->emisor['giro'],                 //  "VENTA AL POR MENOR POR CORREO, POR INTERNET Y VIA TELEFONICA",
                        "DirOrigen"    => $this->emisor['address'],              //  "ARTURO PRAT 527, CURICO",
                        "CmnaOrigen"   => $this->emisor['comuna'],               //  "Curicó",
                        "CiudadOrigen" => $this->emisor['city'],                 //  "Curicó",
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
     * @return  [type]          [return description]
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
            new DTEErrors($th);
        }
    }
}
