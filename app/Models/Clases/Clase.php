<?php

namespace App\Models\Clases;

use App\Models\Wods\Wod;
use App\Models\Users\User;
use App\Models\Clases\ClaseType;
use App\Models\Clases\Reservation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clase extends Model
{
    use SoftDeletes;

    /**
     *  Name of the table in the database
     *
     *  @var  string
     */
    protected $table = 'clases';

    /**
     *  The attributes that should be mutated to dates.
     *
     *  @var  array
     */
    protected $dates = [ 'date', 'deleted_at' ];

    /**
     *  The attributes that are mass assignable.
     *
     *  @var  array
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

    /**
     *  [$appends description]
     *
     *  @var  array
     */
    protected $appends = ['start', 'end', 'url', 'color'];

    /**
     *  Get all of the getReservationCountAttribute for the Clase
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getReservationCountAttribute()
    {
        return $this->hasMany(Reservation::class)->count('id');
    }

    /**
     *  [getStartAttribute description]
     * 
     *  @return  string|Carbon
     */
    public function getStartAttribute()
    {
        if ( $this->block->date == null ) {
            return $this->date->format('Y-m-d') . " " . $this->block->start;
        }

        return $this->block->start;
    }

    /**
     *  Get the end of the clase, date/date-time format
     * 
     *  @return  string
     */
    public function getEndAttribute()
    {
        if ($this->block->date == null) {
            return $this->date->format('Y-m-d') . " " . $this->block->end;
        }

        return $this->block->end;
    }

    /**
     *  [getUrlAttribute description]
     * 
     *  @return  string
     */
    public function getUrlAttribute()
    {
        return url('clases/' . $this->id);
    }

    /**
     *  [getColorAttribute description]
     * 
     *  @return  string
     */
    public function getColorAttribute()
    {
        return $this->claseType->clase_color;
    }

    /**
     * Get all of the comments for the Clase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     *  Get the wod that owns the Clase
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wod()
    {
        return $this->belongsTo(Wod::class);
    }

    /**
     *  The users that belong to the Clase
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->using(Reservation::class);
    }

    /**
     *  Get the user that owns the Clase
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function claseType()
    {
        return $this->belongsTo(ClaseType::class);
    }

    /**
     *  [profresor description]
     *
     *  @return  \App\Models\Users\User
     */
    public function profresor()
    {
        return $this->morphMany('App\Models\Users\User', 'userable');
    }

    /**
     *  The profesor that belong to the Clase
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function profesor()
    {
        return $this->belongsToMany(User::class)->using(Reservation::class);
    }

    /**
     *  Get the block that owns the Clase
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    /**
     *  Corroborate date and time of this class to check if can check attendance 
     *
     *  @return  bool
     */
    public function canCheckAttendance() :bool
    {
        return $this->isToday() && $this->startIsBeforeThanNow();
    }

    /**
     *  Check if the start_at hour is before than now
     * 
     *  Ex. If now= 18:00 and $this->start_at= 17:00, then return true
     *
     *  @return  bool
     */
    public function startIsBeforeThanNow() :bool
    {
        return $this->start_at <= now()->format('H:i:s');
    }

    /**
     *  Return true if the class date is today
     *
     *  @return  bool
     */
    public function isToday() :bool
    {
        return $this->date->format('Y-m-d') === today()->format('Y-m-d');
    }

    /**
     *  Check if the class is full or overload
     *
     *  @return  bool
     */
    public function isFull(): bool
    {
        return $this->reservations()->count('id') >= $this->quota;
    }
}
