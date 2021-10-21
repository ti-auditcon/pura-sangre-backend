<?php

namespace App\Models\Users;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    /**
     *  Name of the table in the database
     *
     *  @var  string
     */
    protected $table = 'role_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'user_id'];

    /**
     * [role description]
     *
     * @return  [type]  [return description]
     */
	public function role()
	{
		return $this->belongsTo(Role::class);
	}

    /**
     * [user description]
     *
     * @return  [type]  [return description]
     */
    public function user()
	{
		return $this->belongsTo(User::class);
	}
}
