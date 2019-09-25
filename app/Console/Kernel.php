<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Clases\CloseClass',
        'App\Console\Commands\Clases\PushClases',
        'App\Console\Commands\Clases\ClearClases',
        'App\Console\Commands\Clases\CreateClases',
        // 'App\Console\Commands\Clases\AfterFirstClass',
        'App\Console\Commands\Plans\FreezePlans',
        'App\Console\Commands\Plans\UnfreezePlans',
        'App\Console\Commands\Reports\PlanSummaryCommand',
        'App\Console\Commands\RefreshPlans',
        'App\Console\Commands\ToExpiredPlan',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('clases:push')->hourlyAt(0);
        $schedule->command('clases:push')->hourlyAt(15);
        $schedule->command('clases:push')->hourlyAt(30);
        $schedule->command('clases:push')->hourlyAt(45);
        $schedule->command('clases:clear')->hourlyAt(0);

        $schedule->command('clases:clear')->hourlyAt(15);
        $schedule->command('clases:clear')->hourlyAt(30);
        $schedule->command('clases:clear')->hourlyAt(45);
        
        $schedule->command('clases:close')->hourlyAt(15);
        $schedule->command('plans:refresh')->daily();
        $schedule->command('clases:create')->weekly();
        $schedule->command('plans:toexpire')->dailyAt('9:10');

        $schedule->command('reports:daily')->dailyAt('23:50');

        $schedule->command('plans:freeze')->dailyAt('00:10');
        $schedule->command('plans:unfreeze')->dailyAt('00:15');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
