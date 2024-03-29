<?php

namespace App\Console\Commands\Plans;

use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;

class FreezePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:freeze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Freeze all the plans who has today the freeze date';

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
     * @return void
     */
    public function handle()
    {
        $today_plans = PostponePlan::whereStartDate(today())
                                   ->pluck('plan_user_id')
                                   ->toArray();

        $plans_to_freeze = PlanUser::whereIn('id', array_values($today_plans))->get();

        foreach ($plans_to_freeze as $plan) {
            $plan->update(['plan_status_id' => PlanStatus::FROZEN]);
        }
    }
}
