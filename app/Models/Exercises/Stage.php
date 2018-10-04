<?php

namespace App\Models\Exercises;

Use App\Models\Exercises\Exercise;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\ExerciseStage;

/**
 * [Stage description]
 */
class Stage extends Model
{
  protected $fillable = ['stage'];

  /**
   * [exercises description]
   * @method exercises
   * @return [model]    [description]
   */
  public function exercises()
  {
    return $this->belongsToMany(Exercise::class)->using(ExerciseStage::class);
  }
}
