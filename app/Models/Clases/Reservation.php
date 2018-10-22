<?php

namespace App\Models\Clases;

use App\Models\Clases\Clase;
use App\Models\Clases\ReservationStatisticStage;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * [Reservation description]
 */
class Reservation extends Model
{
  use SoftDeletes;

  protected $dates = ['deleted_at'];
  protected $fillable = ['clase_id', 'reservation_status_id', 'user_id'];

  /**
   * clase relation to this model
   * @return model [description]
   */
  public function clase()
  {
    return $this->belongsTo(Clase::class);
  }

  /**
   * reservation_statistic_stages relation to this model
   * @method reservation_statistic_stages
   * @return model                       [description]
   */
  public function reservation_statistic_stages()
  {
    return $this->hasMany(ReservationStatisticStage::class);
  }

  /**
   * user relation to this model
   * @return model [description]
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }


}
