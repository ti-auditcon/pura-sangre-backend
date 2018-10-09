<?php

use Illuminate\Database\Seeder;

class PaymentTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_types')->delete();
        
        \DB::table('payment_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'payment_type' => 'Efectivo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'payment_type' => 'Transferencia',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'payment_type' => 'Cheque',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'payment_type' => 'Débito',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'payment_type' => 'Crédito',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}