<?php

namespace App\Console\Commands\Reports;

use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
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
    protected $description = 'Close data about the plans sold yesterday';

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
        $date = today()->subDay();

        $usersActiveOnDate = PlanUser::where('plan_id', '!=', Plan::TRIAL)
                                    ->where('plan_status_id', '!=', PlanStatus::CANCELED)
                                    ->where('start_date', '<=', $date->copy()->format('Y-m-d 23:59:59'))
                                    ->where('finish_date', '>=', $date->copy()->format('Y-m-d 00:00:00'))
                                    ->count('id');
        
        $reservationsForDate = Reservation::join('clases', 'clases.id', '=', 'reservations.clase_id')
                                       ->whereBetween(
                                            'clases.date', 
                                            [$date->copy()->format('Y-m-d 00:00:00'), $date->copy()->format('Y-m-d 23:59:59')]
                                        )
                                       ->count('reservations.id');
        
        $cumulative_reservations = Reservation::join('clases', 'clases.id', '=', 'reservations.clase_id')
                                              ->whereBetween(
                                                'clases.date', 
                                                [$date->copy()->startOfMonth(), $date->copy()->format('Y-m-d 23:59:59')]
                                            )
                                              ->count('reservations.id');
        
        $day_incomes = Bill::where('date', $date->copy()->format('Y-m-d'))->sum('amount');
        
        $cumulative_incomes = Bill::whereBetween('date', [$date->copy()->startOfMonth(), $date])
                                  ->sum('amount');
        
        $day_plans_sold = Bill::where('date', $date->copy()->format('Y-m-d'))->count('id');
        
        $cumulative_plans_sold = Bill::whereBetween('date', [$date->copy()->startOfMonth(), $date])
                                     ->count('id');

        PlanSummary::create([
            'date'                    => $date->format('Y-m-d'),
            'active_users_day'        => $usersActiveOnDate,
            'reservations_day'        => $reservationsForDate,
            'cumulative_reservations' => $cumulative_reservations,
            'day_incomes'             => $day_incomes,
            'cumulative_incomes'      => $cumulative_incomes,
            'day_plans_sold'          => $day_plans_sold,
            'cumulative_plans_sold'   => $cumulative_plans_sold
        ]);
    }
}
