<?php

namespace App\Models\Clases;

use App\Models\Clases\Clase;
use App\Models\Clases\ReservationStatisticStage;
use App\Models\Clases\ReservationStatus;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

/**
 * [Reservation description]
 */
class Reservation extends Model
{
    /**
     * [$fillable description]
     * @var [type]
     */
    protected $fillable = [
        'plan_user_id', 'clase_id', 'reservation_status_id',
        'user_id', 'by_god', 'details'
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

    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }

    /**
     * [clase description]
     * @return [type] [description]
     */
    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    /**
     * [reservation_status description]
     * @return [type] [description]
     */
    public function reservation_status()
    {
      return $this->belongsTo(ReservationStatus::class);
    }

    /**
     * [plan_user description]
     * @return [type] [description]
     */
    public function plan_user()
    {
      return $this->belongsTo(PlanUser::class);
    }
}
