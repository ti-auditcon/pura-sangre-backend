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
     * @var  string
     */
    protected $signature = 'purasangre:plans:unfreeze';

    /**
     * The console command description.
     *
     * @var  string
     */
    protected $description = 'Unfreeze all the plans who has today the unfreeze date';

    /**
     * Create a new command instance.
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return  mixed
     */
    public function handle()
    {
        $freezedPlansFinishedYesterday = PostponePlan::whereFinishDate(today()->subDay())
                                                        ->where('revoked', false)
                                                        ->get();

        // getting the dispatcher instance (needed to enable again the event observer later on)
        $dispatcher = PlanUser::getEventDispatcher();
        PlanUser::unsetEventDispatcher();

        foreach ($freezedPlansFinishedYesterday as  $freezedPlan) {

            $this->info('Iterando plan: ' . $freezedPlan->plan_user_id);

            $collection = collect($freezedPlan->plan_user->only(
                'start_date', 'finish_date', 'counter', 'plan_status_id', 'plan_id', 'observations'
            ));

            $previous = $freezedPlan->plan_user->history;

            $freezedPlan->plan_user->update([
                'plan_status_id' => PlanStatus::ACTIVO,
                'finish_date'    => today()->addDays($freezedPlan->days - 1), // restamos un día que es today()
                'history'        => $previous ? $previous->add($collection) : [$collection]
            ]);

            $planes_posteriores = PlanUser::where('user_id', $freezedPlan->plan_user->user_id)
                                            ->where('start_date', '>', $freezedPlan->plan_user->start_date)
                                            ->where('id', '!=', $freezedPlan->plan_user->id)
                                            ->orderBy('finish_date')
                                            ->get(['id', 'start_date', 'finish_date', 'user_id']);

            /**
             * 
             * si el próximo plan comienza despues del actual, las fechas se mueven hacia atras,
             * si el próximo plan comienza antes de que termine el actual, las fechas se mueven hacia adelante
             * 
             */

            //  Calcula los días de diferencia entre el término del unfreezed plan y el más cercano
            //  el -1 es por el today() que cuenta el día de hoy como uno
            $diff_in_days = $this->getDiffInDays(today()->addDays($freezedPlan->days - 1), $planes_posteriores);

            $this->info("The difference in days is: {$diff_in_days}");

            foreach ($planes_posteriores as $plan) {
                $plan->update([
                    'start_date'  => $plan->start_date->addDays($diff_in_days),
                    'finish_date' => $plan->finish_date->addDays($diff_in_days)
                ]);
            }

            $freezedPlan->revoke();
        }

        PlanUser::setEventDispatcher($dispatcher);
    }

    /**
     * Get the difference, in days, between the current active plan and the new closest plan.
     *
     * @param   Carbon      $finishDateUnfreezedPlan
     * @param   Collection  $planes_posteriores     
     *
     * @return  int
     */
    public function getDiffInDays($finishDateUnfreezedPlan, $planes_posteriores): int
    {
        if ($planes_posteriores->first()) {
            $startDateNexPlan = $planes_posteriores->first()->start_date;
            if ($finishDateUnfreezedPlan >= $startDateNexPlan) {
                return $finishDateUnfreezedPlan->diffInDays($startDateNexPlan) + 1;
            }

            if ($finishDateUnfreezedPlan <= $startDateNexPlan) {
                // return a negative number
                return ($finishDateUnfreezedPlan->diffInDays($startDateNexPlan ) -1) * -1;
            }
        }
    }
}
