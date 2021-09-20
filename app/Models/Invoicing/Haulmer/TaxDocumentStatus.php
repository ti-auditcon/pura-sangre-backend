<?php 

namespace App\Models\Invoicing\Haulmer;

class TaxDocumentStatus
{
    /** Status of a tax docuemnt by Haulmer */
    const NO_STATUS = 0;
    
    /** Status of a tax docuemnt by Haulmer */
    const ACCEPTED = 1;
    
    /** Status of a tax docuemnt by Haulmer */
    const PENDING = 2;
    
    /** Status of a tax docuemnt by Haulmer */
    const REJECTED = 3;
    
    /** Status of a tax docuemnt by Haulmer */
    const ACCEPTED_WITH_OBJECTIONS = 4;

    /**
     *  The status names are define by Haulmer Openfactura documentation
     *  
     *  @url  https://docsapi-openfactura.haulmer.com/#02d546b8-e424-4533-9a5d-4c53b8912798
     *  
     *  Can have the next statuses:
     *    - Aceptado : Documento Emitido correctamente en el SII.
     *    - Pendiente : Esperando Respuesta del SII.
     *    - Rechazado : Documento Rechazado por el SII
     *    - Aceptado con Reparo : Documento válido para el SII, pero con reparos.
     *
     *  @return  array
     */
    public static function list()
    {
        return [
            self::ACCEPTED                 => 'Aceptado',
            self::PENDING                  => 'Pendiente',
            self::REJECTED                 => 'Rechazado',
            self::ACCEPTED_WITH_OBJECTIONS => 'Aceptado con Reparo',
        ];
    }

    /**
     *  Return all the status of tax documents that apply to be cancel
     *
     *  @return  array
     */
    public static function cancellableList()
    {
        return [
            self::ACCEPTED                 => 'Aceptado',
            self::ACCEPTED_WITH_OBJECTIONS => 'Aceptado con Reparo',
        ];
    }
}

