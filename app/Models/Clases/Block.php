<?php

namespace App\Models\Clases;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
  protected $table = 'blocks';
  protected $fillable = ['start', 'end','dow'];
  protected $appends = ['plans_id'];
  // protected $with = array('plans');


  //transformamos el valor de dow a un arraglo para fullcalendar
  public function getDowAttribute($value)
  {
    $array = [];
    array_push($array,$value);
    return $array;
  }

  public function plans()
  {
    return $this->belongsToMany('App\Models\Plans\Plan', 'block_plan');
  }

  public function getPlansIdAttribute()
  {
    return $this->plans->pluck('id');
  }


}
