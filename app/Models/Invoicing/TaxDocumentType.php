<?php

namespace App\Models\Invoicing;

class TaxDocumentType
{

    /**
     *  All the tax documents types by SII
     */

    public const FACTURA = 30;
    public const FACTURA_DE_VENTAS_Y_SERVICIOS_NO_AFECTOS_O_EXENTOS_DE_IVA = 32;
    public const FACTURA_ELECTRONICA = 33;
    public const FACTURA_NO_AFECTA_O_EXENTA_ELECTRONICA = 34;
    public const BOLETA = 35;
    public const BOLETA_EXENTA = 38;
    public const BOLETA_ELECTRONICA = 39;
    public const LIQUIDACION_FACTURA = 40;
    public const BOLETA_EXENTA_ELECTRONICA = 41;
    public const LIQUIDACION_FACTURA_ELECTRONICA = 43;
    public const FACTURA_DE_COMPRA = 45;
    public const FACTURA_DE_COMPRA_ELECTRONICA = 46;
    public const PAGO_ELECTRONICO = 48;
    public const GUIA_DE_DESPACHO = 50;
    public const GUIA_DE_DESPACHO_ELECTRONICA = 52;
    public const NOTA_DE_DEBITO = 55;
    public const NOTA_DE_DEBITO_ELECTRONICA = 56;
    public const NOTA_DE_CREDITO = 60;
    public const NOTA_DE_CREDITO_ELECTRONICA = 61;
    public const LIQUIDACION = 103;
    public const FACTURA_DE_EXPORTACION_ELECTRONICA = 110;
    public const NOTA_DE_DEBITO_DE_EXPORTACION_ELECTRONICA = 111;
    public const NOTA_DE_CREDITO_DE_EXPORTACION_ELECTRONICA = 112;

    /**
     *  Get the list of all types of taxes
     *
     *  @return  array
     */
    public static function list()
    {
        return [
            self::FACTURA                                                   => 'Factura', 
            self::FACTURA_DE_VENTAS_Y_SERVICIOS_NO_AFECTOS_O_EXENTOS_DE_IVA => 'Factura de ventas y servicios no afectos o exentos de IVA',
            self::FACTURA_ELECTRONICA                                       => 'Factura electrónica',
            self::FACTURA_NO_AFECTA_O_EXENTA_ELECTRONICA                    => 'Factura no afecta o exenta electrónica',
            self::BOLETA                                                    => 'Boleta',
            self::BOLETA_EXENTA                                             => 'Boleta exenta',
            self::BOLETA_ELECTRONICA                                        => 'Boleta electrónica',
            self::LIQUIDACION_FACTURA                                       => 'Liquidación factura',
            self::BOLETA_EXENTA_ELECTRONICA                                 => 'Boleta exenta electrónica',
            self::LIQUIDACION_FACTURA_ELECTRONICA                           => 'Liquidación factura electrónica',
            self::FACTURA_DE_COMPRA                                         => 'Factura de compra',
            self::FACTURA_DE_COMPRA_ELECTRONICA                             => 'Factura de compra electrónica',
            self::PAGO_ELECTRONICO                                          => 'Pago electrónico',
            self::GUIA_DE_DESPACHO                                          => 'Guía de despacho',
            self::GUIA_DE_DESPACHO_ELECTRONICA                              => 'Guía de despacho electrónica',
            self::NOTA_DE_DEBITO                                            => 'Nota de débito',
            self::NOTA_DE_DEBITO_ELECTRONICA                                => 'Nota de débito electrónica',
            self::NOTA_DE_CREDITO                                           => 'Nota de crédito',
            self::NOTA_DE_CREDITO_ELECTRONICA                               => 'Nota de crédito electrónica',
            self::LIQUIDACION                                               => 'Liquidación',
            self::FACTURA_DE_EXPORTACION_ELECTRONICA                        => 'Factura de exportación electrónica',
            self::NOTA_DE_DEBITO_DE_EXPORTACION_ELECTRONICA                 => 'Nota de débito de exportación electrónica',
            self::NOTA_DE_CREDITO_DE_EXPORTACION_ELECTRONICA                => 'Nota de crédito de exportación electrónica',
        ];
    }
}