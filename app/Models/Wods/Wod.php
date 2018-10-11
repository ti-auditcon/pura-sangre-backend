<?php

namespace App\Models\Wods;

use Illuminate\Database\Eloquent\Model;
use App\Models\Clases\Clase;

class Wod extends Model
{
  protected $fillable = ['date','clase_type_id'];


  public function clases()
  {
    return $this->hasMany(Clase::class);
  }

  public function stages()
  {
    return $this->hasMany(Stage::class);
  }

}
