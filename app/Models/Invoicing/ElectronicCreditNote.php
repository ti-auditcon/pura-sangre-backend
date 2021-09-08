<?php 

namespace App\Models\Invoicing;

use App\Models\Invoicing\DTE;

class ElectronicCreditNote extends DTE
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
     *  Número de tipo de DTE de BOLETA_ELECTRONICA_EXENTA
     *
     *  @var  int
     */
    const NOTA_CREDITO_ELECTRONICA = 61;

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
            'dte' => [
                'Encabezado' => [
                    'IdDoc' => [
                        "TipoDTE"     => self::NOTA_CREDITO_ELECTRONICA,
                        "Folio"       => $receipt->id,
                        "FchEmis"     => today()->format('Y-m-d'), //  "2020-08-05"
                        "IndServicio" => 3, // tipo de transacción (3 = Boletas de venta y servicios)
                    ],
                    'Emisor' => [
                        "RUTEmisor"    => $this->emisor['rut'],           //  "76795561-8",
                        "RznSoc"       => $this->emisor['razon_social'],  //  "HAULMER SPA",
                        "GiroEmis"     => $this->emisor['giro'],          //  "VENTA AL POR MENOR POR CORREO, POR INTERNET Y VIA TELEFONICA",
                        "Telefono"     => "98745655",
                        "CorreoEmisor" => "correo@correo.cl",
                        "Acteco"       => 479100,
                        "DirOrigen"    => $this->emisor['address'],       //  "ARTURO PRAT 527, CURICO",
                        "CmnaOrigen"   => $this->emisor['comuna'],        //  "Curicó",
                        "CiudadOrigen" => $this->emisor['city'],          //  "Curicó",
                    ],
                    'Receptor' => [
                        "RUTRecep"    => self::RUT_GENERICO, //  "66666666-6"
                        "RznSocRecep" => "NACIONALES SIN RUT   (USO EXCLUSIVO F-29, NO USAR PARA PRUEBAS)",
                        "CdgIntRecep" => 1
                    ],
                    'Totales' => [
                        "MntExe"   => $receipt->amount,
                        "MntTotal" => $receipt->amount,
                        "VlrPagar" => $receipt->amount
                    ],
                ],
                'Detalle' => [
                    0 => [
                        "NroLinDet"       => 1,
                        "TpoCodigo"       => null,
                        "IndExe"          => 1,
                        "NmbItem"         => $receipt->observations,
                        "InfoTicket"      => "",
                        "DscItem"         => "",
                        "QtyItem"         => 1,
                        "UnmdItem"        => "",
                        "PrcItem"         => $receipt->amount,
                        "MontoItem"       => $receipt->amount
                    ]
                ],
                "Referencia" => [
                    "NroLinRef" => 1,
                    "TpoDocRef" => $receipt->id_referencia,
                    "FolioRef"  => $receipt->id,
                    "RUTOtr"    => self::RUT_GENERICO,
                    "FchRef"    => $receipt->issue_date,
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