<?php

namespace App\Models\Exercises;

use Illuminate\Database\Eloquent\Model;
use App\Models\Reservations\ReservationStatisticStage;

class ExerciseStage extends Model
{
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
