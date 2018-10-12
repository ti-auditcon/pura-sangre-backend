<?php

namespace App\Observers\Wods;

use Session;
use Carbon\Carbon;
use App\Models\Clases\Clase;
use App\Models\Wods\Stage;
use App\Models\Clases\ClaseStage;

class StageObserver
{
    /**
     * Handle the stage "created" event.
     *
     * @param  \App\Models\Exercises\Stage  $stage
     * @return void
     */
    public function created(Stage $stage)
    {
        // $ssn_date = Carbon::parse(Session::get('date'))->format('Y-m-d');
        // $clases = Clase::where('date', $ssn_date)->get();
        // foreach ($clases as $clase) {
        //     ClaseStage::create([
        //         'clase_id' => $clase->id,
        //         'stage_id' => $stage->id]);
        // }
    }

    /**
     * Handle the stage "updated" event.
     *
     * @param  \App\Models\Exercises\Stage  $stage
     * @return void
     */
    public function updated(Stage $stage)
    {
        //
    }

    /**
     * Handle the stage "deleted" event.
     *
     * @param  \App\Models\Exercises\Stage  $stage
     * @return void
     */
    public function deleted(Stage $stage)
    {
        //
    }

    /**
     * Handle the stage "restored" event.
     *
     * @param  \App\Models\Exercises\Stage  $stage
     * @return void
     */
    public function restored(Stage $stage)
    {
        //
    }

    /**
     * Handle the stage "force deleted" event.
     *
     * @param  \App\Models\Exercises\Stage  $stage
     * @return void
     */
    public function forceDeleted(Stage $stage)
    {
        //
    }
}
