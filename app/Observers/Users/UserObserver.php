<?php

namespace App\Observers\Users;

use Session;
use Redirect;
use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Support\Str;
use App\Mail\SendNewUserEmail;
use App\Models\Plans\PlanUser;
use App\Models\Users\StatusUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     *  [creating description]
     * 
     *  @param  User   $user [description]
     * 
     *  @return [type]       [description]
     */
    public function creating(User $user)
    {
        $user->status_user_id = !request('test_user') ? StatusUser::INACTIVE : StatusUser::TEST; 

        $user->password = bcrypt('purasangre');

        $user->avatar = url('img/default_user.png');
    }

    /**
     *  Handle the user "created" event.
     *
     *  @param   \App\Models\Users\User  $user
     *
     *  @return  void
     */
    public function created(User $user)
    {
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $user->email, 
            'token' => Hash::make($token),
        ]);

        try {
            Mail::to($user->email)->send(new SendNewUserEmail($user, $token));
        } catch (\Exception $e) {
            Log::error('Hemos tenido el siguiente error: ' . $e);
        }
        
        if ($user->isTest()) {
            $user->assignTestPlan(Plan::find(1));
        }
    }

    /**
     *  Handle the user "deleted" event.
     *
     *  @param   \App\Models\Users\User  $user
     * 
     *  @return  void
     */
    public function deleted(User $user)
    {
        $user->plan_users()->each(function ($plan_user){
            $plan_user->delete();
        });
    }
}
