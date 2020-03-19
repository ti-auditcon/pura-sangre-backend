<?php

namespace App\Models\Plans;

use App\Models\Plans\Plan;
use Illuminate\Database\Eloquent\Model;

class PlanPeriod extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['period', 'period_number'];

  	/**
     * [plans description]
     * @return [type] [description]
     */
	public function plans()
	{
		return $this->hasMany(Plan::class);
	}
}
