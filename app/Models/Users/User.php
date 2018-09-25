<?php

namespace App\Models\Users;

use App\Models\Plans\Plan;
use App\Models\Clases\Clase;
use App\Models\Plans\PlanUser;
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

/**
 * [User description]
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * [USUARIO_ADMINISTRADOR description]
     * @var string
     */
    const USUARIO_ADMINISTRADOR = 'true';

    /**
     * [USUARIO_REGULAR description]
     * @var string
     */
    const USUARIO_REGULAR = 'false';

    protected $fillable = [
      'rut', 'first_name', 'last_name',
      'birthdate', 'gender', 'email',
      'address', 'password', 'phone',
      'emergency_id', 'status_user_id', 'admin'
    ];

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

    /**
     * [setRutAttribute description]
     * @param [type] $value [description]
     */
    public function setRutAttribute($value)
    {
      $this->attributes['rut'] = Rut::parse($value)->number();
    }

    /**
     * [esAdministrador description]
     * @return [boolean] [description]
     */
    public function esAdministrador()
    {
      return $this->admin = User::USUARIO_ADMINISTRADOR;
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
    * metodo  para obtener el plan activo del usuario
    * @return [type] [description]
    */
    public function active_plan()
    {
      return $this->belongsToMany(Plan::class)->using(PlanUser::class)->first();
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
    * [reservations description]
    * @method reservations
    * @return [Model]       [description]
    */
    public function reservations()
    {
      return $this->hasMany(Reservation::class);
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
     * [regular_users description]
     * @return [collection] [description]
     */
    public function regular_users()
    {
      return User::all()->where('admin', 'false')->orderBy('name');
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
     * [getFullNameAttribute description]
     * @return [type] [description]
     */
    public function getFullNameAttribute()
    {
      return $this->first_name.' '.$this->last_name;
    }
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
