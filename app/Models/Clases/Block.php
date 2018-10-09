<?php

namespace App\Models\Clases;

use App\Models\Clases\BlockType;
use App\Models\Clases\Clase;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
  protected $table = 'blocks';
  protected $fillable = ['start', 'end', 'dow', 'title',
            'date', 'profesor_id', 'block_type_id'];
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

  public function user()
  {
    return $this->belongsTo(User::class,'profesor_id');
  }

  public function block_type()
  {
    return $this->belongsTo(BlockType::class,'block_type_id');
  }

  public function getPlansIdAttribute()
  {
    return $this->plans->pluck('id');
  }

  public function getStartAttribute($value)
  {
    if($this->date!=null){
      return $this->date.' '.$value;
    }
    else
    {
      return $value;
    }
  }

  public function getEndAttribute($value)
  {
    if($this->date!=null){
      return $this->date.' '.$value;
    }
    else
    {
      return $value;
    }
  }

  public function clases()
  {
    return $this->hasMany(Clase::class);
  }

}
