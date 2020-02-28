<?php

use Illuminate\Database\Seeder;

class ClaseTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('clase_types')->delete();

        \DB::table('clase_types')->insert(array (
            0 =>
            array (
                'id' => 1,
                'clase_type' => 'CrossFit',
                'clase_color' => '#DCDCDC',
                'icon' => 'crossfit.svg',
                'icon_white' => 'crossfit-white.svg',
                'active' => 1,
                'created_at' => '2018-12-17 17:10:54',
                'updated_at' => '2018-12-17 17:10:56',
            ),
            1 =>
            array (
                'id' => 3,
                'clase_type' => 'Frenetic',
                'clase_color' => '#DCDCDC',
                'icon' => 'frenetik.svg',
                'icon_white' => 'frenetik-white.svg',
                'active' => 2,
                'created_at' => '2019-07-24 10:11:29',
                'updated_at' => '2019-07-24 10:11:29',
            ),
        ));


    }
}