<?php

namespace App\Models\Users;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\Role;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Users\Session;
use App\Models\Plans\PlanUser;
use App\Models\Users\RoleUser;
use App\Models\Users\Emergency;
use App\Models\Plans\PlanStatus;
use App\Models\Users\StatusUser;
use Freshwork\ChileanBundle\Rut;
use App\Models\Clases\Reservation;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Notifications\MyResetPassword;
use App\Models\Clases\ReservationStatus;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     *
     * @var array
     */
    protected $dates = ['birthdate', 'since', 'deleted_at'];

    /**
     *
     * The attributes that are mass assignable.
     *
     * @var  array
     */
    protected $fillable = [
        'rut', 'first_name', 'last_name', 'email',
        'password', 'avatar', 'phone', 'birthdate',
        'gender', 'address', 'lat', 'lng', 'since',
        'emergency_id', 'status_user_id', 'email_verified_at'
    ];

    /**
     * [$hidden description]
     *
     * @var  array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * [$appends description]
     *
     * @var  array
     */
    protected $appends = ['full_name', 'rut_formated', 'status', 'status_color'];

    /**
     * [setBirthdateAttribute description]
     *
     * @param [type] $value [description]
     */
    public function setBirthdateAttribute($value)
    {
        $this->attributes['birthdate'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * [setSinceAttribute description]
     *
     * @param [type] $value [description]
     */
    public function setSinceAttribute($value)
    {
        $this->attributes['since'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
    * Send the password reset notification.
    *
    * @param  string  $token
    * @return void
    */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));
    }

    /**
     * Check if the user has the given or one of the given roles
     *
     * @param   array $roles
     *
     * @return  bool
     */
    public function hasNotRole(array $roles)
    {
        return RoleUser::whereIn('role_id', $roles)
            ->where('user_id', $this->id)
            ->doesntExist('id');
    }

    /**
     * Verified if auth user has an specific Role
     *
     * @param   integer
     *
     * @return  boolean
     */
    public function hasRole($roleId)
    {
        return RoleUser::where('role_id', $roleId)
                       ->where('user_id', $this->id)
                       ->exists();
    }

    /**
     * Check if user has ADMIN Role
     *
     * @param   integer
     *
     * @return  boolean
     */
    public function isAdmin()
    {
        return $this->roles()->whereId(Role::ADMIN)->exists(['id']);
    }

    /**
     * [setRutAttribute description]
     *
     * @param [type] $value [description]
     */
    public function setRutAttribute($value)
    {
        $this->attributes['rut'] = Rut::parse($value)->number();
    }

    /**
     * [getFullNameAttribute description]
     *
     * @return [type] [description]
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * [getFullNameAttribute description]
     *
     * @return [type] [description]
     */
    public function getRutFormatedAttribute()
    {
        return Rut::set($this->rut)->fix()->format();
    }

    /**
     * Scope a query to get all the users.
     *
     * @param   \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllUsers($query)
    {
        $query->with([
                    'actual_plan:id,start_date,finish_date,user_id,plan_id',
                    'actual_plan.plan:id,plan',
                ])
                ->get(['id', 'rut', 'first_name', 'last_name', 'email', 'avatar', 'status_user_id']);
    }

    /**
     * Get the Status User
     *
     * @return  string
     */
    public function getStatusAttribute()
    {
        return StatusUser::getStatus($this->status_user_id);
    }

    /**
     * Get the Status User
     *
     * @return  string
     */
    public function getStatusColorAttribute()
    {
        return StatusUser::getStatusColor($this->status_user_id);
    }

    /**
     * [scopeCountStatusUsers description]
     *
     * @param  [type] $users [description]
     * @return [type]        [description]
     */
    public function scopeCountStatusUsers($users)
    {
        $users->groupBy('status_user_id')
              ->selectRaw('count(*) as total, status_user_id');
    }

    /**
     * Return all the clase of this User
     *
     * @return App\Models\Clases\Clase
     */
    public function clases()
    {
        return $this->belongsToMany(Clase::class, 'reservations', 'user_id', 'clase_id');
    }

    /**
     * Return the status of this User
     *
     * @return App\Models\User\StatusUser
     */
    public function status_user()
    {
        return $this->belongsTo(StatusUser::class);
    }

    /**
     * Return all the plans of this User
     *
     * @return App\Models\Plans\Plan
     */
    public function plans()
    {
        return $this->belongsToMany(Plan::class)
                    ->using(PlanUser::class);
    }

    public function hasNotACurrentPlan()
    {
        return $this->actual_plan()->doesntExist();
    }

    public function currentPlan()
    {
        return $this->actual_plan()->first();
    }

    /**
     * Get the active current plan of this User
     *
     * @return  \App\Models\Plans\PlanUser
     */
    public function actual_plan()
    {
        return $this->hasOne(PlanUser::class)
                    ->where('plan_status_id', PlanStatus::ACTIVE)
                    ->where('start_date', '<=', now())
                    ->where('finish_date', '>=', now());
    }

    /**
     * Return all the plans of this User
     *
     * @return App\Models\Plans\PlanUser
     */
    public function last_plan()
    {
        return $this->hasOne(PlanUser::class)
                    ->where('plan_status_id', '!=', 5)
                    ->orderByDesc('finish_date');
    }

    /**
     * Return the 10 first next reservations of this User
     *
     * @return  App\Models\Clases\Reservation
     */
    public function futureReservations()
    {
        return $this->HasMany(Reservation::class)
                    ->whereIn('reservation_status_id', [
                        ReservationStatus::PENDING,
                        ReservationStatus::CONFIRMED
                    ]);
    }

    /**
     * Get all of the sessions for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Return all the plans of the user,
     * with plan status: 'activo' and 'precompra'
     *
     * @return App\Models\Plans\PlanUser
     */
    public function reservable_plans()
    {
        return $this->hasMany(PlanUser::class)
                    ->whereIn('plan_status_id', [1,3]);
    }

    /**
     * Check if the user is currently inactive into the system
     *
     * @return  bool
     */
    public function isInactive(): bool
    {
        return (int) $this->status_user_id === StatusUser::INACTIVE;
    }

    /**
     * Check if the user is "Test user" into the system
     *
     * @return  bool
     */
    public function isTest(): bool
    {
        return (int) $this->status_user_id === StatusUser::TEST;
    }

    /**
     * [listStudents description]
     *
     * @return  collection
     */
    public function listStudents()
    {
        return $this->leftJoin('plan_user', 'plan_user.user_id', 'users.id')
                    ->with(['todayPlan' => function ($todayPlan) {
                        $todayPlan->with(['plan:id,plan'])
                            ->select(
                                'id',
                                'user_id',
                                'plan_id',
                                'start_date',
                                'finish_date',
                                'counter',
                                'plan_status_id'
                            );
                    }])
                    ->distinct()
                    ->get([
                        'users.id', 'users.rut', 'users.first_name', 'users.last_name',
                        'users.email', 'users.avatar', 'users.status_user_id'
                    ]);
    }

    /**
     * Get a plan that:
     *  -  Be active or freezed
     *  -  Start be before or equals today
     *  -  End be after or equals today
     *
     * @return  \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function todayPlan()
    {
        return $this->hasOne(PlanUser::class)
                    ->whereIn('plan_status_id', [PlanStatus::ACTIVE, PlanStatus::FROZEN])
                    ->where('start_date', '<=', today())
                    ->where('finish_date', '>=', today());
    }

    /**
     * [description]
     *
     * @return App\Models\Clases\Block
     */
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    /**
     * Return all the bills of this User
     *
     * @return App\Models\Bills\Bill
     */
    public function bills()
    {
        return $this->hasManyThrough(
            Bill::class,
            PlanUser::class,
            'user_id',
            'plan_user_id'
        );
    }

    /**
     * Return all the plans of this User,
     * ordered by plan status and start date of the plan
     *
     * @return App\Models\Plans\PlanUser
     */
    public function plan_users()
    {
        return $this->hasMany(PlanUser::class)
                    ->orderBy('plan_status_id', 'ASC')
                    ->orderBy('start_date','desc');
    }

    /**
     * [planes_del_usuario description]
     *
     * @return [type] [description]
     */
    public function planes_del_usuario()
    {
        return PlanUser::where('user_id', $this->id)
                       ->with(['bill:id,date,amount,payment_type_id,plan_user_id',
                                'bill.payment_type:id,payment_type',
                                'plan:id,plan,class_numbers',
                                'plan_status:id,plan_status,type,can_delete',
                                'postpone',
                                'user:id,first_name'])
                       ->get(['id', 'start_date', 'finish_date', 'counter', 'plan_id', 'plan_status_id', 'user_id']);
    }

    /**
     * [regular_users description]
     *
     * @return [collection] [description]
     */
    public function regular_users()
    {
        return User::all()->where('admin', 'false')->orderBy('name');
    }

    // public function reservations()
    // {
    //     return $this->hasManyThrough(Reservation::class, PlanUser::class, 'user_id', 'plan_user_id');
    // }

    /**
     * [last_plan description]
     *
     * @return [type] [description]
     */
    public function emergency()
    {
        return $this->hasOne(Emergency::class);
    }

    /**
     * [last_plan description]
     *
     * @return [type] [description]
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * [roles description]
     *
     * @return [type] [description]
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    /**
     * Get all Roles Ids of the User
     *
     * @return  array
     */
    public function rolesId()
    {
        return $this->roles()->pluck('id')->toArray();
    }

    /**
     * [getAvatarAttribute description]
     *
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function getAvatarAttribute($value)
    {
        if ( !$value ) {
            return url('img/default_user.png');
        }

        return $value;
    }

    /**
     * [birthdate_users description]
     *
     * @return
     */
    public function birthdate_users()
    {
        return $this->whereMonth('birthdate', toDay()->month)
                    ->whereDay('birthdate', toDay()->day)
                    ->get(['id', 'first_name', 'last_name', 'avatar', 'birthdate']);
    }

    /**
     * [itsBirthDay description]
     *
     * @return [type] [description]
     */
    public function itsBirthDay()
    {
        if ($this->birthdate->month == toDay()->month && $this->birthdate->day == toDay()->day) {
            return true;
        }

        return false;
    }

    /**
     * Reservations 'Consumidas' and 'perdidas'
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function past_reservations()
    {
        return Reservation::leftJoin('clases', 'clases.id', '=', 'reservations.clase_id')
                            ->leftJoin('plan_user', 'reservations.plan_user_id', '=', 'plan_user.id')
                            ->leftJoin('plans', 'plan_user.plan_id', '=', 'plans.id')
                            ->where('reservations.user_id', $this->id)
                            ->whereIn('reservations.reservation_status_id', [ReservationStatus::CONSUMED, ReservationStatus::LOST])
                        ->get([
                            'reservations.id as reservationId', 'reservations.clase_id', 'reservations.user_id',
                            'reservations.plan_user_id', 'reservations.reservation_status_id',
                            'clases.date', 'clases.start_at', 'clases.finish_at',
                            'plans.plan'
                        ]);
    }

    /**
     * Assign test plan to the user
     *
     * @param   Plan  $plan
     *
     * @return void
     */
    public function assignTestPlan(Plan $plan)
    {
        $start_date = request('since') 
                        ? Carbon::parse(request('since'))
                        : today();

        $this->plan_users()->create([
            'plan_id'        => $plan->id,
            'start_date'     => $start_date,
            'finish_date'    => $start_date->copy()->addDays(7),
            'plan_status_id' => PlanStatus::ACTIVE,
            'counter'        => $plan->class_numbers
        ]);
    }

    public function updateStatus()
    {
        if ($plan = $this->currentPlan()) {
            $this->status_user_id = $plan->isATestPlan()
                ? StatusUser::TEST
                : StatusUser::ACTIVE;
        } else {
            $this->status_user_id = StatusUser::INACTIVE;
        }

        $this->save();
    }

    /**
     * Scope a query to get all the users who had a plan in the date range
     *
     * @param   Builder  $query
     * @param   Carbon   $start
     * @param   Carbon   $end  
     *
     * @return  Builder
     */
    public function scopeActiveInDateRange(Builder $query, $start, $end)
    {
        return $query->join('plan_user', 'users.id', '=', 'plan_user.user_id')
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->whereNull('plan_user.deleted_at')
            ->select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'users.avatar', 'users.phone', 'users.rut')
            ->distinct('users.id');
    }

    /**
     * Query to get all the users who had a plan before given date,
     * and the plan is not a trial and the user has no other plan after the given date
     *
     * @param   Builder  $query
     * @param   Carbon   $lastDate
     *
     * @return  Builder
     */
    public static function getDropouts($startDate, $endDate)
    {
        return self::select('users.id as id')
            ->join('plan_user', 'users.id', '=', 'plan_user.user_id')
            ->join('plans', 'plan_user.plan_id', '=', 'plans.id')
            ->whereBetween('plan_user.finish_date', [$startDate, $endDate])
            ->whereNotExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('plan_user as plan_user2')
                    ->whereColumn('plan_user2.user_id', 'plan_user.user_id')
                    ->whereRaw('plan_user2.start_date > plan_user.finish_date')
                    ->where('plan_user2.plan_status_id', '!=', PlanStatus::CANCELED)
                    ->where('plan_user2.plan_id', '!=', Plan::TRIAL)
                    ->whereNull('plan_user2.deleted_at');
            })
            ->where('plans.id', '!=', Plan::TRIAL)
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->whereNull('plan_user.deleted_at')
            ->distinct()
            ->get();
    }

    /**
     * Scope a query to get all the new students in the date range who hadn't a plan before the given start,
     * except if the plan is a trial, and the current plan is not a trial.
     *
     * @param   Builder  $query
     * @param   Carbon   $start
     * @param   Carbon   $end
     *
     * @return  Builder
     */
    public function scopeNewStudentsInDateRange(Builder $query, $start, $end)
    {
        return $query->join('plan_user', 'users.id', '=', 'plan_user.user_id')
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->whereNotExists(function ($subQuery) use ($start) {
                $subQuery->select(DB::raw(1))
                    ->from('plan_user as previous_plan')
                    ->whereColumn('previous_plan.user_id', 'plan_user.user_id')
                    ->whereRaw('previous_plan.finish_date < plan_user.start_date')
                    ->where('previous_plan.plan_status_id', '!=', PlanStatus::CANCELED)
                    ->where('previous_plan.plan_id', '!=', Plan::TRIAL)
                    ->whereNull('previous_plan.deleted_at');
            })
            ->whereNull('plan_user.deleted_at')
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plan_user.plan_id', '!=', Plan::TRIAL)
            ->distinct('users.id');
    }

    /**
     * Scope a query to get all the users who had a plan in the date range.
     *
     * @param   Builder  $query
     * @param   Carbon   $start
     * @param   Carbon   $end
     *
     * @return  Builder
     */
    public function scopeTurnaroundInDateRange(Builder $query, $start, $end)
    {
        return $query->join('plan_user', 'users.id', '=', 'plan_user.user_id')
            ->join('plans', 'plan_user.plan_id', '=', 'plans.id')
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->whereNull('plan_user.deleted_at')
            ->distinct('users.id');
    }
}
