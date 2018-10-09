<?php

namespace App\Models\Exercises;

use App\Models\Clases\Clase;
use App\Models\Clases\ClaseStage;
Use App\Models\Exercises\Exercise;
use App\Models\Exercises\StageType;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\ExerciseStage;

/**
 * [Stage description]
 */
class Stage extends Model
{
  protected $fillable = ['stage', 'stage_type_id', 'name', 'description', 'star'];

  public function clases()
  {
    return $this->belongsToMany(Clase::class)->using(ClaseStage::class);
  }
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
