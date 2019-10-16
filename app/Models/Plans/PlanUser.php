<?php

namespace App\Models\Plans;

use App\Models\Bills\Bill;
use App\Models\Clases\Reservation;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PlanUserPeriod;
use App\Models\Plans\PostponePlan;
use App\Models\Users\User;
use Carbon\Carbon;
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
     * [bill description]
     * @return [model] [description]
     */
    public function bill()
    {
        return $this->hasOne(Bill::class);
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
     * Get the information on the postponement of this plan.
     * 
     * @return App\Models\Plans\PostponePlan
     */
    public function postpone()
    {
        return $this->hasOne(PostponePlan::class);
    }

    /**
     * [reservations description]
     * @return [type] [description]
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
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
     * Create a bill from a plan user
     * 
     * @param  $request
     * @return App\Models\Bills
     */
    public function createBill($request)
    {
        return Bill::create([
            'plan_user_id' => $this->id,
            'payment_type_id' => $request->payment_type_id,
            'date' => Carbon::parse($request->date),
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
            'detail' => $request->detalle,
            'amount' => $request->amount
        ]);
    }
}
