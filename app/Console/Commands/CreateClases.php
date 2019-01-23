<?php

namespace App\Console\Commands;

use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CreateClases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:clases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make one week of classes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Block::all() as $block) {
            $clases = $block->clases->sortByDesc('date');
            if (count($clases) > 1) {
                $clase =  $block->clases->sortByDesc('date')->first();
                $fecha = Carbon::parse($clase->date)->addWeek();
                if($block->date == null){
                    Clase::create([
                       'block_id' => $block->id,
                       'date' => $fecha,
                       'start_at' => $block->start,
                       'finish_at' => $block->end,
                       'profesor_id' => $block->profesor_id,
                       'clase_type_id' => $block->clase_type_id,
                       'quota' => $block->quota,
                    ]);
                }
            }
        }    
    }
}
