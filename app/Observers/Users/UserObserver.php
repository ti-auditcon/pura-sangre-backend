<?php

namespace App\Observers\Users;

use App\Models\Users\User;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Users\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\Models\Users\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\Models\Users\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $user->plan_users()->each(function ($plan_user){
            $plan_user->delete();
        });
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\Models\Users\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\Models\Users\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
