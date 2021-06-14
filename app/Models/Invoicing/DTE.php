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

    // /**
    //  *  methodDescription
    //  *
    //  *  @return  returnType
    //  */
    // public function __construct()
    // {
    //     $this->emisor = [
    //         'rut'                        => '76795561-8',
    //         "razon_social"               => "HAULMER SPA",
    //         "giro"                       => "VENTA AL POR MENOR EN EMPRESAS DE VENTA A DISTANCIA VÍA INTERNET; COMERCIO ELEC",
    //         "address"                    => "ARTURO PRAT 527   CURICO",
    //         "comuna"                     => "Curicó",
    //         "city"                       => "Curicó",
    //         "phone"                      => "954514528",
    //         "email"                      => "correo@correo.com",
    //         "codigo_sii_sucursal"        => 81303347,
    //         "codigo_actividad_economica" => 479100,
    //     ];
    // }

    public static function allDTES()
    {
        return self::$dtes;
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
