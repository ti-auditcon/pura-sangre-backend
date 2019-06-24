<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class FreezePlan extends Model
{
	protected $fillable = ['plan_user_id', 'start_date', 'finish_date'];

	/**
	 * PlanUser Relationship
	 * @return Eloquent class
	 */
	public function plan_user()
	{
		return $this->belongsTo(PlanUser::class);
	}
}
