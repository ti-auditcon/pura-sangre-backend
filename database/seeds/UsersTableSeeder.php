<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert(array (
            0 => array (
                'id' => 1,
                'rut' => 5163619,
                'first_name' => 'Admin',
                'last_name' => 'Pura Sangre',
                'email' => 'contacto@purasangrecrossfit.cl',
                'password' => bcrypt('123123'),
                'avatar' => 'http://adminps.test/storage/users/u (17).jpg',
                'phone' => 59154977,
                'birthdate' => '2000-08-18',
                'gender' => 'female',
                'address' => '6611 Zieme Vista Suite 484',
                'status_user_id' => 3,
                'remember_token' => 'E15ZZ6woBk',
                'created_at' => '2018-11-23 12:18:27',
                'updated_at' => '2018-11-23 12:18:27',
                'deleted_at' => NULL,
            ),
        ));
    }
}
