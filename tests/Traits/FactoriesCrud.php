<?php

namespace Tests\Traits;

use App\Models\Users\Role;
use App\Models\Users\User;

trait FactoriesCrud
{
    /**
     *  Create a user with role = admin in database with factory
     *  and return it
     *
     *  @return  \App\Models\Users\User
     */
    public function fakeStudent()
    {
        return $this->createUser();
    }

    /**
     *  Create a user with role = admin in database with factory
     *  and return it
     *
     *  @return  \App\Models\Users\User
     */
    public function fakeAdmin($data = [])
    {
        return $this->createUser(Role::ADMIN, $data);
    }

    /**
     *  Create a user with role = admin in database with factory
     *  and return it
     *
     *  @return  \App\Models\Users\User
     */
    public function fakeCoach()
    {
        return $this->createUser(Role::COACH);
    }

    // /**
    //  *  Create a user with role = admin in database with factory
    //  *  and return it
    //  *
    //  *  @return  \App\Models\Users\User
    //  */
    // public function fakeRecepcionist()
    // {
    //     return $this->createUser(Role::RECEPTIONIST);
    // }

    /**
     *  Create a fake user based on factory and assigned the given role
     *
     *  @param   int  $role
     *
     *  @return  \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function createUser($role = Role::STUDENT, $data = [])
    {
        return User::withoutEvents(function () use ($role, $data) {
            $user = factory(User::class)->create();

            $user->roles()->attach($role);

            return $user;
        });
    }
}
