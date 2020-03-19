<?php

namespace App\Models\Clases;

use Illuminate\Database\Eloquent\Model;
use App\Models\Clases\Reservation;

/**
 * [ReservationStatus description]
 */
class ReservationStatus extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
