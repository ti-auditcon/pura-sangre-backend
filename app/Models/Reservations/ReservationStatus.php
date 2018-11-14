<?php

namespace App\Models\Reservations;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\Reservation;

class ReservationStatus extends Model
{
    protected $fillable = ['reservation_status'];

    /**
     * [reservations description]
     * @method reservations
     * @return [type]       [description]
     */
    public function reservations()
    {
      return $this->hasMany(Reservation::class);
    }
}
