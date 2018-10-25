<?php

namespace App\Models\Clases;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clases\ReservationStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Clases\ReservationStatisticStage;

/**
 * [Reservation description]
 */
class Reservation extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = ['clase_id', 'reservation_status_id', 'user_id'];

  /**
   * [reservation_statistic_stages description]
   * @method reservation_statistic_stages
   * @return [type]                       [description]
   */
  public function reservation_statistic_stages()
  {
    return $this->hasMany(ReservationStatisticStage::class);
  }

  /**
   * [user description]
   * @return [type] [description]
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function reservation_status()
  {
    return $this->belongsTo(ReservationStatus::class);
  }

}
