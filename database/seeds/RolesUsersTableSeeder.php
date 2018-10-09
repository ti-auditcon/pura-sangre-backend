<?php

use Illuminate\Database\Seeder;

class RolesUsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles_users')->delete();
        
        \DB::table('roles_users')->insert(array (
            
        ));
        
        
    }
}