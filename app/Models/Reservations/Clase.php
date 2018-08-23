<?php

namespace App\Models\Reservations;

use App\Models\Exercises\Stage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\Reservation;

class Clase extends Model
{
    protected $table = 'clases';
    protected $fillable = ['date', 'start_at', 'finish_at', 'room', 'profesor_id', 'quota'];

    public function reservations()
    {
      return $this->hasMany(Reservation::class);
    }

    public function stages()
    {
      return $this->belongsToMany(Stage::class);
    }
}
