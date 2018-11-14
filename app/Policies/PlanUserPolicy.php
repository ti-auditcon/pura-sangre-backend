<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Traits\AdminActions;
use App\Models\Plans\PlanUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlanUserPolicy
{
    use HandlesAuthorization, AdminActions;

    /**
     * Determine whether the user can view the plan user.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return mixed
     */
    public function view(User $user, PlanUser $planUser)
    {
        return $planUser->user_id->id === $user->id;
    }

    /**
     * Determine whether the user can create plan users.
     *
     * @param  \App\Models\Users\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the plan user.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return mixed
     */
    public function update(User $user, PlanUser $planUser)
    {
        //
    }

    /**
     * Determine whether the user can delete the plan user.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return mixed
     */
    public function delete(User $user, PlanUser $planUser)
    {
        //
    }

    /**
     * Determine whether the user can restore the plan user.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return mixed
     */
    public function restore(User $user, PlanUser $planUser)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the plan user.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Plans\PlanUser  $planUser
     * @return mixed
     */
    public function forceDelete(User $user, PlanUser $planUser)
    {
        //
    }
}
