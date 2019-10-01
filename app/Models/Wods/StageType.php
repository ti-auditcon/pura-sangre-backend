<?php

namespace App\Models\Wods;

use App\Models\Wods\Stage;
use App\Models\Clases\ClaseType;
use Illuminate\Database\Eloquent\Model;

class StageType extends Model
{
	/**
	 * Mass assignment for this model
	 *  
	 * @var array
	 */
  	protected $fillable = ['stage_type', 'clase_type_id', 'featured'];

	/**
	 * Get all the stages of this model
	 * 
	 * @return Illuminate\Database\Eloquent\Relations\HasMany
	 */
  	public function stages()
  	{	
      	return $this->hasMany(Stage::class);
  	}

  	/**
  	 * Get the clase type who this model belongs
  	 * 
  	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
  	 */
  	public function clase_type()
  	{
  		return $this->belongsTo(ClaseType::class);
  	}
}
