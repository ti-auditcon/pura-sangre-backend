<?php

use Illuminate\Database\Seeder;

class ReservationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return  void
     */
    public function run()
    {
        \DB::table('reservations')->delete();
        
        \DB::table('reservations')->insert(array (
            0 => 
            array (
                'id' => 8,
                'plan_user_id' => 1093,
                'clase_id' => 398,
                'reservation_status_id' => 2,
                'user_id' => 1,
                'by_god' => NULL,
                'details' => NULL,
                'created_at' => '2018-12-06 11:33:19',
                'updated_at' => '2018-12-06 11:33:19',
            ),
            1 => 
            array (
                'id' => 20,
                'plan_user_id' => 748,
                'clase_id' => 330,
                'reservation_status_id' => 1,
                'user_id' => 1,
                'by_god' => NULL,
                'details' => NULL,
                'created_at' => '2018-12-06 11:33:19',
                'updated_at' => '2018-12-06 11:33:19',
            ),
            2 => 
            array (
                'id' => 24,
                'plan_user_id' => 1280,
                'clase_id' => 592,
                'reservation_status_id' => 1,
                'user_id' => 1,
                'by_god' => NULL,
                'details' => NULL,
                'created_at' => '2018-12-06 11:33:19',
                'updated_at' => '2018-12-06 11:33:19',
            ),
            3 => 
            array (
                'id' => 26,
                'plan_user_id' => 775,
                'clase_id' => 195,
                'reservation_status_id' => 1,
                'user_id' => 1,
                'by_god' => NULL,
                'details' => NULL,
                'created_at' => '2018-12-06 11:33:19',
                'updated_at' => '2018-12-06 11:33:19',
            ),
            4 => 
            array (
                'id' => 29,
                'plan_user_id' => 78,
                'clase_id' => 188,
                'reservation_status_id' => 2,
                'user_id' => 1,
                'by_god' => NULL,
                'details' => NULL,
                'created_at' => '2018-12-06 11:33:19',
                'updated_at' => '2018-12-06 11:33:19',
            ),
       ));
    }
}