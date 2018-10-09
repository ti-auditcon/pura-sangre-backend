<?php

use Illuminate\Database\Seeder;

class StageTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('stage_types')->delete();
        
        \DB::table('stage_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'stage_type' => 'WARM-UP',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'stage_type' => 'SKILL',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'stage_type' => 'WOD',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}