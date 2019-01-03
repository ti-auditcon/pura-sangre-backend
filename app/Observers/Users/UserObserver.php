<?php

namespace App\Observers\Users;

use Session;
use Redirect;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Mail\SendNewUserEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class UserObserver
{
    public function retrieved(User $user)
    {
        if($user->status_user_id == 1 || $user->status_user_id == 3) {
            if(!$user->actual_plan) {
                $user->status_user_id = 2;
                $user->save();
            }
        }
    }

    public function creating(User $user)
    {
        //
    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\Models\Users\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // Mail::to($user->email)->send(new SendNewUserEmail($user));
        // Password::sendResetLink(['email' => $user->email]);

        if ($user->status_user_id == 3) {
            $planuser = new PlanUser;
            $planuser->plan_id = 1;
            $planuser->user_id = $user->id;
            $planuser->counter = 3;
            $planuser->plan_status_id = 1;
            $planuser->start_date = today();
            $planuser->finish_date = today()->addDays(7);
            $planuser->save();
        }
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
