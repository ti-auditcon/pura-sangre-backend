<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Model;

class PlanSummary extends Model
{
    /**
     *  Name of the table in the database
     *
     *  @var  string
     */
	protected $table = 'plans_summaries';

	/**
	 * Columns for Massive Assignment 
	 * 
	 * @var array
	 */
	protected $fillable = [
		'date',
		'active_users_day',
		'reservations_day',
		'cumulative_reservations',
		'day_incomes',
		'cumulative_incomes',
		'day_plans_sold',
		'cumulative_plans_sold'
	];
}
