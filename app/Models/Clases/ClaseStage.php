<?php

namespace App\Models\Clases;

use App\Models\Clases\Clase;
use App\Models\Wods\Stage;
use Illuminate\Database\Eloquent\Model;

class ClaseStage extends Model
{
    /**
     * Name of the table in the database
     *
     * @var  string
     */
    protected $table = 'clase_stage';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['clase_id', 'stage_id'];

	/**
	 * [clase relation to this model]
	 * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function clase()
	{
		return $this->belongsTo(Clase::class);
	}

	/**
	 * [stage relation to this model]
	 * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function stage()
	{
		return $this->belongsTo(Stage::class);
	}
}
