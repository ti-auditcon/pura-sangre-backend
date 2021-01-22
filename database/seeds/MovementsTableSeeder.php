<?php

use Illuminate\Database\Seeder;

class MovementsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('movements')->delete();

        \DB::table('movements')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Arch Rock',
                'description' => 'Balanceo en posición de superman.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'BS - Back Squat',
                'description' => 'Sentadilla trasera con peso detrás de la nuca.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'BP - Bench Press',
                'description' => 'Press de banca.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Box Jump',
                'description' => 'Salto al cajón.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'BRP - Burpee.',
                'description' => '',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => ' - Clean',
            'description' => 'Cargada. Consiste en llevar una carga desde el suelo hasta los hombros. Versiones adicionales incluyen: Hang Clean (HC)(Clean desde rodillas), Power Clean (PC), y Squat Clean (SC).',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'C&J - Clean and Jerk',
                'description' => 'Cargada y envión. La unión de realizar un clean y seguidamente un Jerk.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'CTB / C2B - Chest to Bar',
                'description' => 'Pecho a la barra. Dominadas en las que debes tocar el rack con el pecho. ',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'DL - Deadlift',
                'description' => 'Peso muerto. Levanta un peso del suelo hasta la extensión completa de cadera.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            9 =>
            array (
                'id' => 10,
                'name' => 'DU’s - Double Unders',
                'description' => 'Dos vueltas de la cuerda en un salto.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            10 =>
            array (
                'id' => 11,
                'name' => 'FS -Front Squat',
                'description' => 'Sentadilla Frontal con el peso por delante.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            11 =>
            array (
                'id' => 12,
                'name' => 'Hang',
            'description' => 'Colgado. En movimientos halterofilia, se utiliza cuando el movimiento (clean o snatch) comienza desde la rodilla o por encima de ella.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            12 =>
            array (
                'id' => 13,
                'name' => 'Hollow',
            'description' => 'Ejercicio funcional que consiste en estar tumbados boca arriba, con piernas y brazos extendidos (brazos hacia atrás y a los lados de la cabeza) y levantados del suelo contrayendo zona abdominal.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            13 =>
            array (
                'id' => 14,
                'name' => 'Hollow Rock',
                'description' => 'Balanceo en posición de hollow.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            14 =>
            array (
                'id' => 15,
                'name' => 'HSPU - Hand Stand Push-Up',
                'description' => 'Pino flexión ó Parada de manos con flexión.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            15 =>
            array (
                'id' => 16,
                'name' => 'K2E - Knees to Elbows',
                'description' => 'Consiste en estar colgados en la barra y llevar las rodillas a los codos.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            16 =>
            array (
                'id' => 17,
                'name' => 'MU - Muscle Up',
            'description' => 'Movimientos combinados que encadenan un balanceo con un fondo de Tríceps (puede ser en anillas o en barra).',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            17 =>
            array (
                'id' => 18,
                'name' => 'OHS - Over Head Squat',
                'description' => 'Sentadilla con peso por encima de la cabeza.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            18 =>
            array (
                'id' => 19,
                'name' => 'Pistol',
                'description' => 'Sentadilla a una pierna.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            19 =>
            array (
                'id' => 20,
                'name' => 'PP - Push Press',
                'description' => 'Press de hombros con empuje. Consiste en llevar la barra desde tus hombros hasta arriba de tu cabeza con un impulso con las piernas para empujarla hacia arriba. El movimiento termina con fuerza estricta de hombros.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            20 =>
            array (
                'id' => 21,
                'name' => 'PJ - Push Jerk',
                'description' => 'Press con Envión. Consiste en llevar la barra desde tus hombros hasta arriba de tu cabeza con un impulso con las piernas para empujarla hacia arriba y además, una ligera flexión de piernas al final del movimiento para recibir la barra desde más abajo para ayudarnos un poco más a levantarla. ',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            21 =>
            array (
                'id' => 22,
                'name' => 'PU - Pull Up or Push Up',
                'description' => 'Dominadas o flexiones.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            22 =>
            array (
                'id' => 23,
                'name' => 'Ring dips',
                'description' => 'Fondos de tríceps en anillas',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            23 =>
            array (
                'id' => 24,
                'name' => 'Rope climb',
            'description' => 'Escalar la cuerda con o sin ayuda de las piernas (Es el momento en el que todos nos sentimos bomberos).',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            24 =>
            array (
                'id' => 25,
                'name' => 'SDL - Sumo Deadlift',
                'description' => 'Peso Muerto en con las piernas en posición de sumo ',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            25 =>
            array (
                'id' => 26,
                'name' => 'SDHP - Sumo Deadlift High Pull',
                'description' => 'Consiste en realizar un peso muerto en posición de sumo, y luego de que la barra llegue a la cadera, realizar un empuje y tirar con los brazos la barra hasta la altura de la barbilla.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            26 =>
            array (
                'id' => 27,
                'name' => 'SP - Shoulder Press',
                'description' => 'Press de hombros estrictos. Consiste en llevar la barra desde tus hombros hasta arriba de tu cabeza únicamente con la fuerza estricta de tus hombros.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            27 =>
            array (
                'id' => 28,
                'name' => 'SN - Snatch',
            'description' => 'Arrancada: Consiste en levantar la barra del suelo hasta arriba de la cabeza en un solo tiempo. Versiones adicionales incluyen, Hang Snatch (HS), Power Snatch (PS), y Squat Snatch (SS).',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            28 =>
            array (
                'id' => 29,
                'name' => 'Split Jerk',
                'description' => 'Envión. La técnica es similar al Push Jerk, con la diferencia que al momento de flexionar las piernas para terminar el movimiento, las piernas se mueven una hacia delante, y otra hacia atrás.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            29 =>
            array (
                'id' => 30,
                'name' => 'SQ - Squat',
                'description' => 'Sentadilla.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            30 =>
            array (
                'id' => 31,
                'name' => 'S-ups',
                'description' => 'Sit ups.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            31 =>
            array (
                'id' => 32,
                'name' => 'SU’s - Single Unders',
                'description' => 'Una vuelta de la cuerad en un salto.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            32 =>
            array (
                'id' => 33,
                'name' => 'Superman',
                'description' => 'Ejercicio funcional que consiste en estar tumbados boca abajo, con piernas y brazos extendidos y elevados lo más que se pueda para que no toquen suelo ni cuádriceps ni brazos. ',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            33 =>
            array (
                'id' => 34,
                'name' => 'T2B - Toes to Bar',
                'description' => 'Consiste en estar colgados en la barra y toca la barra con los pies.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            34 =>
            array (
                'id' => 35,
                'name' => 'V-ups',
                'description' => 'Abdominales en V.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
            35 =>
            array (
                'id' => 36,
                'name' => 'WBS - Wall Ball Shot',
                'description' => 'Lanzamiento de bola a la pared.',
                'created_at' => '2020-06-22 11:47:59',
                'updated_at' => '2020-06-22 11:47:59',
            ),
        ));


    }
}