<?php

use Illuminate\Database\Seeder;

class ExercisesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exercises')->delete();
        
        \DB::table('exercises')->insert(array (
            0 => 
            array (
                'id' => 1,
                'exercise' => 'Bear crawl',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'exercise' => 'Broad Jump',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'exercise' => 'Burpess',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'exercise' => 'Crab walk',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'exercise' => 'Desplazamiento lateral en posición de plancha',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'exercise' => 'Dolphin push ups',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'exercise' => 'Elevación de pelvis en suelo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'exercise' => 'Elevaciones de pelvis en suelo o puentes',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'exercise' => 'Elevaciones de piernas extendidas en suelo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'exercise' => 'Elevaciones de talones de pie',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'exercise' => 'Elevaciones de tronco en el suelo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'exercise' => 'Flexiones de brazos tradicionales',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'exercise' => 'Flexiones diamante',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'exercise' => 'Flexiones pino o en V invertida',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'exercise' => 'Flexiones verticales o handstand pushups',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'exercise' => 'Flutter kicks o aleteo de piernas',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'exercise' => 'Mountain climbers',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'exercise' => 'Patadas de burro o extensiones de cadera en suelo.',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'exercise' => 'Pistol squat o sentadillas a una pierna',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'exercise' => 'Plank lateral',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'exercise' => 'Plank o estabilización horizontal',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'exercise' => 'Saltamontes o grasshoppers',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'exercise' => 'Saltos de rana',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'exercise' => 'Saltos del patinador',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'exercise' => 'Scissor lunge o zancadas con salto',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'exercise' => 'Sentadillas estilo sumo o con las piernas separadas',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'exercise' => 'Sentadillas frontales',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'exercise' => 'Sprawl',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'exercise' => 'Superman o extensión lumbar en suelo',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'exercise' => 'Zancadas o lunge',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}