<?php

namespace App\Models\Clases;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{

  //transformamos el valor de dow a un arraglo para fullcalendar
  public function getDowAttribute($value)
  {
    $array = [];
    array_push($array,$value);
    return $array;
  }
}
