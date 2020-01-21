<?php

namespace App\Models\Users;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\Role;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Users\RoleUser;
use App\Models\Users\Emergency;
use App\Models\Users\Millestone;
use Freshwork\ChileanBundle\Rut;
use App\Models\Users\StatusUser;
use App\Models\Bills\Installment;
use Laravel\Passport\HasApiTokens;
use App\Models\Clases\Reservation;
use App\Notifications\MyResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * [$dates description]
     * 
     * @var array
     */
    protected $dates = ['birthdate', 'since', 'deleted_at'];
    
    /**
     * [$fillable description]
     * 
     * @var array
     */
    protected $fillable = [
        'rut',
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'phone',
        'birthdate', 
        'gender', 
        'address',
        'lat',
        'lng',
        'since',
        'emergency_id', 
        'status_user_id'
    ];

    /**
     * [$hidden description]
     * 
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    
    /**
     * [$appends description]
     * 
     * @var array
     */
    protected $appends = ['full_name', 'rut_formated'];

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
     * Verified if auth user has an specific Role
     * 
     * @param  integer
     * @return boolean
     */
    public function hasRole($role)
    {
        return RoleUser::where('role_id', $role)
                       ->where('user_id', $this->id)
                       ->exists();
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
        return $this->first_name.' '.$this->last_name;
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllUsers($query)
    {
        $query->select(['id', 'rut', 'first_name', 'last_name', 'email', 'avatar', 'status_user_id'])
              ->with(['actual_plan:id,start_date,finish_date,user_id,plan_id',
                      'actual_plan.plan:id,plan'
                     ]);
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
        return $this->belongsToMany(Clase::Class, 'reservations', 'user_id', 'clase_id');
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

    /**
     * Get the active plan of this User
     * 
     * @return App\Models\Plans\PlanUser
     */
    public function actual_plan()
    {
        return $this->hasOne(PlanUser::class)->where('plan_status_id', 1)
                                             ->where('start_date','<=', today())
                                             ->where('finish_date','>=', today());
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
     * @return App\Models\Clases\Reservation
     */
    public function future_reservs()
    {
        return $this->HasMany(Reservation::class)
                    ->whereIn('reservation_status_id', [1,2])
                    ->take(10);
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
     * @return [type] [description]
     */
    public function birthdate_users()
    {
        return User::whereMonth('birthdate', toDay()->month)
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
        return Reservation::where('user_id', $this->id)
                          ->whereIn('reservation_status_id', [3,4])
                          ->with(['clase:id,date,start_at,finish_at',
                                  'reservation_status:id,reservation_status',
                                  'plan_user:id,plan_id',
                                  'plan_user.plan:id,plan'])
                          ->get(['id', 'clase_id', 'user_id', 'plan_user_id', 'reservation_status_id']);
    }
}