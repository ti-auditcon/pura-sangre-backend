<?php 

namespace App\Models\Invoicing\Haulmer;

class HaulmerError 
{
    public $errors = [];

    public function manage($error)
    {
        if (isset($error['error']) && isset($error['error']['details'])) {
            return $error['error']['details'][0]['field'] . ' '  . $error['error']['details'][0]['issue'];
        }

        return null;
    }
}
