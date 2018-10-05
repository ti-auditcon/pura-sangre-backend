<?php

namespace App\Models\Clases;

use App\Models\Clases\Clase;
use App\Models\Exercises\Stage;
use Illuminate\Database\Eloquent\Model;

class ClaseStage extends Model
{
	protected $table = 'clase_stage';

	/**
	 * [clase relation to this model]
	 * @return [model] [description]
	 */
	public function clase()
	{
		return $this->belongsTo(Clase::class);
	}

	/**
	 * [stage relation to this model]
	 * @return [model] [description]
	 */
	public function stage()
	{
		return $this->belongsTo(Stage::class);
	}
}
