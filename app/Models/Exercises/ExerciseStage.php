<?php

namespace App\Models\Exercises;

use App\Models\Wods\Stage;
use App\Models\Exercises\Exercise;
use Illuminate\Database\Eloquent\Model;
use App\Models\Clases\ReservationStatisticStage;

/**
 * [ExerciseStage description]
 */
class ExerciseStage extends Model
{

	/**
	 * [reservation_statistic_stages description]
	 * @method reservation_statistic_stages
	 * @return [model] [return reservation_statistic_stages model]
	 */
	public function reservation_statistic_stages()
	{
	  return $this->hasMany(ReservationStatisticStage::class);
	}

	/**
	 * [stage relation to this model]
	 * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function stage()
	{
		return $this->belongsTo(Stage::class);
	}

	/**
	 * [exercise relation to this model]
	 * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function exercise()
	{
		return $this->belongsTo(Exercise::class);
	}
}
