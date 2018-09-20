<?php

namespace App\Observers\Clases;

use App\Models\Clases\Block;

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
      //
    }

    /**
     * Handle the block' "created" event.
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
