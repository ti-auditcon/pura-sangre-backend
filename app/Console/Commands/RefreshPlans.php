<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;

class RefreshPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loop over all plan_user and change the status of all plans with expired dates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $plans = PlanUser::where('user_id', $user->id)->whereIn('plan_status_id', [1,3])->get();
            foreach ($plans as $plan) {
                if ($plan->plan_status_id == 1) {
                    if (Carbon::parse($plan->finish_date) < today()) {
                        $plan->plan_status_id = 4;
                        $plan->save();
                    }
                }
                if ($plan->plan_status_id == 3) {
                    if (Carbon::parse($plan->start_date) <= today() && Carbon::parse($plan->finish_date) >= today()) {
                        $plan->plan_status_id = 1;
                        $plan->save();
                    }
                }
            }
            if ($user->actual_plan) {
                if ($user->actual_plan->plan->id == 1) {
                    $user->status_user_id = 3;
                }else{
                    $user->status_user_id = 1;
                }
            }else{
                $user->status_user_id = 2;
            }
            $user->save();
        }
    }
}
