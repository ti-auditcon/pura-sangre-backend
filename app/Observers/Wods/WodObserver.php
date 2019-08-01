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
      // $ssn_date = Carbon::parse(Session::get('date'))->format('Y-m-d');
      // $clases = Clase::where('date', $ssn_date)->get();
      // foreach ($clases as $clase) {
      //     ClaseStage::create([
      //         'clase_id' => $clase->id,
      //         'stage_id' => $stage->id]);
      // }
      Clase::where('date', $wod->date)->update(['wod_id' => $wod->id]);



  }
}
