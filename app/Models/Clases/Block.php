<?php

namespace App\Models\Clases;

use App\Models\Clases\BlockType;
use App\Models\Clases\Clase;
<<<<<<< HEAD
use App\Models\Users\User;
=======
use App\Models\Clases\ClaseType;
>>>>>>> dev
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
  protected $table = 'blocks';
  protected $fillable = ['start', 'end', 'dow', 'title',
            'date', 'profesor_id', 'clase_type_id'];
  protected $appends = ['plans_id','color'];
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

<<<<<<< HEAD
  public function block_type()
  {
    return $this->belongsTo(BlockType::class,'block_type_id');
=======
  public function claseType()
  {
    return $this->belongsTo(ClaseType::class);
>>>>>>> dev
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

  public function getColorAttribute()
  {
    return $this->claseType->clase_color;
  }

  public function clases()
  {
    return $this->hasMany(Clase::class);
  }

}
