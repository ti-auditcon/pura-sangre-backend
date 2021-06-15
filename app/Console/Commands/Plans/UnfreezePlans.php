<?php

namespace App\Console\Commands\Plans;

use Carbon\Carbon;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PostponePlan;

class UnfreezePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:plans:unfreeze';

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
        $freezedPlansFinishedYesterday = PostponePlan::whereFinishDate(today()->subDay())
                                                        ->where('revoked', false)
                                                        ->get();

        foreach ($freezedPlansFinishedYesterday as  $freezedPlan) {
            $collection = collect($freezedPlan->plan_user->only(
                'start_date', 'finish_date', 'counter', 'plan_status_id', 'plan_id', 'observations'
            ));

            $previous = $freezedPlan->plan_user->history;

            $diff_in_days = Carbon::parse($freezedPlan->finish_date)->diffInDays(today());

            $freezedPlan->plan_user->update([
                'plan_status_id' => PlanStatus::ACTIVO,
                'finish_date'    => Carbon::parse($freezedPlan->plan_user->finish_date)->subDays($diff_in_days),
                'history'        => $previous ? $previous->add($collection) : [$collection]
            ]);
            
            $planes_posteriores = PlanUser::where('user_id', $freezedPlan->plan_user->user_id)
                                            ->where('start_date', '>', $freezedPlan->plan_user->start_date)
                                            ->where('id', '!=', $freezedPlan->plan_user->id)
                                            ->orderBy('finish_date')
                                            ->get(['id', 'start_date', 'finish_date', 'user_id']);

            foreach ($planes_posteriores as $plan) {
                $plan->update([
                    'start_date'  => $plan->start_date->subDays($diff_in_days),
                    'finish_date' => $plan->finish_date->subDays($diff_in_days)
                ]);
            }

            $freezedPlan->revoke();
        }
    }
}
