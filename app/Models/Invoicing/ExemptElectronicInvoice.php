<?php 

namespace App\Models\Invoicing;

use App\Models\Invoicing\TaxDocument;
use App\Models\Invoicing\TaxIssuerInterface;

class ExemptElectronicInvoice extends TaxDocument implements TaxIssuerInterface
{
    /**
     *  Give form the receipt to be issue to SII
     *
     *  @param   object  $receipt
     * 
     *  @return  array
     */
    public function get($receipt)
    {
        $boleta = $this->calculateValues($receipt);

        return [
            'dte' => [
                'Encabezado' => [
                    'IdDoc' => [
                        "TipoTaxDocument" => self::BOLETA_ELECTRONICA_EXENTA,
                        "Folio"           => $receipt->folio,
                        "FchEmis"         => today()->format('Y-m-d'), //  "2020-08-05"
                        "IndServicio"     => 3, // tipo de transacción (3 = Boletas de venta y servicios)
                    ],
                    'Emisor' => [
                        "RUTEmisor"    => $this->emisor->rut,                    //  "76795561-8",
                        "RznSocEmisor" => $this->emisor->razon_social,            //  "HAULMER SPA",
                        "GiroEmisor"   => $this->emisor->giro,                    //  "VENTA AL POR MENOR POR CORREO, POR INTERNET Y VIA TELEFONICA",
                        "DirOrigen"    => $this->emisor->address,                 //  "ARTURO PRAT 527, CURICO",
                        "CmnaOrigen"   => $this->emisor->comuna,                  //  "Curicó",
                        "CiudadOrigen" => $this->emisor->city,                    //  "Curicó",
                        // "CdgSIISucur"  => $this->emisor['codigo_sii_sucursal'],  //  81303347
                    ],
                    'Receptor' => [
                        "RUTRecep"    => $receipt->rutrecep,
                        "CdgIntRecep" => $receipt->cdgintrecep ?? 1,
                        "RznSocRecep" => $receipt->rznsocrecep ?? "NACIONALES SIN RUT   (USO EXCLUSIVO F-29, NO USAR PARA PRUEBAS)",
                    ],
                    'Totales' => [
                        "MntExe"   => $boleta->mntexe,
                        "MntTotal" => $boleta->mnttotal,
                        "VlrPagar" => $boleta->vlrpagar,
                    ]
                ],
                'Detalle' => [
                    0 => [
                        "NroLinDet"       => $receipt->NroLinDet,
                        "TpoCodigo"       => $receipt->TpoCodigo,
                        "IndExe"          => $receipt->IndExe,
                        "ItemEspectaculo" => $receipt->ItemEspectaculo,
                        "RUTMandante"     => $receipt->RUTMandante,
                        "NmbItem"         => $receipt->NmbItem,
                        "InfoTicket"      => $receipt->InfoTicket,
                        "DscItem"         => $receipt->DscItem,
                        "QtyItem"         => $receipt->QtyItem,
                        "UnmdItem"        => $receipt->UnmdItem,
                        "PrcItem"         => $receipt->PrcItem,
                        "RecargoMonto"    => $receipt->RecargoMonto,
                        "MontoItem"       => $receipt->MontoItem,
                    ]
                ]
            ]
        ];
    }
}

