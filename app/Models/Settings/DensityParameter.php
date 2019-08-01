<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;

class DensityParameter extends Model
{
	/**
	 * Name of the table in the DataBase
	 * 
	 * @var string
	 */
	protected $table = 'density_parameters';
	
	/**
	 * Columns for Massive Assignment 
	 * 
	 * @var array
	 */
<<<<<<< HEAD
    protected $fillable = ['level', 'from', 'to', 'color'];
=======
    protected $fillable = ['level', 'percentage', 'color'];
>>>>>>> parent of 17d130f... Revert "?"
}
