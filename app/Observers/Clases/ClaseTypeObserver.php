<?php

namespace App\Observers\Clases;

use App\Models\Clases\ClaseType;

class ClaseTypeObserver
{
    /**
     * Handle the clase type "deleted" event.
     *
     * @param  \App\Models\Clases\ClaseType  $claseType
     * @return void
     */
    public function deleted(ClaseType $clases_type)
    {
        // $clases_type->blocks()->delete();
    }
}
