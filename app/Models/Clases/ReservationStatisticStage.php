<?php

namespace App\Models\Clases;

use App\Models\Exercises\Statistic;
use App\Models\Exercises\ExerciseStage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clases\Reservation;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * [ReservationStatisticStage description]
 */
class ReservationStatisticStage extends Model
{
    use SoftDeletes;

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
        'statistic_id', 'reservation_id', 'stage_exercise_id',
        'weight', 'time', 'round', 'repetitions',
    ];

  /**
   * [stage description]
   * @method stage
   *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function excercise_stage()
  {
    return $this->belongsTo(ExerciseStage::class);
  }

  /**
   * [reservation description]
   * @method reservation
   *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function reservation()
  {
    return $this->belongsTo(Reservation::class);
  }

  /**
  * [statistic description]
  * @method statistic
  *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
  */
  public function statistic()
  {
    return $this->belongsTo(Statistic::class);
  }

}
