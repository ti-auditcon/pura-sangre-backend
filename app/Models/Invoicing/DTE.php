<?php

namespace App\Models\Invoicing;

use Carbon\Carbon;

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
     *  url for developing and testing
     *
     *  @var  string
     */
    protected $urlDev = 'https://dev-api.haulmer.com/v2';

    protected $urlProduction = 'https://api.haulmer.com/v2';

    protected $apiKeyDev = '928e15a2d14d4a6292345f04960f4bd3';

    protected $apiKeyProduction = 'bab4ce50d3c9406b86ae536d44d6b172';

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

    /**
     *  methodDescription
     *
     *  @return  returnType
     */
    public function __construct()
    {
        $this->emisor = [
            'rut'                        => '76795561-8',
            "razon_social"               => "HAULMER SPA",
            "giro"                       => "VENTA AL POR MENOR EN EMPRESAS DE VENTA A DISTANCIA VÍA INTERNET; COMERCIO ELEC",
            "address"                    => "ARTURO PRAT 527   CURICO",
            "comuna"                     => "Curicó",
            "city"                       => "Curicó",
            "phone"                      => "954514528",
            "email"                      => "correo@correo.com",
            "codigo_sii_sucursal"        => 81303347,
            "codigo_actividad_economica" => 479100,
        ];
    }

    public function issueReceipt($order)
    {
        $order->observations = optional($order->plan)->plan ?? "Pago de plan";

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
                        "Folio"       => $receipt->plan_user_id,
                        "FchEmis"     => Carbon::parse($receipt->date)->format('Y-m-d'), //  "2020-08-05"
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
     *  @param   [type]  $request  [$request description]
     *
     *  @return  [type]            [return description]
     */
    public function calculateValues($request)
    {
        return [
            'monto_neto' => round((int) $request->amount / 1.19),
            'iva' => round(((int) $request->amount / 1.19) * 0.19),
            'total' => (int) $request->amount
        ];
    }

    public function issueToSII($dte)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "{$this->urlDev}/dte/document",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $dte,
            CURLOPT_HTTPHEADER => array(
                "apikey: {$this->apiKeyDev}"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }
}
