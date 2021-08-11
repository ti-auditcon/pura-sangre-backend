<?php

namespace App\Models\Users;

use App\Models\Users\User;
use App\Models\Users\RoleUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Role extends Model
{
	const ADMIN = 1;

    /**
     *  [users description]
     *
     *  @method users
     *
     *  @return [model] [description]
     */
	public function users()
	{
	    return $this->belongsToMany(User::class, 'role_user');
	}


    /**
     * [coaches description]
     *
     * @return  [type]  [return description]
     */
	public function coaches()
	{
		return $this->belongsToMany(User::class)->using(RoleUser::class);
	}
}
