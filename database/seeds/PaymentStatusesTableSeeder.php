<?php

use Illuminate\Database\Seeder;

class PaymentStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_statuses')->delete();
        
        \DB::table('payment_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'payment_status' => 'Pagado',
                'created_at' => '2018-09-20 16:03:11',
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'payment_status' => 'Pendiente',
                'created_at' => '2018-09-20 16:03:11',
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'payment_status' => 'Anulado',
                'created_at' => '2018-09-20 16:03:11',
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'payment_status' => 'Otro',
                'created_at' => '2018-09-20 16:03:11',
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}