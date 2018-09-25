<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * [UserPolicy description]
 */
class UserPolicy
{
    /**
     * [use description]
     * @var [type]
     */
    use HandlesAuthorization, AdminActions;

    /**
     * [view description]
     * @param  User   $authenticatedUser [description]
     * @param  User   $user              [description]
     * @return [type]                    [description]
     */
    public function view(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id;
    }

    /**
     * [update description]
     * @param  User   $authenticatedUser [description]
     * @param  User   $user              [description]
     * @return [type]                    [description]
     */
    public function update(User $authenticatedUser, User $user)
    {
        return $authenticatedUser->id === $user->id;
    }
}
