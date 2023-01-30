<?php

namespace App\Models\Plans;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanStatus;
use App\Models\Users\StatusUser;
use App\Models\Clases\Reservation;
use App\Models\Plans\PostponePlan;
use App\Models\Plans\PlanUserPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanUser extends Model
{
    use SoftDeletes;

    /**
     * Name of the table in the database
     *
     * @var  string
     */
    protected $table = 'plan_user';

    /**
     * All values that are treated as dates
     *
     * @var  array
     */
    protected $dates = ['start_date', 'finish_date', 'deleted_at'];

    /**
     * Castable values
     *
     * @var  array
     */
    protected $casts = [
        'history' => 'collection',
    ];

    /**
     * [$fillable description]
     *
     * @var  [type]
     */
    protected $fillable = [
        'start_date',
        'finish_date',
        'counter',
        'plan_status_id',
        'plan_id',
        'user_id',
        'observations',
        'history'
    ];

    /**
     * Appended values to queries
     *
     * @var  array
     */
    protected $appends = ['human_start_date', 'human_finish_date'];

    /**
     * [getStartDateAttribute description]
     *
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    /**
     * [getFinishDateAttribute description]
     *
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getFinishDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    /**
     * Undocumented function
     *
     * @return  Carbon
     */
    public function getHumanStartDateAttribute()
    {
        // return $this->attributes['start_date']->format('d-m-Y');
        return $this->start_date->format('d-m-Y');
    }

    /**
     * Undocumented function
     *
     * @return  Carbon
     */
    public function getHumanFinishDateAttribute()
    {
        return $this->finish_date->format('d-m-Y');
    }

    /**
     * Check if the plan is freezed
     *
     * @return  bool
     */
    public function isFreezed() :bool
    {
        return (int) $this->plan_status_id === PlanStatus::CONGELADO;
    }

    /**
     * Check if the plan is active
     *
     * @return  bool
     */
    public function isActive() :bool
    {
        return (int) $this->plan_status_id === PlanStatus::ACTIVE;
    }

    /**
     * Check if the plan is in pre purchase
     *
     * @return  bool
     */
    public function isPrePurchase() :bool
    {
        return (int) $this->plan_status_id === PlanStatus::PRE_PURCHASE;
    }

    /**
     * Check if the plan is finished
     *
     * @return  bool
     */
    public function isFinished() :bool
    {
        return (int) $this->plan_status_id === PlanStatus::FINISHED;
    }

    /**
     * [bill description]
     *
     * @return [model] [description]
     */
    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    /**
     * [plan description]
     *
     * @method plan
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     *  Check if this PlanUser is a test plan
     *
     *  @return  bool
     */
    public function isATestPlan(): bool
    {
        return $this->plan->id === Plan::PRUEBA;
    }

    /**
     * [plan_status description]
     *
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan_status()
    {
        return $this->belongsTo(PlanStatus::class);
    }

    /**
     * status plan
     *
     * @return  string
     */
    public function getStatusAttribute()
    {
        return $this->plan_status->getPlanStatus($this->plan_status_id);
    }

    /**
     * Status Plan Color
     *
     * @return  string
     */
    public function getStatusColorAttribute()
    {
        return $this->plan_status->getPlanStatusColor($this->plan_status_id);
    }

    /**
     * [plan_user_periods description]
     *
     * @return App\Models\Plans\PlanUserPeriod
     */
    public function plan_user_periods()
    {
        return $this->hasMany(PlanUserPeriod::class);
    }

    /**
     * Get the freezed log of this plan
     * taking just the one which is available
     *
     * @return  App\Models\Plans\PostponePlan
     */
    public function postpone()
    {
        return $this->hasOne(PostponePlan::class)
                    ->where('revoked', false);
    }

    /**
     * [reservations description]
     *
     * @return [type] [description]
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * [user description]
     *
     * @method user
     * @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Store a new PlanUser
     *
     * @param   Flow   $data  [$data description]
     * @param   User   $user  [$user description]
     *
     * @return  $this
     */
    public static function makePlanUser($data, $user)
    {
        $plan_status = count($user->plan_users()->where('plan_status_id', 1)->get()) > 0 ?
                        PlanStatus::PRECOMPRA :
                        PlanStatus::ACTIVO;

        if ($plan_status === PlanStatus::ACTIVO) {
            $user->status_user_id = StatusUser::ACTIVE;
            $user->save();
        }

        return self::create([
            'counter'        => $data->plan->class_numbers,
            'user_id'        => $data->user_id,
            'plan_id'        => $data->plan_id,
            'start_date'     => $data->start_date,
            'finish_date'    => $data->finish_date,
            'plan_status_id' => $plan_status 
        ]);
    }

    /**
     * methodDescription
     *
     * @return  returnType
     */
    public function asignPlanToUser($request, Plan $plan, $user)
    {
        return $this->create([
            'counter'        => $request->counter,
            'user_id'        => $user->id,
            'plan_id'        => $plan->id,
            'start_date'     => Carbon::parse($request->start_date),
            'finish_date'    => Carbon::parse($request->finish_date),
            'observations'   => $request->observations,
            'plan_status_id' => PlanStatus::ACTIVO,
        ]);
    }
    
    /**
     * Update the planUser substracting quotas of it
     *
     * @param   int  $quotas_number
     *
     * @return  void
     */
    public function subQuotas(int $quotas_number)
    {
        $this->update([
            'counter' => $this->counter - $quotas_number
        ]);
    }

    /**
     * Finish the planUser passing to status "Completado"
     *
     * @return  void
     */
    public function finish()
    {
        $diffInDays = Carbon::parse($this->finish_date)->diffInDays(today());

        $this->update([
            'plan_status_id' => PlanStatus::FINISHED,
            'finish_date' => now()->startOfMinute()->subMinutes(2)
        ]);

        app(self::class)->moveBackDatesOfFuturePlans($this->user_id, $diffInDays); 
    }

    /**
     * Move back the dates (start_date and finish_date) of the future plans,
     * for now just the plans with the prePurchase status
     *
     * @param   int  $userId
     * @param   int  $diffInDays
     *
     * @return  void
     */
    public function moveBackDatesOfFuturePlans($userId, $diffInDays)
    {
        $future_plans = Planuser::where('user_id', $userId)
                                ->where('start_date', '>', today())
                                ->where('plan_status_id', PlanStatus::PRECOMPRA)
                                ->orderBy('finish_date')
                                ->get([
                                    'id', 'start_date', 'finish_date', 'user_id'
                                ]);

        foreach ($future_plans as $planUser) {
            $planUser->update([
                'start_date' => $planUser->start_date->subDays($diffInDays),
                'finish_date' => $planUser->finish_date->subDays($diffInDays)
            ]);
        }
    }

    public function activate()
    {
        return $this->update(['plan_status_id' => PlanStatus::ACTIVE]);
    }

    /**
     * To check if the planUser is current or not,
     * we check if the start_date is today or before and
     * if the finish_date is today or after
     *
     * @return  boolean
     */
    public function isCurrent(): bool
    {
        return $this->startsTodayOrBefore() && $this->finishesTodayOrAfter();
    }

    /**
     * Check if the planUser starts today or before
     *
     * @return  boolean
     */
    public function startsTodayOrBefore(): bool
    {
        return $this->start_date <= today();
    }

    /**
     * Check if the planUser finishes today or after
     *
     * @return  boolean
     */
    public function finishesTodayOrAfter(): bool
    {
        return $this->finish_date >= today();
    }

    /**
     * Check if the planUser is finished or not
     *
     * @return  boolean
     */
    public function finishesBeforeToday(): bool
    {
        return $this->finish_date < today();
    }

    /**
     * Check if there is a planUser that overlaps with the dates
     * 
     * @param   Carbon  $startDate
     * @param   Carbon  $endDate
     * @param   User  $user
     * 
     * @return  boolean
     */
    public function hasOverlappedDates($user, $startDate, $endDate)
    {
        return $this->where('user_id', $user->id)
            ->whereIn('plan_status_id', [PlanStatus::PRECOMPRA, PlanStatus::ACTIVE])
            ->where('start_date', '<=', $endDate)
            ->where('finish_date', '>=', $startDate)
            ->exists('id');
    }

    /**
     * Check if the plan dates are in between to some other plan
     *
     * @param   Carbon  $startDate
     * @param   Carbon  $endDate
     * @param   PlanUser  $planUser
     *
     * @return  boolean
     */
    public function checkPlanDatesInBetween($startDate, $endDate, $planUser)
    {
        return $this->where('start_date', '<=', $startDate)
                    ->where('finish_date', '>=', $endDate)
                    ->exists();
    }

    /**
     * Check if the plan dates are before to some other plan
     *
     * @param   Carbon  $startDate
     * @param   Carbon  $endDate
     * @param   PlanUser  $planUser
     *
     * @return  boolean
     */
    public function planDatesBeforeRequestPlan($startDate, $endDate, $planUser)
    {
        return $this->where('user_id', $planUser->user_id)
                    ->where('id', '!=', $planUser->id)
                    ->where('start_date', '<=', $startDate)
                    ->where('finish_date', '>=', $startDate)
                    ->exists();
    }
}
