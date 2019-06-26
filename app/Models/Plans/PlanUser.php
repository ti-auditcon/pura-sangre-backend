<?php

namespace App\Models\Plans;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanStatus;
use App\Models\Clases\Reservation;
use App\Models\Plans\PlanUserPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/** [PlanUser description] */
class PlanUser extends Model
{
    use SoftDeletes;

    /**
     * Define table name
     * @var string
    */
    protected $table = 'plan_user';
    
    /**
     * [$dates description]
     * @var [type]
     */
    protected $dates = ['start_date', 'finish_date', 'deleted_at'];
    
    /**
     * [$fillable description]
     * @var [type]
     */
    protected $fillable = [
        'start_date', 'finish_date', 'counter',
        'plan_status_id', 'plan_id', 'user_id', 'observations'
    ];

    /**
     * [getStartDateAttribute description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    /**
     * [getFinishDateAttribute description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getFinishDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    /**
     * [plan description]
     * @method plan
     * @return [model] [description]
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * [user description]
     * @method user
     * @return [model] [description]
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * [bill description]
     * @return [model] [description]
     */
    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    /**
     * [plan_status description]
     * @return [model] [description]
     */
    public function plan_status()
    {
        return $this->belongsTo(PlanStatus::class);
    }

    /**
     * [plan_user_periods description]
     * @return [model] [description]
     */
    public function plan_user_periods()
    {
        return $this->hasMany(PlanUserPeriod::class);
    }

    /**
     * [reservations description]
     * @return [type] [description]
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
