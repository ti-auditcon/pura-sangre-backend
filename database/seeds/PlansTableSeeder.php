<?php

use Illuminate\Database\Seeder;

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
                'plan' => 'Mensual',
                'class_numbers' => 18,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            1 => 
            array (
                'id' => 2,
                'plan' => 'Trimestral',
                'class_numbers' => 12,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            2 => 
            array (
                'id' => 3,
                'plan' => 'Anual',
                'class_numbers' => 20,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            3 => 
            array (
                'id' => 4,
                'plan' => 'AM',
                'class_numbers' => 21,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            4 => 
            array (
                'id' => 5,
                'plan' => 'Estudiante',
                'class_numbers' => 18,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
        ));
        
        
    }
}