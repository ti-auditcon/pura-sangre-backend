<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class PostponePlan extends Model
{
    /**
     *  Name of the table in the database
     *
     *  @var  string
     */
	protected $table = 'freeze_plans';

	/**
     * 
     *  plan_user_id   integer
     *  start_date     date
     *  finish_date    date
     *  days           integer  Should this be the resting days
     *  revoked        bool
     * 
     *  @var  array
	 */
	protected $fillable = [
        'plan_user_id',
        'start_date',
        'finish_date',
        'days',
        'revoked'
    ];

	/**
     *  @var  array
	 */
	protected $dates = ['start_date', 'finish_date'];

    /**
     *  Get the PlanUser that owns the PostponePlan
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function plan_user()
	{
		return $this->belongsTo(PlanUser::class, 'plan_user_id');
	}

    /**
     *  Finish the freezing of the plan
     *
     *  @return  bool
     */
    public function revoke()
    {
        $this->update(['revoked' => true]);
    }
}
