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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_user_id', 'clase_id',
        'reservation_status_id', 'user_id', 'by_god', 'details'
    ];

    /**
     *  [reservation_statistic_stages description]
     *
     *  @method reservation_statistic_stages
     *
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

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function reservation_status()
    {
      return $this->belongsTo(ReservationStatus::class);
    }

    public function plan_user()
    {
      return $this->belongsTo(PlanUser::class);
    }
}
