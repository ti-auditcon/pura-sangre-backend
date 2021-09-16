<?php 

namespace App\Models\Invoicing;

use App\Models\Invoicing\TaxDocument;
use App\Models\Invoicing\TaxDocumentType;
use App\Models\Invoicing\InvoiceIssuerInterface;

class ElectronicCreditNote extends TaxDocument implements InvoiceIssuerInterface
{
    /**    Código utilizado para los siguientes
     *     casos:
     *      a) Nota de Crédito que elimina
     *      documento de referencia en forma
     *      completa (Factura de venta, Nota
     *      de débito, o Factura de compra
     *      b) Nota de crédito que corrige un
     *      texto del documento de referencia
     *      (ver campo Corrección Factura)
     *      c) Nota de Débito que elimina una
     *      Nota de Crédito en la referencia en
     *      forma completa
     *      d) Notas de crédito o débito que
     *      corrigen montos de otro
     *      documento
     *     CASOS a) b) y c) DEBEN TENER UN ÚNICO DOCUME
     */

    /**
     *  [VOID_REFERENCE_DOCUMENT description]
     *
     *  @var  integer
     */
    const CANCEL_REFERENCE_DOCUMENT = 1;

    /**
     *  [VOID_REFERENCE_DOCUMENT description]
     *
     *  @var  integer
     */
    const FIX_REFERENCE_DOCUMENT_TEXT = 2;
    
    /**
     *  [VOID_REFERENCE_DOCUMENT description]
     *
     *  @var  integer
     */
    const FIX_AMOUNTS = 3;

    /**
     *  [get description]
     *
     *  @param   [type]  $receipt  [$receipt description]
     *
     *  @return  array
     */
    public function get($receipt)
    {
        return [
            "dte" => [
                "Encabezado" => [
                    "IdDoc" => [
                        "TipoDTE"      => TaxDocumentType::NOTA_DE_CREDITO_ELECTRONICA,
                        "Folio"        => $receipt->folio,
                        "FchEmis"      => today()->format('Y-m-d'), //  "2020-08-05"
                        "IndServicio"  => $receipt->tpotranventa,  // tipo de transacción (3 = Boletas de venta y servicios)
                        "PeriodoDesde" => today()->format('Y-m-d'),
                        "PeriodoHasta" => today()->format('Y-m-d'),
                        "FchVenc"      => today()->format('Y-m-d'),
                    ],
                    "Emisor" => [
                        "RUTEmisor"    => $this->emisor->rut,           //  "76795561-8",
                        "RznSoc"       => $this->emisor->razon_social,  //  "HAULMER SPA",
                        "GiroEmis"     => $this->emisor->giro,          //  "VENTA AL POR MENOR POR CORREO, POR INTERNET Y VIA TELEFONICA",
                        "Telefono"     => $this->emisor->phone,
                        "CorreoEmisor" => $this->emisor->email,
                        "Acteco"       => $this->emisor->codigo_actividad_economica,
                        "DirOrigen"    => $this->emisor->address,       //  "ARTURO PRAT 527, CURICO",
                        "CmnaOrigen"   => $this->emisor->comuna,        //  "Curicó",
                        "CiudadOrigen" => $this->emisor->city,          //  "Curicó",
                    ],
                    "Receptor" => [
                        "RUTRecep"    => $receipt->rutrecep,
                        "CdgIntRecep" => $receipt->cdgintrecep ?? 1,
                        "RznSocRecep" => $receipt->rznsocrecep,
                    ],
                    "Totales" => [
                        "MntNeto"  => $receipt->mntneto,
                        "IVA"      => $receipt->iva,
                        "MntExe"   => 0, // suma de todos los valores exentos de iva
                        "MntTotal" => $receipt->mnttotal,
                        "VlrPagar" => $receipt->mnttotal
                    ],
                ],
                "Detalle" => [
                    0 => [
                        "NroLinDet"       => $receipt->nrolindet,
                        // "TpoCodigo"       => null,
                        // "IndExe"          => 1,
                        "NmbItem"         => $receipt->nmbitem,
                        // "InfoTicket"      => "",
                        // "DscItem"         => "",
                        "QtyItem"         => $receipt->qtyitem,
                        // "UnmdItem"        => null,
                        "PrcItem"         => $receipt->mntneto,
                        "MontoItem"       => $receipt->mntneto
                    ]
                ],
                "Referencia" => [
                    "NroLinRef" => 1,
                    "TpoDocRef" => $receipt->tipodte,
                    "FolioRef"  => $receipt->folio,
                    // "RUTOtr"    => self::RUT_GENERICO,
                    "FchRef"    => $receipt->fchemis,
                    "CodRef"    => self::CANCEL_REFERENCE_DOCUMENT,
                ],
            ]
        ];
    }

}
//  Tipos de documentos tributarios
//  Como respaldo para las operaciones de recibo y entrega de mercancía o dinero; entre los documentos tributarios de uso común se encuentran:
//  
//  Notas de crédito
//  Notas de débito
//  Guías de despacho
//  Liquidaciones de facturas
//  Boletas
//  Facturas