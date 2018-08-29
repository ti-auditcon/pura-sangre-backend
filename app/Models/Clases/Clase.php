<?php

namespace App\Models\Clases;

use App\Models\Users\User;
use App\Models\Exercises\Stage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clases\Reservation;

/**
 * [Clase description]
 */
class Clase extends Model
{
    protected $table = 'clases';
    protected $fillable = ['date', 'start_at', 'finish_at', 'room', 'profesor_id', 'quota'];

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
}
