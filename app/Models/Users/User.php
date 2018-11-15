<?php

namespace App\Models\Users;

use App\Models\Users\Role;
use App\Models\Plans\Plan;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
use App\Models\Users\RoleUser;
use App\Models\Users\Emergency;
use Freshwork\ChileanBundle\Rut;
use App\Models\Users\Millestone;
use App\Models\Users\StatusUser;
use App\Models\Bills\Installment;
use App\Models\Clases\Reservation;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $fillable = [
      'rut', 'first_name', 'last_name',
      'birthdate', 'gender', 'email',
      'address', 'password', 'phone',
      'emergency_id', 'status_user_id'];

    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['deleted_at'];
    protected $appends = ['full_name'];

    // /**
    //  * [getRutAttribute description]
    //  * @param  [type] $value [description]
    //  * @return [type]        [description]
    //  */
    // public function getRutAttribute($value)
    // {
    //   return Rut::set($value)->fix()->format();
    // }

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
        return $this->belongsToMany(Clase::Class)->using(Reservation::class);
    }

    /**
     * [emergency description]
     * @method emergency
     * @return [Model]    [description]
     */
    public function emergency()
    {
        return $this->belongsTo(Emergency::class)->withDefault();
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
    * [millestones description]
    * @method millestones
    * @return [Model]      [description]
    */
    public function millestones()
    {
        return $this->belongsToMany(Millestone::class);
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
        return $this->hasOne(PlanUser::class)->where('plan_status_id', 1)->where('start_date','<=', today())->where('finish_date','>=', today());
    }

    public function active_plan()
    {
        $plan = PlanUser::where('plan_status_id', 1)
                        ->where('user_id', $this->id)
                        ->where('start_date','<=', today())
                        ->where('finish_date','>=', today())
                        ->first();
        // dd($plan);
        if ($plan != null) {
            return $this->hasOne(PlanUser::class)->where('id', $plan->id);
        }
    }

    public function reservable_plans()
    {
      return $this->hasMany(PlanUser::class)->where('plan_status_id',[1,3]);
    }

    public function canReserve(Clase $clase)
    {

    }
    /**
     * [active_users description]
     * @return [type] [description]
     */
    public function active_users()
    {
        return $this->where('status_user_id', 1);
    }

    /**
     * [blocks description]
     * @return [type] [description]
     */
    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    /**
     * [installments description]
     * @return [type] [description]
     */
    public function installments()
    {
        return $this->hasManyThrough(
            Installment::class,
            PlanUser::class,
            'user_id',
            'plan_user_id'
        );
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
        return $this->hasMany(PlanUser::class);
    }

    /**
     * [regular_users description]
     * @return [collection] [description]
     */
    public function regular_users()
    {
        return User::all()->where('admin', 'false')->orderBy('name');
    }

    /**
    * [reservations description]
    * @method reservations
    * @return [Model]       [description]
    */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->using(RoleUser::class);
    }

    // public function coaches()
    // {
    //     return $this->belongsToMany(Role::class)->using(RoleUser::class);
    // }
}
