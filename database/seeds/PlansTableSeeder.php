<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        DB::table('plans')->delete();

        DB::table('plans')->insert(array (
            0 =>
            array (
                'id' => 1,
                'plan' => 'Plan Prueba',
                'plan_period_id' => null,
                'has_clases' => true,
                'class_numbers' => 3,
                'amount' => 0,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            1 =>
            array (
                'id' => 2,
                'plan' => 'Plan Invitado',
                'plan_period_id' =>  6,
                'has_clases' => false,
                'class_numbers' => 0,
                'amount' => 0,
                'custom' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            2 =>
            array (
                'id' => 3,
                'plan' => 'Plan Full Mensual',
                'plan_period_id' => 1,
                'has_clases' => false,
                'class_numbers' => 30,
                'amount' => 45000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            3 =>
            array (
                'id' => 4,
                'plan' => 'Plan Full Trimestral',
                'plan_period_id' => 3,
                'has_clases' => false,
                'class_numbers' => 30,
                'amount' => 121500,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            4 =>
            array (
                'id' => 5,
                'plan' => 'Plan Full Semestral',
                'plan_period_id' => 5,
                'has_clases' => false,
                'class_numbers' => 30,
                'amount' => 229500,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            5 =>
            array (
                'id' => 6,
                'plan' => 'Plan Full Anual',
                'plan_period_id' => 6,
                'has_clases' => false,
                'class_numbers' => 30,
                'amount' => 432000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            6 =>
            array (
                'id' => 7,
                'plan' => 'Plan 12 clases Mensual',
                'plan_period_id' => 1,
                'has_clases' => true,
                'class_numbers' => 12,
                'amount' => 40000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            7 =>
            array (
                'id' => 8,
                'plan' => 'Plan 12 clases Trimestral',
                'plan_period_id' => 3,
                'has_clases' => true,
                'class_numbers' => 12,
                'amount' => 108000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            8 =>
            array (
                'id' => 9,
                'plan' => 'Plan 12 clases Semestral',
                'plan_period_id' => 5,
                'has_clases' => true,
                'class_numbers' => 12,
                'amount' => 204000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            9 =>
            array (
                'id' => 10,
                'plan' => 'Plan 12 clases Anual',
                'plan_period_id' => 6,
                'has_clases' => true,
                'class_numbers' => 12,
                'amount' => 384000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            10 =>
            array (
                'id' => 11,
                'plan' => 'Plan Estudiante',
                'plan_period_id' => 1,
                'has_clases' => false,
                'class_numbers' => 30,
                'amount' => 25990,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            11 =>
            array (
                'id' => 12,
                'plan' => 'Plan AM',
                'plan_period_id' => 1,
                'has_clases' => false,
                'class_numbers' => 30,
                'amount' => 30000,
                'custom' => 0,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
        ));
    }
}
