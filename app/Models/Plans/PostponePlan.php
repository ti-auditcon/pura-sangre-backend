<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class PostponePlan extends Model
{
	/**
	 *  @var  string
	 */
	protected $table = 'freeze_plans';

	/**
     *  @var  array
	 */
	protected $fillable = ['plan_user_id', 'start_date', 'finish_date'];

	/**
     *  @var  array
	 */
	protected $dates = ['start_date', 'finish_date'];

	/**
	 *  PlanUser Relationship
	 * 
     *  @return  BelongsTo
	 */
	public function plan_user()
	{
		return $this->belongsTo(PlanUser::class, 'plan_user_id');
	}
}
