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
                'plan' => 'Prueba',
                'plan_period_id' => NULL,
                'class_numbers' => 3,
                'amount' => 0,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            1 => 
            array (
                'id' => 2,
                'plan' => 'Invitado',
                'plan_period_id' => 6,
                'class_numbers' => 0,
                'amount' => 0,
                'custom' => 1,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            2 => 
            array (
                'id' => 3,
                'plan' => 'Full Mensual',
                'plan_period_id' => 1,
                'class_numbers' => 31,
                'amount' => 45000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2019-08-07 09:53:59',
            ),
            3 => 
            array (
                'id' => 4,
                'plan' => 'Full Trimestral',
                'plan_period_id' => 3,
                'class_numbers' => 31,
                'amount' => 121500,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2019-08-07 09:57:00',
            ),
            4 => 
            array (
                'id' => 5,
                'plan' => 'Full Semestral',
                'plan_period_id' => 5,
                'class_numbers' => 31,
                'amount' => 229500,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2019-08-07 09:56:34',
            ),
            5 => 
            array (
                'id' => 6,
                'plan' => 'Full Anual',
                'plan_period_id' => 6,
                'class_numbers' => 31,
                'amount' => 432000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2019-08-07 09:55:45',
            ),
            6 => 
            array (
                'id' => 7,
                'plan' => '12 clases Mensual',
                'plan_period_id' => 1,
                'class_numbers' => 12,
                'amount' => 40000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            7 => 
            array (
                'id' => 8,
                'plan' => '12 clases Trimestral',
                'plan_period_id' => 3,
                'class_numbers' => 12,
                'amount' => 108000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            8 => 
            array (
                'id' => 9,
                'plan' => '12 clases Semestral',
                'plan_period_id' => 5,
                'class_numbers' => 12,
                'amount' => 204000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            9 => 
            array (
                'id' => 10,
                'plan' => '12 clases Anual',
                'plan_period_id' => 6,
                'class_numbers' => 12,
                'amount' => 384000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2018-08-28 20:59:34',
            ),
            10 => 
            array (
                'id' => 11,
                'plan' => 'Estudiante',
                'plan_period_id' => 1,
                'class_numbers' => 31,
                'amount' => 25990,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2019-08-07 09:54:41',
            ),
            11 => 
            array (
                'id' => 12,
                'plan' => 'AM',
                'plan_period_id' => 1,
                'class_numbers' => 31,
                'amount' => 30000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2018-08-28 20:59:34',
                'updated_at' => '2019-08-07 09:54:28',
            ),
            12 => 
            array (
                'id' => 13,
                'plan' => '8 Sesiones',
                'plan_period_id' => 1,
                'class_numbers' => 8,
                'amount' => 35000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-03-04 10:42:49',
                'updated_at' => '2019-09-06 09:22:15',
            ),
            13 => 
            array (
                'id' => 14,
                'plan' => 'prueba test',
                'plan_period_id' => 1,
                'class_numbers' => 20,
                'amount' => 60000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-03-05 12:12:32',
                'updated_at' => '2019-03-05 12:12:32',
            ),
            14 => 
            array (
                'id' => 15,
                'plan' => 'canje dia los enamoraos',
                'plan_period_id' => 1,
                'class_numbers' => 12,
                'amount' => 34000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-04-16 13:03:41',
                'updated_at' => '2019-04-16 13:03:57',
            ),
            15 => 
            array (
                'id' => 16,
                'plan' => '4 Sesiones',
                'plan_period_id' => 1,
                'class_numbers' => 4,
                'amount' => 20000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-06-12 17:37:42',
                'updated_at' => '2019-06-12 17:39:32',
            ),
            16 => 
            array (
                'id' => 17,
                'plan' => 'Convenio Copefrut',
                'plan_period_id' => 1,
                'class_numbers' => 31,
                'amount' => 30000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-06-17 12:25:04',
                'updated_at' => '2019-08-07 09:55:10',
            ),
            17 => 
            array (
                'id' => 18,
                'plan' => '8 Sesiones Full',
                'plan_period_id' => 1,
                'class_numbers' => 8,
                'amount' => 35000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-06-25 17:18:33',
                'updated_at' => '2019-06-25 18:15:32',
            ),
            18 => 
            array (
                'id' => 19,
                'plan' => '8 Sesiones Full Trimestral',
                'plan_period_id' => 3,
                'class_numbers' => 8,
                'amount' => 95000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-06-25 17:29:55',
                'updated_at' => '2019-06-25 18:16:04',
            ),
            19 => 
            array (
                'id' => 20,
                'plan' => 'Convenio PDI',
                'plan_period_id' => 1,
                'class_numbers' => 31,
                'amount' => 35000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-06-26 12:13:11',
                'updated_at' => '2019-08-07 09:55:29',
            ),
            20 => 
            array (
                'id' => 21,
                'plan' => 'PLAN FULL FINDE',
                'plan_period_id' => 1,
                'class_numbers' => 12,
                'amount' => 26000,
                'custom' => 0,
                'daily_clases' => 1,
                'created_at' => '2019-08-07 09:52:13',
                'updated_at' => '2019-08-07 10:17:02',
            ),
            21 => 
            array (
                'id' => 22,
                'plan' => 'Plan Extra Full',
                'plan_period_id' => 1,
                'class_numbers' => 31,
                'amount' => 60000,
                'custom' => 0,
                'daily_clases' => 2,
                'created_at' => '2019-08-07 09:53:14',
                'updated_at' => '2019-09-02 18:30:49',
            ),
            22 => 
            array (
                'id' => 24,
                'plan' => 'Plan Extra Full Trimestral',
                'plan_period_id' => 3,
                'class_numbers' => 31,
                'amount' => 162000,
                'custom' => 0,
                'daily_clases' => 2,
                'created_at' => '2019-08-20 09:48:14',
                'updated_at' => '2019-09-02 18:32:33',
            ),
            23 => 
            array (
                'id' => 25,
                'plan' => 'Plan Extra Full Semestral',
                'plan_period_id' => 5,
                'class_numbers' => 31,
                'amount' => 306000,
                'custom' => 0,
                'daily_clases' => 2,
                'created_at' => '2019-08-20 09:49:32',
                'updated_at' => '2019-09-02 18:31:31',
            ),
            24 => 
            array (
                'id' => 26,
                'plan' => 'Plan Extra Full Anual',
                'plan_period_id' => 6,
                'class_numbers' => 31,
                'amount' => 576000,
                'custom' => 0,
                'daily_clases' => 2,
                'created_at' => '2019-08-20 09:50:15',
                'updated_at' => '2019-09-02 18:31:13',
            ),
        ));
        
        
    }
}