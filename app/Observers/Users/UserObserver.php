<?php

namespace App\Observers\Users;

use App\Mail\SendNewUserEmail;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Session;

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
        $token = str_random(64);
        DB::table('password_resets')->insert([
            'email' => $user->email, 
            'token' => Hash::make($token),
        ]);
        Mail::to($user->email)->send(new SendNewUserEmail($user, $token));

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

    public function createToken($tokens, $user)
    {
        return $tokens->create($user);
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
