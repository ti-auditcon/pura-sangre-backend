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
        'App\Console\Commands\Plans\FreezePlans',
        'App\Console\Commands\Plans\UnfreezePlans',
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
        $schedule->command('push:clases')->hourlyAt(15);
        $schedule->command('push:clases')->hourlyAt(30);
        $schedule->command('push:clases')->hourlyAt(45);
        
        $schedule->command('clean:clase')->hourly();
        $schedule->command('clean:clase')->hourlyAt(15);
        $schedule->command('clean:clase')->hourlyAt(30);
        $schedule->command('clean:clase')->hourlyAt(45);

        // $schedule->command('push:clases')->everyFifteenMinutes();
        // $schedule->command('clean:clase')->everyFifteenMinutes();
        $schedule->command('closed:clase')->hourlyAt(15);
        $schedule->command('refresh:plans')->daily();
        $schedule->command('create:clases')->weekly();
        $schedule->command('toexpire:plan')->dailyAt('9:10');

        $schedule->command('plans:freeze')->dailyAt('00:10');
        $schedule->command('plans:unfreeze')->dailyAt('00:15');
        // $schedule->command('queue:work')->hourly();
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
