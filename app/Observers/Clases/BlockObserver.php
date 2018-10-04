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
      //creamos las 12 clases siguientes por cada bloque
      if($block->date == null){
        $first_date = now()->startOfWeek()->addDays($block->dow[0]-1);
        // dd($first_date );
        $date = $first_date;
        for ($i=0; $i < 12; $i++) {
          Clase::create([
            'block_id' => $block->id,
            'date' => $first_date,
            'start_at' => $block->start,
            'finish_at' => $block->end,
            'profesor_id' => $block->profesor_id,
        ]);
          $date->addWeek();
        }
      }
      else {
        Clase::create([
          'block_id' => $block->id,
          'date' => $block->date,
          'start_at' => $block->start,
          'finish_at' => $block->end,
          'profesor_id' => 1,
      ]);
      }

      //si es unico
      //
      //
    }

    /**
     * Handle the block' "creating" event.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return void
     */
    public function creating(Block $block)
    {
      //que noi se pueda guardar si el horario topa o no?
    }

    /**
     * Handle the block' "updated" event.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return void
     */
    public function updated(Block $block)
    {
        //
    }

    /**
     * Handle the block' "updating" event.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return void
     */
    public function updating(Block $block)
    {
      //que noi se pueda guardar si el horario topa o no?
    }

    /**
     * Handle the block' "deleted" event.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return void
     */
    public function deleted(Block $block)
    {
        //
    }

    /**
     * Handle the block' "restored" event.
     *
     * @param  \App\Models\Clases\Block  $block
     * @return void
     */
    public function restored(Block $block)
    {
        //
    }

    /**
     * Handle the block' "force deleted" event.
     *
     * @param  \App\'App\Models\Clases\Block  $block
     * @return void
     */
    public function forceDeleted(Block $block)
    {
        //
    }
}
