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
    // /**
    //  * The attributes that should be mutated to dates.
    //  *
    //  * @var array
    //  */
    // protected $dates = ['deleted_at'];

    /**
     *  Massive asignment values for Reservation
     *
     *  plan_user_id            integer      Id of the PlaUser
     *  clase_id                integer      Where this reservations belongs
     *  reservation_status_id   integer      Could has the next statuses: (active, consumed, pendient, cancelled)
     *  user_id                 integer      Who is gonna take the class
     *  by_god                  tinyInteger  Who made the reservation (meanwhile used to determine if the admin made it)
     *  details                 longText     **not used yet, goal is to student can make some notes after the class
     *
     *  @var  array
     */
    protected $fillable = [
        'plan_user_id',
        'clase_id',
        'reservation_status_id',
        'user_id',
        'by_god',
        'details'
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
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
      return $this->belongsTo(User::class);
    }
    // @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }
    //  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
    public function reservation_status()
    {
      return $this->belongsTo(ReservationStatus::class);
    }

    /**
     * [plan_user description]
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan_user()
    {
      return $this->belongsTo(PlanUser::class);
    }
}
