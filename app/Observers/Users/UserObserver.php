<?php

namespace App\Observers\Users;

use App\Mail\SendNewUserEmail;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Redirect;
use Session;

class UserObserver
{
    /**
     * [creating description]
     * 
     * @param  User   $user [description]
     * @return [type]       [description]
     */
    public function creating(User $user)
    {
        $user->status_user_id = !request('test_user') ? 2 : 3; 

        $user->password = bcrypt('purasangre');

        $user->avatar = url('img/default_user.png');
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

        try {
            Mail::to($user->email)->send(new SendNewUserEmail($user, $token));
        } catch (Exception $e) {
            Log::error('Hemos tenido el siguiente error: ' . $e);
        }
        
        if ($user->status_user_id == 3) {
            $planuser = new PlanUser;
            $planuser->plan_id = 1;
            $planuser->user_id = $user->id;
            $planuser->counter = 3;
            $planuser->plan_status_id = 1;
            $planuser->start_date = request('since') ?
                                    Carbon::parse(request('since')) :
                                    today();
            $planuser->finish_date = $planuser->start_date->copy()->addDays(7);
            $planuser->save();
        }
    }

    public function createToken($tokens, $user)
    {
        return $tokens->create($user);
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
}
