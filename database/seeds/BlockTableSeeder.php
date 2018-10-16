<?php

use Illuminate\Database\Seeder;
use App\Models\Clases\Block;

class BlockTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      \DB::table('clase_types')->insert(array (
          0 =>
          array (
              'id' => 1,
              'clase_type' => 'Crossfit',
              'clase_color' => '#27b0b6',
              'created_at' => NULL,
              'updated_at' => NULL,
          ),
          1 =>
          array (
              'id' => 2,
              'clase_type' => 'Cardio',
              'clase_color' => '#27b066',
              'created_at' => NULL,
              'updated_at' => NULL,
          ),
        ));
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'1', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'2', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'3', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'4', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'5', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'11:00','end'=>'12:00','dow'=>'1', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'11:00','end'=>'12:00','dow'=>'2', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'11:00','end'=>'12:00','dow'=>'3', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'11:00','end'=>'12:00','dow'=>'4', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'11:00','end'=>'12:00','dow'=>'5', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'15:00','end'=>'16:00','dow'=>'1', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'15:00','end'=>'16:00','dow'=>'2', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'15:00','end'=>'16:00','dow'=>'3', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'15:00','end'=>'16:00','dow'=>'4', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'15:00','end'=>'16:00','dow'=>'5', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'16:00','end'=>'17:00','dow'=>'1', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'16:00','end'=>'17:00','dow'=>'2', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'16:00','end'=>'17:00','dow'=>'3', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'16:00','end'=>'17:00','dow'=>'4', 'profesor_id' => 1, 'clase_type_id' => 1]);
      Block::create(['start'=>'16:00','end'=>'17:00','dow'=>'5', 'profesor_id' => 1, 'clase_type_id' => 1]);
    }
}
