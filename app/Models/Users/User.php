<?php

namespace App\Models\Users;

use App\Models\Bills\Installment;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanUser;
use App\Models\Users\Emergency;
use App\Models\Users\Millestone;
use App\Models\Users\Role;
use App\Models\Users\RoleUser;
use App\Models\Users\StatusUser;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * [User description]
 */
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
        return $this->belongsToMany('App\Models\Clases\Clase', 'reservations', 'user_id', 'clase_id');
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
    * metodo  para obtener el plan activo del usuario
    * @return [type] [description]
    */
    public function active_plan()
    {
      return $this->belongsToMany(Plan::class)->using(PlanUser::class)->first();
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

    /**
    * [status_user description]
    * @return [model] [description]
    */
    public function plan_users()
    {
      return $this->hasMany(PlanUser::class)->orderBy('finish_date', 'desc');
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
    // 
    // public function coaches()
    // {
    //     return $this->belongsToMany(Role::class)->using(RoleUser::class);
    // }

}

// /**
//  * [active_plan description]
//  * @return [type] [description]
//  */
// public function active_plan()
// {
//     return $this->belongsToMany(Plan::class)->wherePivot('plan_state', 'activo');
// }

/**
* [getRouteKeyName obtener nombre]
* @method getRouteKeyName
* @return string  [allow to search the route by "name", instead of "id"]
*/
// public function getRouteKeyName()
// {
//     return 'name';
// }
    // *
    //  * [esAdministrador description]
    //  * @return [boolean] [description]

    // public function esAdministrador()
    // {
    //   return $this->admin;
    // }
