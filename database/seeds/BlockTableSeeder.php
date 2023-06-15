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
      Block::create(['start'=>'07:00', 'end'=>'08:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'07:00', 'end'=>'08:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'07:00', 'end'=>'08:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'07:00', 'end'=>'08:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'07:00', 'end'=>'08:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);

      Block::create(['start'=>'08:00', 'end'=>'09:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'08:00', 'end'=>'09:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'08:00', 'end'=>'09:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'08:00', 'end'=>'09:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'08:00', 'end'=>'09:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);

      Block::create(['start'=>'09:00', 'end'=>'10:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'09:00', 'end'=>'10:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'09:00', 'end'=>'10:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'09:00', 'end'=>'10:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'09:00', 'end'=>'10:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);

      Block::create(['start'=>'10:00', 'end'=>'11:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'10:00', 'end'=>'11:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'10:00', 'end'=>'11:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'10:00', 'end'=>'11:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'10:00', 'end'=>'11:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
     
      Block::create(['start'=>'12:00', 'end'=>'13:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'12:00', 'end'=>'13:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'12:00', 'end'=>'13:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'12:00', 'end'=>'13:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'12:00', 'end'=>'13:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);

      Block::create(['start'=>'17:00', 'end'=>'18:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'17:00', 'end'=>'18:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'17:00', 'end'=>'18:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'17:00', 'end'=>'18:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'17:00', 'end'=>'18:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);

      Block::create(['start'=>'18:00', 'end'=>'19:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'18:00', 'end'=>'19:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'18:00', 'end'=>'19:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'18:00', 'end'=>'19:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'18:00', 'end'=>'19:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);

      Block::create(['start'=>'19:00', 'end'=>'20:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'19:00', 'end'=>'20:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'19:00', 'end'=>'20:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'19:00', 'end'=>'20:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'19:00', 'end'=>'20:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);

      Block::create(['start'=>'20:00', 'end'=>'21:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'20:00', 'end'=>'21:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'20:00', 'end'=>'21:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'20:00', 'end'=>'21:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'20:00', 'end'=>'21:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);

      Block::create(['start'=>'21:00', 'end'=>'22:00', 'dow'=>'1', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'21:00', 'end'=>'22:00', 'dow'=>'2', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'21:00', 'end'=>'22:00', 'dow'=>'3', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'21:00', 'end'=>'22:00', 'dow'=>'4', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
      Block::create(['start'=>'21:00', 'end'=>'22:00', 'dow'=>'5', 'coach_id' => 1, 'quota' => 22, 'clase_type_id' => 1]);
    }
}
