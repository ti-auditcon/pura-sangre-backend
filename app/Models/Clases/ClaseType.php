<?php

namespace App\Models\Clases;

use Illuminate\Database\Eloquent\Model;


class ClaseType extends Model
{
	protected $fillable = ['clase_type', 'clase_color'];

	/**
	 * Return all the clases associated to this Model
	 * 
	 * @return Illuminate\Database\Eloquent
	 */
	public function clases()
	{
		return $this->hasMany('App\Models\Clases\Clase'::class);
	}

	public function blocks()
	{
		return $this->belongsToMany('App\Models\Clases\Block',
									'clases');
	}
}
