<?php

namespace App\Console\Commands\Reports;

use App\Models\Clases\Reservation;
use App\Models\Plans\PlanUser;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class PlanSummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $period = CarbonPeriod::create('2019-01-01', '1 days', today());

        foreach ($period as $date) {
            $active_users_day = PlanUser::where('plan_id', '!=', 1)
                                        ->where('start_date', '<=', $date)
                                        ->where('finish_date', '>=', $date)
                                        ->count('id');
            
            $reservations_day = Reservation::join('clases', 'clases.id', '=', 'reservations.id')
                                           ->where('clases.date', $date)
                                           ->count('id');
            
            $cumulative_reservations = Reservation::join('clases', 'clases.id', '=', 'reservations.id')
                                                  ->whereBetween('clases.date', [$date->startOfMonth(), $date])
                                                  ->count('id');
            
            $day_incomes = Bill::where('date', $date)
                               ->sum('amount');
            
            $cumulative_incomes = Reservation::join('clases', 'clases.id', '=', 'reservations.id')
                                                  ->whereBetween('clases.date', [$date->startOfMonth(), $date])
                                                  ->count('id');
            
            $days_plans_sold = ;
            
            $cumulative_plans_sol = ;
        }
    }
}
