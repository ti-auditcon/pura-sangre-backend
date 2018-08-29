<?php

use Illuminate\Database\Seeder;

class StagesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('stages')->delete();
        
        \DB::table('stages')->insert(array (
            0 => 
            array (
                'id' => 1,
                'stage' => 'Warm-Up',
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            1 => 
            array (
                'id' => 2,
                'stage' => 'SKILL',
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            2 => 
            array (
                'id' => 3,
                'stage' => 'WOD',
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
        ));
        
        
    }
}