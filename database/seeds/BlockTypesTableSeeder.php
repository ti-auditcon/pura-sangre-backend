<?php

use Illuminate\Database\Seeder;

class BlockTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('block_types')->delete();
        
        \DB::table('block_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'block_type' => 'Crossfit',
                'max_quota' => 25,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}