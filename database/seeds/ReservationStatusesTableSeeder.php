<?php

use Illuminate\Database\Seeder;

class ReservationStatusesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('reservation_statuses')->delete();
        
        \DB::table('reservation_statuses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'reservation_status' => 'Pendiente',
                'created_at' => '2018-10-25 12:29:47',
                'updated_at' => '2018-10-25 12:29:47',
            ),
            1 => 
            array (
                'id' => 2,
                'reservation_status' => 'Confirmado',
                'created_at' => '2018-10-25 12:29:47',
                'updated_at' => '2018-10-25 12:29:47',
            ),
        ));
        
        
    }
}