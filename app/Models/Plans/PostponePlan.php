<?php

namespace App\Models\Plans;

use Carbon\Carbon;
use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class PostponePlan extends Model
{
    public const ACTIVE = 0;
    public const REVOKED = 1;

    public const FROZEN_PLAN_MESSAGE = 'El plan se ha congelado correctamente.';

    public const UNFROZEN_PLAN_MESSAGE = 'El plan se ha descongelado correctamente.';

    /**
     * Name of the table in the database
     *
     * @var  string
     */
	protected $table = 'freeze_plans';

	/**
     * plan_user_id   integer
     * start_date     date
     * finish_date    date
     * days           integer  Remaining days of frozen plan
     * revoked        bool     If its active or not
     * 
     * @var  array
	 */
	protected $fillable = [
        'plan_user_id',
        'start_date',
        'finish_date',
        'days',
        'revoked'
    ];

	/**
     * @var  array
	 */
	protected $dates = ['start_date', 'finish_date'];

    /**
     * Get the PlanUser that owns the PostponePlan
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function plan_user()
	{
		return $this->belongsTo(PlanUser::class, 'plan_user_id');
	}
    
    /**
     * Get the PlanUser that owns the PostponePlan
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function planUser()
	{
		return $this->belongsTo(PlanUser::class, 'plan_user_id');
	}

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setFinishDateAttribute($value)
    {
        $this->attributes['finish_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * Finish the freezing of the plan
     *
     * @return  bool
     */
    public function revoke()
    {
        $this->update(['revoked' => true]);

        // $this->planUser->update([
        //     'plan_status_id' => PlanStatus::ACTIVE,
        //     'finish_date' => today()->endOfDay()->addDays($this->days)
        // ]);
    }
}
