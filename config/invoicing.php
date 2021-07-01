<?php 

return [
    
    /*
    |--------------------------------------------------------------------------
    |  Haulmer's OpeFactura credentials
    |--------------------------------------------------------------------------
    |
    |  The production api_key is the current PursaSangre api
    |
    */ 
    
    'haulmer' => [
        
        /**
         *  HAULMER DATA AND KEYS
         */
        'sandbox' => [
            'api_key' => '928e15a2d14d4a6292345f04960f4bd3',
            'base_uri' => 'https://dev-api.haulmer.com/v2',
            'emisor' => [
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
            ]
        ],
        
        /**
         *  PURASANGRE DATA AND KEYS
         */
        'production' => [
            'api_key' => '1784ea7f088c4d7b915e727d691a8453',
            'base_uri' => 'https://api.haulmer.com/v2',
            'emisor' => [
                'rut'                        => '76411109-5',
                "razon_social"               => "GIMNASIO CRISTOBAL IGNACIO GUTIERREZ ARROYO E.I.R.L.",
                "giro"                       => "OTRAS ACTIVIDADES DE ESPARCIMIENTO Y RECREATIVAS N.C.P.",
                "address"                    => "CAMINO ZAPALLAR S/N",
                "comuna"                     => "Curicó",
                "city"                       => "Curicó",
                "phone"                      => "97792338",
                "email"                      => "contacto@purasangrecrossfit.cl",
                "codigo_sii_sucursal"        => 79024097,
                "codigo_actividad_economica" => 932909,
            ]
        ]

    ]

];
