<?php

namespace App\Observers\Clases;

use App\Models\Clases\Block;
use App\Models\Clases\Clase;

class BlockObserver
{
    /**
     * Handle the block' "created" event.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return void
     */
    public function created(Block $block)
    {
        //Creamos las 12 clases siguientes por cada bloque
        if (is_null($block->date)) {
            $first_date = now()->startOfWeek()->addDays($block->dow[0] - 1);
            // dd($first_date);
            $date = $first_date;
            for ($i = 0; $i < 12; $i++) {
                Clase::create([
                    'block_id' => $block->id,
                    'date' => $date,
                    'start_at' => $block->start,
                    'finish_at' => $block->end,
                    'profesor_id' => $block->profesor_id,
                    'clase_type_id' => $block->clase_type_id,
                    'quota' => $block->quota,
                ]);
                $date->addWeek();
            }
        } else {
            Clase::create([
                'block_id' => $block->id,
                'date' => $block->date,
                'start_at' => $block->start,
                'finish_at' => $block->end,
                'profesor_id' => 1,
                'clase_type_id' => $block->clase_type_id,
                'quota' => $block->quota,
            ]);
        }
    }

    /**
     * Update quota from block to clases
     * 
     * @param  App\Models\Blocks\Block  $block
     * 
     * @return  void
     */
    public function updated(Block $block)
    {
        $block->clases()->each(function ($clase) {
            if ($clase->start >= now()) {
                $clase->update([ 'quota' => $clase->block->quota ]);
            }
        });
    }

    /**
     * Handle the block' "deleted" event.
     *
     * @param  \App\Models\Clases\Block  $block
     * 
     * @return void
     */
    public function deleted(Block $block)
    {   
        $block->clases()->each(function ($clase) {
            $clase->delete();
        });
    }
}
