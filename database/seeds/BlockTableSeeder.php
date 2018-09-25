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
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'1']);
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'2']);
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'3']);
      Block::create(['start'=>'10:00','end'=>'11:00','dow'=>'4']);
    }
}
