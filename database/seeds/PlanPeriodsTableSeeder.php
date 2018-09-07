<?php

use Illuminate\Database\Seeder;

/**
 * [PlanPeriodsTableSeeder description]
 */
class PlanPeriodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('plan_periods')->delete();

        \DB::table('plan_periods')->insert(array (
            0 =>
            array (
                'id' => 1,
                'period' => 'Mensual',
                'period_number' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'period' => 'Bimensual',
                'period_number' => 2,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'period' => 'Trimestral',
                'period_number' => 3,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'period' => 'Cuatrimestral',
                'period_number' => 4,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'period' => 'Semestral',
                'period_number' => 6,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'period' => 'Anual',
                'period_number' => 12,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}
