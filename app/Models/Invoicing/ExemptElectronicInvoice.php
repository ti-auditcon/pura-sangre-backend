<?php

namespace App\Models\Invoicing;

use App\Models\Invoicing\TaxDocument;
use App\Models\Invoicing\TaxIssuerInterface;

class ExemptElectronicInvoice extends TaxDocument implements TaxIssuerInterface
{
    /**
     * Give form the receipt to be issue to SII
     *
     * @param   object  $receipt
     *
     * @return  object
     */
    public function get($receipt)
    {
        // $boleta = $this->calculateValues($receipt);

        // set taxDocument data with this data below
        return [
            'dte' => [
                'Encabezado' => [
                    'IdDoc' => [
                        "TipoDTE"     => TaxDocumentType::BOLETA_EXENTA_ELECTRONICA,
                        "Folio"       => $receipt->folio,
                        "FchEmis"     => today()->format('Y-m-d'), //  "2020-08-05"
                        "IndServicio" => 3, // tipo de transacción (3 = Boletas de venta y servicios)
                    ],
                    'Emisor' => [
                        "RUTEmisor"    => $this->sender->rut,                    //  "76795561-8",
                        "RznSocEmisor" => $this->sender->razon_social,            //  "HAULMER SPA",
                        "GiroEmisor"   => $this->sender->giro,                    //  "VENTA AL POR MENOR POR CORREO, POR INTERNET Y VIA TELEFONICA",
                        "DirOrigen"    => $this->sender->address,                 //  "ARTURO PRAT 527, CURICO",
                        "CmnaOrigen"   => $this->sender->comuna,                  //  "Curicó",
                        "CiudadOrigen" => $this->sender->city,                    //  "Curicó",
                        // "CdgSIISucur"  => $this->emisor['codigo_sii_sucursal'],  //  81303347
                    ],
                    'Receptor' => [
                        "RUTRecep"    => $receipt->rutrecep,
                        // "CdgIntRecep" => $receipt->cdgintrecep ?? 1,
                        // "RznSocRecep" => $receipt->rznsocrecep ?? "NACIONALES SIN RUT   (USO EXCLUSIVO F-29, NO USAR PARA PRUEBAS)",
                    ],
                    'Totales' => [
                        "MntExe"       => $receipt->mntexe,
                        "MntTotal"     => $receipt->mnttotal,
                        "TotalPeriodo" => $receipt->vlrpagar,
                        "VlrPagar"     => $receipt->vlrpagar,
                    ]
                ],
                'Detalle' => [
                    (object) [
                        "NroLinDet"       => $receipt->NroLinDet,
                        "IndExe"          => $receipt->IndExe,
                        "NmbItem"         => $receipt->NmbItem,
                        "QtyItem"         => $receipt->QtyItem,
                        "PrcItem"         => $receipt->PrcItem,
                        "MontoItem"       => $receipt->MontoItem,
                        // "TpoCodigo"       => $receipt->TpoCodigo,
                        // "ItemEspectaculo" => $receipt->ItemEspectaculo,
                        // "RUTMandante"     => $receipt->RUTMandante,
                        // "InfoTicket"      => $receipt->InfoTicket,
                        // "DscItem"         => $receipt->DscItem,
                        // "UnmdItem"        => $receipt->UnmdItem,
                        // "RecargoMonto"    => $receipt->RecargoMonto,
                    ]
                ]
            ]
        ];
    }
}

