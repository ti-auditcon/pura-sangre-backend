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
     * Define table name
     *
     * @var string
    */
    protected $table = 'plan_user';

    /**
     *  All values that are treated as dates
     *
     *  @var  array
     */
    protected $dates = ['start_date', 'finish_date', 'deleted_at'];

    /**
     *  Castable values
     *
     *  @var  array
     */
    protected $casts = [
        'history' => 'collection',
    ];

    /**
     *  [$fillable description]
     *
     *  @var  [type]
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
     *  [getFinishDateAttribute description]
     *
     *  @param  [type] $value [description]
     *  @return [type]        [description]
     */
    public function getFinishDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    /**
     *  If the plan has the status freezed return true
     *
     *  @return  bool
     */
    public function isFreezed() :bool
    {
        return (int) $this->plan_status_id === PlanStatus::CONGELADO;
    }

    /**
     *  [bill description]
     *
     *  @return [model] [description]
     */
    public function bill()
    {
        return $this->hasOne(Bill::class);
    }

    /**
     *  [plan description]
     *
     *  @method plan
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     *  [plan_status description]
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan_status()
    {
        return $this->belongsTo(PlanStatus::class);
    }

    /**
     *  status plan
     *
     *  @return  string
     */
    public function getStatusAttribute()
    {
        return $this->plan_status->getPlanStatus($this->plan_status_id);
    }

    /**
     *  Status Plan Color
     *
     *  @return  string
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
     *  Get the freezed log of this plan
     *  taking just the one which is available
     *
     *  @return  App\Models\Plans\PostponePlan
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
     *  Store a new PlanUser
     *
     *  @param   Flow   $data  [$data description]
     *  @param   User   $user  [$user description]
     *
     *  @return  $this
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
     *  methodDescription
     *
     *  @return  returnType
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

    // /**
    //  *  The plan can be:
    //  *  - Prueba: just add a week and the counter is equals to class_numbers
    //  *  - Custom: The finish date of the plan is defined at creation part
    //  *  - Others: add months and counter guided by plan
    //  *
    //  *  @param   [type]  $plan    
    //  *  @param   [type]  $request 
    //  *
    //  *  @return  array
    //  */
    // public function manageSpecificParametersForPlan(Plan $plan, $request)
    // {        
    //     if ($plan->isPrueba()) {
    //         $finish_date = Carbon::parse($request->fecha_inicio)->addWeeks(1);
    //         $counter = $plan->class_numbers;

    //         return [$finish_date, $counter];
    //     }
        
    //     if ($plan->isCustom()) {
    //         $finish_date = Carbon::parse($request->fecha_termino);
    //         $counter = $request->counter;

    //         return [$finish_date, $counter];
    //     }

    //     $finish_date = Carbon::parse($request->fecha_inicio)->addMonths($plan->plan_period->period_number)->subDay();
    //     $counter = $plan->class_numbers * $plan->plan_period->period_number * $plan->daily_clases;

    //     return [$finish_date, $counter];
    // }
}
