<?php

namespace App\Policies;

use App\Models\Users\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\HandlesAuthorization;

/** [UserPolicy description] */
class UserPolicy
{
    /**
     * [use "AdminActions" es para que se ejecute antesa que esta clase,
     * y si es admin no considera estas políticas.]
     * @var [type]
     */
    use HandlesAuthorization, AdminActions;

    /**
     * [view description]
     * @param  User   $authenticatedUser [description]
     * @param  User   $user             [description]
     * @return [type]                    [description]
     */
    public function view(User $authenticatedUser, User $user)
    {
      return $authenticatedUser->id === $user->id;
    }

    /**
     * [update description]
     * @param  User   $authenticatedUser [description]
     * @param  User   $user             [description]
     * @return [type]                    [description]
     */
    public function update(User $authenticatedUser, User $user)
    {
      return $authenticatedUser->id === $user->id;
    }

    // /**
    //  * [delete description]
    //  * @param  User   $authenticatedUser [description]
    //  * @param  User   $user             [description]
    //  * @return [type]                    [description]
    //  */
    // public function delete(User $authenticatedUser, User $user)
    // {
    //   return $authenticatedUser->id === $user->id;
    // }
    //
    // /**
    //  * [restore description]
    //  * @param  User   $authenticatedUser [description]
    //  * @param  User   $user              [description]
    //  * @return [type]                    [description]
    //  */
    // public function restore(User $authenticatedUser, User $user)
    // {
    //   return $authenticatedUser->id === $user->id;
    // }

    // /**
    //  * Determine whether the user can permanently delete the model.
    //  *
    //  * @param  \App\Models\Users\User  $user
    //  * @param  \App\Models\Users\User  user
    //  * @return mixed
    //  */
    // public function forceDelete(User $user, User user)
    // {
    //     //
    // }
}
