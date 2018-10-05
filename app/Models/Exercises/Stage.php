<?php

namespace App\Models\Exercises;

Use App\Models\Exercises\Exercise;
use App\Models\Exercises\StageType;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\ExerciseStage;

/**
 * [Stage description]
 */
class Stage extends Model
{
  protected $fillable = ['stage', 'stage_type_id',
                        'name', 'description', 'star'];

  /**
   * [exercises description]
   * @method exercises
   * @return [model]    [description]
   */
  public function exercises()
  {
    return $this->belongsToMany(Exercise::class)->using(ExerciseStage::class);
  }

  public function stage_type()
  {
    return $this->belongsTo(StageType::class);
  }
}
