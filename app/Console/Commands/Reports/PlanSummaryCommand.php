<?php

namespace App\Console\Commands\Reports;

use Carbon\CarbonPeriod;
use App\Models\Bills\Bill;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Clases\Reservation;
use App\Models\Reports\PlanSummary;

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
    protected $description = 'Close data about the plans sold today';

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
        $date = today();

        $active_users_day = PlanUser::where('plan_id', '!=', 1)
                                    ->where('start_date', '<=', $date)
                                    ->where('finish_date', '>=', $date)
                                    ->count('id');
        
        $reservations_day = Reservation::join('clases', 'clases.id', '=', 'reservations.clase_id')
                                       ->where('clases.date', $date->copy()->format('Y-m-d'))
                                       ->count('reservations.id');
        
        $cumulative_reservations = Reservation::join('clases', 'clases.id', '=', 'reservations.clase_id')
                                              ->whereBetween('clases.date', [$date->copy()->startOfMonth(), $date])
                                              ->count('reservations.id');
        
        $day_incomes = Bill::where('date', $date->copy()->format('Y-m-d'))->sum('amount');
        
        $cumulative_incomes = Bill::whereBetween('date', [$date->copy()->startOfMonth(), $date])
                                  ->sum('amount');
        
        $day_plans_sold = Bill::where('date', $date->copy()->format('Y-m-d'))->count('id');
        
        $cumulative_plans_sold = Bill::whereBetween('date', [$date->copy()->startOfMonth(), $date])
                                     ->count('id');

        PlanSummary::create([
            'date' => $date->format('Y-m-d'),
            'active_users_day' => $active_users_day,
            'reservations_day' => $reservations_day,
            'cumulative_reservations' => $cumulative_reservations,
            'day_incomes' => $day_incomes,
            'cumulative_incomes' => $cumulative_incomes,
            'day_plans_sold' => $day_plans_sold,
            'cumulative_plans_sold' => $cumulative_plans_sold
        ]);
    }
}
