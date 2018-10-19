<?php

use Illuminate\Database\Seeder;

class StatusUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('status_users')->delete();

        \DB::table('status_users')->insert(array (
            0 =>
            array (
                'id' => 1,
                'status_user' => 'Activo',
                'created_at' => '2018-08-28 20:59:28',
                'updated_at' => '2018-08-28 20:59:28',
            ),
            1 =>
            array (
                'id' => 2,
                'status_user' => 'Inactivo',
                'created_at' => '2018-08-28 20:59:28',
                'updated_at' => '2018-08-28 20:59:28',
            ),
            2 =>
            array (
                'id' => 3,
                'status_user' => 'Prueba',
                'created_at' => '2018-08-28 20:59:28',
                'updated_at' => '2018-08-28 20:59:28',
            ),
        ));


    }
}
