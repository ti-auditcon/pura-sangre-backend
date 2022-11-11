<?php

namespace App\Models\Clases;

use App\Models\Wods\StageType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ClaseType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
        'clase_type',
        'clase_color',
        'icon',
        'icon_white',
        'active',
        'special'
    ];

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

    /**
     * Get all of the stageTypes for the ClaseType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stageTypes(): HasMany
    {
        return $this->hasMany(StageType::class);
    }
}
