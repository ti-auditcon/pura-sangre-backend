<?php

namespace App\Models\Exercises;

use App\Models\Wods\Stage;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exercises\ExerciseStage;

/**
 * [Exercise description]
 */
class Exercise extends Model
{
    protected $fillable = ['exercise'];

    /**
     * [stages relation]
     * @return [model] [description]
     */
    public function stages()
    {
       return $this->belongsToMany(Stage::class)->using(ExerciseStage::class);
    }
}
