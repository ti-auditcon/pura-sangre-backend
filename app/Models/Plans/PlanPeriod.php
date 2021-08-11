<?php

namespace App\Models\Plans;

use App\Models\Plans\Plan;
use Illuminate\Database\Eloquent\Model;

class PlanPeriod extends Model
{
    /**
     * MONTHLY period for plans
     *
     * @var  integer
     */
    const MONTHLY = 1;
    
    /**
     * BIMONTHLY period for plans
     *
     * @var  integer
     */
    const BIMONTHLY = 2;
    
    /**
     * QUARTERLY period for plans
     *
     * @var  integer
     */
    const QUARTERLY = 3;
    
    /**
     * BIANNUAL period for plans
     *
     * @var  integer
     */
    const BIANNUAL = 6;
    
    /**
     * ANNUAL period for plans
     *
     * @var  integer
     */
    const ANNUAL = 12;

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
