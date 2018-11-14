<?php

namespace App\Models\Reservations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Reservations\ReservationStatisticStage;

/**
 * [Reservation description]
 */
class Reservation extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = [
    'date',
    'start_at',
    'finish_at',
    'room',
    'profesor_id',
    'quota',
  ];

  /**
   * [reservation_statistic_stages description]
   * @method reservation_statistic_stages
   * @return [type]                       [description]
   */
  public function reservation_statistic_stages()
  {
    return $this->hasMany(ReservationStatisticStage::class);
  }

}
