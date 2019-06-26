<?php

namespace App\Console\Commands\Plans;

use App\Models\Plans\PlanUser;
use App\Models\Plans\PostponePlan;
use Illuminate\Console\Command;

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
     * @return mixed
     */
    public function handle()
    {
        $today_plans = PostponePlan::whereStartDate(today())->pluck('plan_user_id')->toArray();

        $plans = PlanUser::whereIn('id', array_values($today_plans))
                         ->update(['plan_status_id' => PlanStatus::INACTIVO]);
    }
}
