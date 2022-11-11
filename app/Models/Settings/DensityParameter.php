<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class DensityParameter extends Model
{
    /**
     * Name of the table in the database
     *
     * @var  string
     */
	protected $table = 'density_parameters';
	
	/**
	 * Columns for Massive Assignment 
	 * 
	 * @var array
	 */
    protected $fillable = ['level', 'from', 'to', 'color'];
}
