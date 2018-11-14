<?php

use Illuminate\Database\Seeder;

class PlanStatusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('plan_status')->delete();

        \DB::table('plan_status')->insert(array (
            0 =>
            array (
                'id' => 1,
                'plan_status' => 'Activo',
                'type' => 'success',
                'can_delete' => true,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            1 =>
            array (
                'id' => 2,
                'plan_status' => 'Inactivo',
                'type' => 'warning',
                'can_delete' => true,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            2 =>
            array (
                'id' => 3,
                'plan_status' => 'Precompra',
                'type' => 'primary',
                'can_delete' => true,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            3 =>
            array (
                'id' => 4,
                'plan_status' => 'Completado',
                'type' => 'secondary',
                'can_delete' => false,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            4 =>
            array (
                'id' => 5,
                'plan_status' => 'Cancelado',
                'type' => 'danger',
                'can_delete' => false,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
        ));


    }
}
