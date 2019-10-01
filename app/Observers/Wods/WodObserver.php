<?php

namespace App\Observers\Wods;

use App\Models\Wods\Wod;
use App\Models\Clases\Clase;

class WodObserver
{
  public function creating(Wod $wod)
  {
    //verificar si ya existe uno

  }

    public function created(Wod $wod)
    {
        Clase::where('date', $wod->date)
             ->where('clase_type_id', $wod->clase_type_id)
             ->update(['wod_id' => $wod->id]);
    }
}
