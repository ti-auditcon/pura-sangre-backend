<?php

use Illuminate\Database\Seeder;

class PlanUserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('plan_user')->delete();

        \DB::table('plan_user')->insert(array (
            0 =>
            array (
                'id' => 5000,
                'start_date' => '2018-11-19',
                'finish_date' => '2018-12-18',
                'counter' => 2,
                'plan_status_id' => 1,
                'plan_id' => 5,
                'user_id' => 1, 
                'created_at' => '2018-10-19 10:53:25',
                'updated_at' => '2018-12-18 10:53:26',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 5001,
                'start_date' => '2018-11-18',
                'finish_date' => '2018-12-18',
                'counter' => 2,
                'plan_status_id' => 1,
                'plan_id' => 5,
                'user_id' => 2,
                'created_at' => '2018-11-19 12:06:42',
                'updated_at' => '2018-10-19 12:06:43',
                'deleted_at' => NULL,
            ),
            2 =>
            array (
                'id' => 5002,
                'start_date' => '2018-11-18',
                'finish_date' => '2018-12-17',
                'counter' => 2,
                'plan_status_id' => 1,
                'plan_id' => 5,
                'user_id' => 3,
                'created_at' => '2018-10-19 12:06:42',
                'updated_at' => '2018-10-19 12:06:43',
                'deleted_at' => NULL,
            ),
        ));
    }
}
