<?php 

namespace App\Models\Invoicing;

use App\Models\Invoicing\TaxDocument;

interface TaxIssuerInterface
{
    /**
     * Build the invoice with all the data to be issue
     *
     * @param   TaxDocument  $receipt
     * 
     * @return  array
     */
    public function get(TaxDocument $receipt);
}