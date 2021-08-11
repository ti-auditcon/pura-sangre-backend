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
<<<<<<< Updated upstream
	 * [$fillable description]
	 * @var [type]
	 */
	protected $fillable = ['plan_user_id', 'start_date', 'finish_date'];
=======
     *  plan_user_id     int   
     *  start_date       date   
     *  finish_date      date  
     *  days_consumed    int      Days already consumed of the PlanUser
     *  total_plan_days  int      Days difference between start and end PlanUser
     *  revoked          boolean  
     * 
     *  @var  array
	 */
	protected $fillable = [
        'plan_user_id',
        'start_date',
        'finish_date',
        'days_consumed',
        'total_plan_days',
        'revoked'
    ];
>>>>>>> Stashed changes

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
