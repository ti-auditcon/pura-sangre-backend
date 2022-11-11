<?php

namespace App\Models\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Database\Eloquent\Model;

class PlanUserPeriod extends Model
{
    /**
     * [$fillable description]
     *
     * @var [type]
     */
    protected $fillable = [
        'start_date', 'finish_date',
        'counter', 'plan_user_id'
    ];

    /**
     * [planuser description]
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planuser()
    {
    	return $this->belongsTo(PlanUser::class);
    }
}
