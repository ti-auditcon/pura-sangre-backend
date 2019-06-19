<?php

namespace App\Models\Users;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\Role;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Users\RoleUser;
use App\Models\Plans\PlanUser;
use App\Models\Users\Emergency;
use Freshwork\ChileanBundle\Rut;
use App\Models\Users\Millestone;
use App\Models\Users\StatusUser;
use App\Models\Bills\Installment;
use App\Models\Clases\Reservation;
use Laravel\Passport\HasApiTokens;
use App\Notifications\MyResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $dates = ['birthdate','since','deleted_at'];
    protected $fillable = [
        'rut', 'first_name', 'last_name',
        'email', 'password', 'avatar', 'phone',
        'birthdate', 'gender', 'address', 'since',
        'emergency_id', 'status_user_id'
    ];
    protected $hidden = ['password', 'remember_token'];
    protected $appends = ['full_name'];


    public function setBirthdateAttribute($value)
    {
        $this->attributes['birthdate'] = Carbon::parse($value)->format('Y-m-d');
    }

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
     * [hasRole description]
     * @param  [type]  $role [description]
     * @return boolean       [description]
     */
    public function hasRole($role)
    {
        $role = RoleUser::where('role_id', $role)->where('user_id', $this->id)->get();
        if (count($role) > 0) {
            return true;
        }
    }

    /**
     * [setRutAttribute description]
     * @param [type] $value [description]
     */
    public function setRutAttribute($value)
    {
        $this->attributes['rut'] = Rut::parse($value)->number();
    }

     /**
     * [getFullNameAttribute description]
     * @return [type] [description]
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
    * [clases description]
    * @return [type] [description]
    */
    public function clases()
    {
        return $this->belongsToMany(Clase::Class, 'reservations', 'user_id', 'clase_id');
    }

    /**
    * [status_user description]
    * @method status_user
    * @return [Model]      [description]
    */
    public function status_user()
    {
        return $this->belongsTo(StatusUser::class);
    }

    /**
    * [plans description]
    * @return [type] [description]
    */
    public function plans()
    {
        return $this->belongsToMany(Plan::class)->using(PlanUser::class);
    }

    /**
    * metodo para obtener el plan activo del usuario
    * @return [type] [description]
    */
    public function actual_plan()
    {
        return $this->hasOne(PlanUser::class)->where('plan_status_id', 1)
                                             ->where('start_date','<=', today())
                                             ->where('finish_date','>=', today());
    }

    public function last_plan()
    {
        return $this->hasOne(PlanUser::class)->where('plan_status_id', '!=', 5)->orderByDesc('finish_date');
    }

    public function future_reservs()
    {
        return $this->HasMany(Reservation::class)->whereIn('reservation_status_id', [1,2])->take(10);
    }

    public function past_reservs()
    {
        return $this->HasMany(Reservation::class)->whereIn('reservation_status_id', [3,4]);
    }

    public function reservable_plans()
    {
        return $this->hasMany(PlanUser::class)->whereIn('plan_status_id', [1,3]);
    }

    /**
     * [blocks description]
     * @return [type] [description]
     */
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

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
    * [status_user description]
    * @return [model] [description]
    */
    public function plan_users()
    {
        return $this->hasMany(PlanUser::class)->orderBy('plan_status_id', 'ASC')->orderBy('start_date','desc');
    }

    /**
     * [regular_users description]
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

    public function emergency()
    {
        return $this->hasOne(Emergency::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->using(RoleUser::class);
    }

    public function getAvatarAttribute($value)
    {
      if(!$value)
      {
        return url('img/default_user.png');
      } else {
        return $value;
      }
    }

    public function birthdate_users()
    {
        return User::whereMonth('birthdate', toDay()->month)->whereDay('birthdate', toDay()->day)->get();
    }

    public function itsBirthDay()
    {
        if ($this->birthdate->month == toDay()->month && $this->birthdate->day == toDay()->day) {
            return true;
        }else{
            return false;
        }
    }

}