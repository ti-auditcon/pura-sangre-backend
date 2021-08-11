<?php

namespace App\Models\Misc;

class Country
{
    public static function list()
    {
        return [
            ['prefix' => '+54',  'avatar' => public_path('img/countries/arg.png'), 'currency_symbol' => '$', 'code' => 'AR', 'name' => 'Argentina'],
            ['prefix' => '+591', 'avatar' => public_path('img/countries/bol.png'), 'currency_symbol' => 'b$', 'code' => 'BO', 'name' => 'Bolivia'],
            ['prefix' => '+55',  'avatar' => public_path('img/countries/bra.png'), 'currency_symbol' => 'R$', 'code' => 'BR', 'name' => 'Brasil'],
            ['prefix' => '+56',  'avatar' => public_path('img/countries/chi.png'), 'currency_symbol' => '$', 'code' => 'CL', 'name' => 'Chile'],
            ['prefix' => '+57',  'avatar' => public_path('img/countries/col.png'), 'currency_symbol' => '$', 'code' => 'CO', 'name' => 'Colombia'],
            ['prefix' => '+506', 'avatar' => public_path('img/countries/cri.png'), 'currency_symbol' => '₡', 'code' => 'CR', 'name' => 'Costa Rica'],
            ['prefix' => '+53',  'avatar' => public_path('img/countries/cub.png'), 'currency_symbol' => 'c', 'code' => 'CU', 'name' => 'Cuba'],
            ['prefix' => '+593', 'avatar' => public_path('img/countries/ecu.png'), 'currency_symbol' => '$', 'code' => 'EC', 'name' => 'Ecuador'],
            ['prefix' => '+503', 'avatar' => public_path('img/countries/slv.png'), 'currency_symbol' => '$', 'code' => 'SV', 'name' => 'El Salvador'],
            ['prefix' => '+502', 'avatar' => public_path('img/countries/gtm.png'), 'currency_symbol' => 'Q', 'code' => 'GT', 'name' => 'Guatemala'],
            ['prefix' => '+504', 'avatar' => public_path('img/countries/hnd.png'), 'currency_symbol' => 'L', 'code' => 'HN', 'name' => 'Honduras'],
            ['prefix' => '+52',  'avatar' => public_path('img/countries/mex.png'), 'currency_symbol' => '$', 'code' => 'MX', 'name' => 'México'],
            ['prefix' => '+595', 'avatar' => public_path('img/countries/prg.png'), 'currency_symbol' => '₲', 'code' => 'PY', 'name' => 'Paraguay'],
            ['prefix' => '+51',  'avatar' => public_path('img/countries/per.png'), 'currency_symbol' => 'S/ ', 'code' => 'PE', 'name' => 'Perú'],
            ['prefix' => '+507', 'avatar' => public_path('img/countries/pnm.png'), 'currency_symbol' => 'B/ ', 'code' => 'PA', 'name' => 'Panamá'],
            ['prefix' => '+505', 'avatar' => public_path('img/countries/nic.png'), 'currency_symbol' => 'C$', 'code' => 'NI', 'name' => 'Nicaragua'],
            ['prefix' => '+598', 'avatar' => public_path('img/countries/ury.png'), 'currency_symbol' => '$', 'code' => 'UY', 'name' => 'Uruguay'],
            ['prefix' => '+58',  'avatar' => public_path('img/countries/ven.png'), 'currency_symbol' => 'Bs', 'code' => 'VE', 'name' => 'Venezuela']
        ];
    }

    /**
     *  Order all the codes by prefixes and return it as array
     *
     *  @return  array
     */
    public static function orderedByPrefix()
    {
        return array_sort(self::list(), function ($key) {
            return $key['prefix'];
        });
    }

    /**
     *  @param   string  by default gonna be Chile $
     *
     *  @return  string  example: $
     */
    public static function getCurrencySymbolByCode($code = 'CL')
    {
        $country = array_values(array_filter(self::list(), function($country) use ($code) {
            return $country['code'] === $code;
        }));

        return $country[0]['currency_symbol'];
    }

    /**
     *  [getCountryBycode description]
     *
     *  @param   [type]  $code  [$code description]
     *
     *  @return  array          [return description]
     */
    public static function getCountryBycode($code): array
    {
        $country = array_values(array_filter(self::list(), function($country) use ($code) {
            return $country['code'] === $code;
        }));

        return $country[0];
    }
}


