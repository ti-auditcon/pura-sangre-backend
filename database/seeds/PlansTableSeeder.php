<?php

use Illuminate\Database\Seeder;

/**
 * [PlansTableSeeder description]
 */
class PlansTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('plans')->delete();

        \DB::table('plans')->insert(array (
            0 =>
            array (
                'id' => 1,
                'plan' => 'Full',
                'plan_period_id' => 1,
                'class_numbers' => 0,
                'amount' => 25000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            1 =>
            array (
                'id' => 2,
                'plan' => 'Full',
                'plan_period_id' => 1,
                'class_numbers' => 0,
                'amount' => 60000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            2 =>
            array (
                'id' => 3,
                'plan' => 'Full',
                'plan_period_id' => 1,
                'class_numbers' => 0,
                'amount' => 100000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            3 =>
            array (
                'id' => 4,
                'plan' => 'Full',
                'plan_period_id' => 1,
                'class_numbers' => 0,
                'amount' => 195000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            4 =>
            array (
                'id' => 5,
                'plan' => '12 clases',
                'plan_period_id' => 1,
                'class_numbers' => 12,
                'amount' => 22000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            5 =>
            array (
                'id' => 6,
                'plan' => '12 clases',
                'plan_period_id' => 1,
                'class_numbers' => 12,
                'amount' => 60000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            6 =>
            array (
                'id' => 7,
                'plan' => '12 clases',
                'plan_period_id' => 1,
                'class_numbers' => 12,
                'amount' => 115000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            7 =>
            array (
                'id' => 8,
                'plan' => '12 clases',
                'plan_period_id' => 1,
                'class_numbers' => 12,
                'amount' => 220000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            8 =>
            array (
                'id' => 9,
                'plan' => 'Estudiante',
                'plan_period_id' => 1,
                'class_numbers' => 0,
                'amount' => 21000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            9 =>
            array (
                'id' => 10,
                'plan' => 'AM',
                'plan_period_id' => 1,
                'class_numbers' => 0,
                'amount' => 19000,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
        ));
    }
}
