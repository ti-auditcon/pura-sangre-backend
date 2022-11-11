<?php 

namespace App\Models\Invoicing;

use App\Models\Invoicing\TaxDocument;
use App\Models\Invoicing\TaxDocumentType;
use App\Models\Invoicing\TaxIssuerInterface;

class ElectronicCreditNote implements TaxIssuerInterface
{
    /**  Código utilizado para los siguientes
     *    casos:
     *     a) Nota de Crédito que elimina
     *     documento de referencia en forma
     *     completa (Factura de venta, Nota
     *     de débito, o Factura de compra
     *     b) Nota de crédito que corrige un
     *     texto del documento de referencia
     *     (ver campo Corrección Factura)
     *     c) Nota de Débito que elimina una
     *     Nota de Crédito en la referencia en
     *     forma completa
     *     d) Notas de crédito o débito que
     *     corrigen montos de otro
     *     documento
     *    CASOS a) b) y c) DEBEN TENER UN ÚNICO DOCUME
     */

    /**
     * [VOID_REFERENCE_DOCUMENT description]
     *
     * @var  integer
     */
    const CANCEL_REFERENCE_DOCUMENT = 1;

    /**
     * [VOID_REFERENCE_DOCUMENT description]
     *
     * @var  integer
     */
    const FIX_REFERENCE_DOCUMENT_TEXT = 2;
    
    /**
     * [VOID_REFERENCE_DOCUMENT description]
     *
     * @var  integer
     */
    const FIX_AMOUNTS = 3;

    /**
     * Build the invoice with all the data to be issue
     *
     * @param   TaxDocument  $receipt
     * 
     * @return  array
     */
    public function get(TaxDocument $receipt)
    {
        return [
            'dte' => [
                'Encabezado' => [
                    'IdDoc'   => [
                        'TipoDTE'      => TaxDocumentType::NOTA_DE_CREDITO_ELECTRONICA,
                        'Folio'        => $receipt->folio,
                        'FchEmis'      => today()->format('Y-m-d'), //  '2020-08-05'
                        'TpoTranVenta' => $receipt->tpotranventa, // tipo de transacción (3 = Boletas de venta y servicios)
                        'FmaPago'      => 2 // credito Valor 1: Contado; 2: Crédito 3: Sin costo (entrega gratuita)
                        // 'PeriodoDesde' => today()->format('Y-m-d'),
                        // 'PeriodoHasta' => today()->format('Y-m-d'),
                        // 'FchVenc'      => today()->format('Y-m-d'),
                    ],
                    'Emisor'  => [
                        'RUTEmisor'   => $receipt->sender->rut,           //  '76795561-8',
                        'RznSoc'      => $receipt->sender->razon_social,  //  'HAULMER SPA',
                        'GiroEmis'    => $receipt->sender->giro,          //  'VENTA AL POR MENOR POR CORREO, POR INTERNET Y VIA TELEFONICA',
                        'Acteco'      => $receipt->sender->codigo_actividad_economica,
                        'DirOrigen'   => $receipt->sender->address,       //  'ARTURO PRAT 527, CURICO',
                        'CmnaOrigen'  => $receipt->sender->comuna,        //  'Curicó',
                        'Telefono'    => $receipt->sender->phone,
                        'CdgSIISucur' => $receipt->sender->codigo_sii_sucursal
                    ],
                    'Receptor' => [
                        'RUTRecep'    => $receipt->rutrecep,
                        'RznSocRecep' => $receipt->rznsocrecep,
                        // "CdgIntRecep" => $receipt->cdgintrecep ?? 1,
                        'GiroRecep'   => $receipt->girorecep,
                        'Contacto'    => $receipt->contacto,
                        'DirRecep'    => $receipt->dirrecep,
                        'CmnaRecep'   => $receipt->cmnarecep,
                    ],
                    'Totales'  => [
                        'MntNeto'      => $receipt->mntneto,
                        'TasaIVA'      => 19,
                        'IVA'          => $receipt->iva,
                        'MntTotal'     => $receipt->mnttotal,
                        'MontoPeriodo' => $receipt->mnttotal,
                        'MntExe'       => $receipt->mntexe,
                        'VlrPagar'     => $receipt->mnttotal
                    ],
                ],
                'Detalle'    => $receipt->detalle,
                'Referencia' => [
                    'NroLinRef' => 1,
                    'TpoDocRef' => $receipt->tipodte,
                    'FolioRef'  => $receipt->folio,
                    'FchRef'    => $receipt->fchemis,
                    'CodRef'    => self::CANCEL_REFERENCE_DOCUMENT,
                ],
            ]
        ];
    }
}
