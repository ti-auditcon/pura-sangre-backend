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
                'plan' => 'Prueba',
                'plan_period_id' => null,
                'class_numbers' => 3,
                'amount' => 0,
                'custom' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            1 =>
            array (
                'id' => 2,
                'plan' => 'Invitado',
                'plan_period_id' =>  6,
                'class_numbers' => 0,
                'amount' => 0,
                'custom' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            2 =>
            array (
                'id' => 3,
                'plan' => 'Full Mensual',
                'plan_period_id' => 1,
                'class_numbers' => 30,
                'amount' => 25000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            3 =>
            array (
                'id' => 4,
                'plan' => 'Full Trimestral',
                'plan_period_id' => 3,
                'class_numbers' => 30,
                'amount' => 60000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            4 =>
            array (
                'id' => 5,
                'plan' => 'Full Semestral',
                'plan_period_id' => 5,
                'class_numbers' => 30,
                'amount' => 100000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            5 =>
            array (
                'id' => 6,
                'plan' => 'Full Anual',
                'plan_period_id' => 6,
                'class_numbers' => 30,
                'amount' => 195000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            6 =>
            array (
                'id' => 7,
                'plan' => '12 clases Mensual',
                'plan_period_id' => 1,
                'class_numbers' => 12,
                'amount' => 22000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            7 =>
            array (
                'id' => 8,
                'plan' => '12 clases Trimestral',
                'plan_period_id' => 3,
                'class_numbers' => 12,
                'amount' => 60000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            8 =>
            array (
                'id' => 9,
                'plan' => '12 clases Semestral',
                'plan_period_id' => 5,
                'class_numbers' => 12,
                'amount' => 115000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            9 =>
            array (
                'id' => 10,
                'plan' => '12 clases Anual',
                'plan_period_id' => 6,
                'class_numbers' => 12,
                'amount' => 220000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            10 =>
            array (
                'id' => 11,
                'plan' => 'Estudiante',
                'plan_period_id' => 1,
                'class_numbers' => 30,
                'amount' => 21000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            11 =>
            array (
                'id' => 12,
                'plan' => 'AM',
                'plan_period_id' => 1,
                'class_numbers' => 30,
                'amount' => 19000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
        ));
    }
}
