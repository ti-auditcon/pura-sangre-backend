<?php

namespace App\Models\Users;

use App\Models\Users\User;
use App\Models\Users\RoleUser;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     *  List of roles into system
     *
     *  @var  int
     */
	const ADMIN = 1;
	const COACH = 2;
	const STUDENT = 3;

    /**
     *  The users that belong to the Role
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function users()
	{
	    return $this->belongsToMany(User::class, 'role_user');
	}

    /**
     *  The coaches that belong to the Role
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function coaches()
	{
		return $this->belongsToMany(User::class)->using(RoleUser::class);
	}
}
