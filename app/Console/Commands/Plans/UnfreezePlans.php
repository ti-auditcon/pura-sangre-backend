<?php

namespace App\Console\Commands\Plans;

use App\Models\Plans\PlanStatus;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PostponePlan;
use Illuminate\Console\Command;

class UnfreezePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:unfreeze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unfreeze all the plans who has today the unfreeze date';

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
        $yesterday_plans = PostponePlan::whereFinishDate(today()->subDay())
                                       ->pluck('plan_user_id')
                                       ->toArray();

        $plans_to_unfreeze = PlanUser::whereIn('id', array_values($yesterday_plans))
                                     ->get();

        foreach ($plans_to_unfreeze as $plan) {
            
            $plan->update(['plan_status_id' => PlanStatus::ACTIVO]);
        
        }
    }
}
