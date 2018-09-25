<?php

namespace App\Models\Clases;

use App\Models\Users\User;
use App\Models\Exercises\Stage;
use App\Models\Clases\Reservation;
use Illuminate\Database\Eloquent\Model;

/**
 * [Clase description]
 */
class Clase extends Model
{
    protected $table = 'clases';
    protected $fillable = ['date', 'start_at', 'finish_at', 'room', 'profesor_id', 'quota' ,'block_id'];
    protected $appends = ['start','end','url','reservation_count'];

    /**
     * [reservations description]
     * @return [type] [description]
     */
    public function reservations()
    {
      return $this->hasMany(Reservation::class);
    }

    /**
     * [stages description]
     * @return [type] [description]
     */
    public function stages()
    {
      return $this->belongsToMany(Stage::class);
    }

    /**
     * [users description]
     * @return [type] [description]
     */
    public function users()
    {
    return $this->belongsToMany(User::Class)->using(Reservation::class);
    }

    public function block()
    {
      return $this->belongsTo(Block::class);
    }

    public function getStartAttribute()
    {
      return $this->date.' '.$this->block->start;
    }

    public function getEndAttribute()
    {
      return $this->date.' '.$this->block->end;
    }

    public function getUrlAttribute()
    {
      return url('clases/'.$this->id);
    }

    public function getReservationCountAttribute()
    {
      return $this->hasMany(Reservation::class)->count();
    }
}
