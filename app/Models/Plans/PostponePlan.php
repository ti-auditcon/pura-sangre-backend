<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class PostponePlan extends Model
{
	/**
	 * [$table description]
	 * @var string
	 */
	protected $table = 'freeze_plans';

	/**
	 * [$fillable description]
	 * @var [type]
	 */
	protected $fillable = ['plan_user_id', 'start_date', 'finish_date'];

	/**
	 * [$dates description]
	 * @var [type]
	 */
	protected $dates = ['start_date', 'finish_date'];

	/**
	 * PlanUser Relationship
	 * @return Eloquent class
	 */
	public function plan_user()
	{
		return $this->belongsTo(PlanUser::class, 'plan_user_id');
	}
}
