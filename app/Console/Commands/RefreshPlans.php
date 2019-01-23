<?php

namespace App\Console\Commands;

use App\Models\Plans\PlanUser;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RefreshPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:plans';

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
        $plans = PlanUser::whereIn('plan_status_id' ,[1, 3])->get();
        foreach ($plans as $plan) {
            if (Carbon::parse($plan->finish_date) < today()) {
                $plan->plan_status_id = 4;
                $plan->save();
            }
        }
    }
}
