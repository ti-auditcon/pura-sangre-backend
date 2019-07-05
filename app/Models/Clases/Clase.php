<?php

namespace App\Models\Clases;

use App\Models\Wods\Wod;
use App\Models\Users\User;
use App\Models\Wods\Stage;
use App\Models\Clases\ClaseType;
use App\Models\Clases\ClaseStage;
use App\Models\Clases\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clase extends Model
{
    use SoftDeletes;
    
    protected $table = 'clases';
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = ['date', 'start_at', 'finish_at', 'room', 'profesor_id', 'quota' ,'block_id','clase_type_id'];
    
    protected $appends = ['start','end','url','reservation_count','title','color'];

    // protected static function boot()
    // {
    //     parent::boot();
    // }


    /**
     * [getReservationCountAttribute description]
     * @return [type] [description]
     */
    public function getReservationCountAttribute()
    {
        return $this->hasMany(Reservation::class)->count();
    }

    /**
     * [getStartAttribute description]
     * @return [type] [description]
     */
    public function getStartAttribute()
    {
        if($this->block->date==null){
        
          return $this->date." ".$this->block->start;
        
        } else {
        
          return $this->block->start;
        
        }
    }

    /**
     * [getEndAttribute description]
     * @return [type] [description]
     */
    public function getEndAttribute()
    {
        if ($this->block->date == null) {
        
          return $this->date . " " . $this->block->end;
        
        } else {
        
          return $this->block->end;
        
        }
    }

    /**
     * [getTitleAttribute description]
     * @return [type] [description]
     */
    public function getTitleAttribute()
    {
        return '';
    }

    /**
     * [getUrlAttribute description]
     * @return [type] [description]
     */
    public function getUrlAttribute()
    {
        return url('clases/'.$this->id);
    }

    /**
     * [getColorAttribute description]
     * @return [type] [description]
     */
    public function getColorAttribute()
    {
        return $this->claseType->clase_color;
    }


    /**
     * [reservations description]
     * @return [type] [description]
     */
    public function reservations()
    {
      return $this->hasMany(Reservation::class);
    }

    /**
     * [stages relation to this model]
     * @return [model] [description]
     */
    // public function stages()
    // {
    //   return $this->belongsToMany(Stage::class)->using(ClaseStage::class);
    // }

    public function wod()
    {
      return $this->belongsTo(Wod::class);
    }

    /**
     * [users relation to this model]
     * @return [model] [description]
     */
    public function users()
    {
    return $this->belongsToMany(User::Class)->using(Reservation::class);
    }

    /**
     * [claseType description]
     * @return [type] [description]
     */
    public function claseType()
    {
      return $this->belongsTo(ClaseType::class);
    }

    /**
     * [profresor relation to this model]
     * @return [model] [description]
     */
    public function profresor()
    {
        return $this->morphMany('App\Models\Users\User', 'userable');
    }

    /**
     * [profesor relation to this model]
     * @return [model] [description]
     */
    public function profesor()
    {
        return $this->belongsToMany(User::Class)->using(Reservation::class);
    }

    /**
     * [block relation to this model]
     * @return [model] [description]
     */
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

}
