<?php

namespace App\Models\Clases;

use Illuminate\Database\Eloquent\Model;


class ClaseType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['clase_type', 'clase_color'];

	/**
	 * Return all the clases associated to this Model
	 *
	 * @return Illuminate\Database\Eloquent
	 */
	public function clases()
	{
		return $this->hasMany('App\Models\Clases\Clase');
	}

	/**
	 * Return all the blocks associated to this Model
	 *
	 * @return Illuminate\Database\Eloquent
	 */
	public function blocks()
	{
		return $this->hasMany('App\Models\Clases\Block');
	}

	public function stage_types()
	{
		return $this->hasMany('App\Models\Wods\StageType');
	}
}
