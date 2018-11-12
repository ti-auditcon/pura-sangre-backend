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
                'reservation_status' => 'Confirmada',
                'created_at' => '2018-10-25 12:29:47',
                'updated_at' => '2018-10-25 12:29:47',
            ),
            2 =>
            array (
                'id' => 3,
                'reservation_status' => 'Consumida',
                'created_at' => '2018-10-25 12:29:47',
                'updated_at' => '2018-10-25 12:29:47',
            ),
            3 =>
            array (
                'id' => 4,
                'reservation_status' => 'Perdida',
                'created_at' => '2018-10-25 12:29:47',
                'updated_at' => '2018-10-25 12:29:47',
            ),
        ));


    }
}
