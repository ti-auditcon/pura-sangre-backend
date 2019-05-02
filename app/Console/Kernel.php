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
        'App\Console\Commands\ClosedClass',
        'App\Console\Commands\CreateClases',
        'App\Console\Commands\RefreshPlans',
        'App\Console\Commands\PushClases',
        'App\Console\Commands\ToExpiredPlan',
        'App\Console\Commands\CleanClases',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('push:clases')->hourly();
        $schedule->command('closed:clase')->hourlyAt(15);
        $schedule->command('refresh:plans')->daily();
        $schedule->command('create:clases')->weekly();
        $schedule->command('toexpire:plan')->dailyAt('9:10');
        // $schedule->command('clean:clase')->hourlyAt(15);
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
