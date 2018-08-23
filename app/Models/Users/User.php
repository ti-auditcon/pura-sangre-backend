<?php

namespace App\Models\Users;

use App\Models\Users\Emergency;
use App\Models\Users\Millestone;
use App\Models\Users\StatusUser;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Reservations\Reservation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * [User description]
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    /**
     * [getRouteKeyName obtener nombre]
     * @method getRouteKeyName
     * @return string  [allow to search the route by "name", instead of "id"]
     */
    // public function getRouteKeyName()
    // {
    //     return 'name';
    // }

    protected $fillable = ['name', 'email', 'password', 'emergency_id', 'status_user_id'];
    protected $hidden = ['password', 'remember_token'];
    protected $dates = ['deleted_at'];

    /**
     * [emergency description]
     * @method emergency
     * @return [Model]    [description]
     */
    public function emergency()
    {
      return $this->belongsTo(Emergency::class);
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
    * [reservations description]
    * @method reservations
    * @return [Model]       [description]
    */
    public function reservations()
    {
      return $this->hasMany(Reservation::class);
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

}
