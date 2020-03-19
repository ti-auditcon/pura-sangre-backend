<?php

namespace App\Models\Wods;

use App\Models\Wods\Wod;
use App\Models\Clases\Clase;
use App\Models\Wods\StageType;
use App\Models\Clases\ClaseStage;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['wod_id', 'stage', 'stage_type_id', 'name', 'description', 'star'];

	/**
	 * [wod description]
	 * @return [type] [description]
	 */
	public function wod()
	{
		return $this->belongsTo(Wod::class);
	}

	/**
	 * [wod description]
	 * @return [type] [description]
	 */
	public function stage_type()
	{
		return $this->belongsTo(StageType::class);
	}

	/**
	 * [wod description]
	 * @return [type] [description]
	 */
	public function exercises()
	{
		return $this->belongsToMany(Exercise::class)->using(ExerciseStage::class);
	}
}
