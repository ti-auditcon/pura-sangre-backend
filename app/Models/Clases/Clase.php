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

    /**
     *  The attributes that should be mutated to dates.
     *
     *  @var  array
     */
    protected $dates = [ 'date', 'deleted_at' ];

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'start_at',
        'finish_at',
        'room',
        'profesor_id',
        'quota',
        'wod_id',
        'block_id',
        'clase_type_id'
    ];

    protected $appends = ['start', 'end', 'url', 'reservation_count', 'color'];

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
        return $this->hasMany(Reservation::class)->count('id');
    }

    /**
     * [getStartAttribute description]
     * @return [type] [description]
     */
    public function getStartAttribute()
    {
        if ( $this->block->date == null ) {
            return $this->date->format('Y-m-d') . " ". $this->block->start;
        }

        return $this->block->start;
    }

    /**
     * [getEndAttribute description]
     * @return [type] [description]
     */
    public function getEndAttribute()
    {
        if ($this->block->date == null) {

          return $this->date->format('Y-m-d')k . " " . $this->block->end;

        } else {

          return $this->block->end;

        }
    }

    /**
     * [getTitleAttribute description]
     * @return [type] [description]
     */
    // public function getTitleAttribute()
    // {
    //     return '';
    // }

    /**
     * [getUrlAttribute description]
     * @return [type] [description]
     */
    public function getUrlAttribute()
    {
        return url('clases/' . $this->id);
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
        // @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
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
    return $this->belongsToMany(User::class)->using(Reservation::class);
    }

    /**
     * [claseType description]
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
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
        return $this->belongsToMany(User::class)->using(Reservation::class);
    }

    /**
     * [block relation to this model]
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

}
