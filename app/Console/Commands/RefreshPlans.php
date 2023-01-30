<?php

namespace App\Console\Commands;

use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;

class RefreshPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:plans:refresh';

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
        $plans = PlanUser::whereIn('plan_status_id', [PlanStatus::ACTIVE, PlanStatus::PRE_PURCHASE])
                        ->with('user')
                        ->get();

        $this->info('Number of plans to update: ' . $plans->count());

        if (boolval($plans->count())) {
            foreach ($plans as $plan) {
                if ($plan->isActive() && $plan->finishesBeforeToday()) {
                    $plan->finish();
                } elseif ($plan->isCurrent()) {
                    $plan->activate();
                }
            }
        }
    }
}
