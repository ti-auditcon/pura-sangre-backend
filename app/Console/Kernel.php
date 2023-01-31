<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * We define a list of commands that should be available to the CLI by default.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Clases\CloseClass',
        'App\Console\Commands\Clases\ClasesSendPushes',
        'App\Console\Commands\Clases\ClasesClear',
        'App\Console\Commands\Clases\CreateClases',
        // 'App\Console\Commands\Clases\AfterFirstClass',
        'App\Console\Commands\Invoicing\IssueReceiptsCommand',
        'App\Console\Commands\Messages\SendNotifications',
        'App\Console\Commands\Plans\FreezePlans',
        'App\Console\Commands\Plans\UnfreezePlans',
        'App\Console\Commands\Reports\PlanSummaryCommand',
        'App\Console\Commands\RefreshPlans',
        'App\Console\Commands\ToExpiredPlan',
        'App\Console\Commands\Users\UsersGoneAway',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param   \Illuminate\Console\Scheduling\Schedule  $schedule
     *
     * @return  void
     */
    protected function schedule(Schedule $schedule)
    {
        /** Send Push Notifications to users certain minutes before class starts  */
        $schedule->command('purasangre:clases:send-notifications')->everyFifteenMinutes();
        /** Remove users who don't confirm assistance to clases  */
        $schedule->command('purasangre:clases:clear')->everyFifteenMinutes();

        // $schedule->command('clases:close')->hourlyAt(15);
        /** Close all the clases, changing status of the users */
        $schedule->command('clases:close')->everyFiveMinutes();
        $schedule->command('plans:refresh')->daily();
        $schedule->command('clases:create')->weekly();
        $schedule->command('plans:toexpire')->dailyAt('9:10');

        $schedule->command('reports:daily')->dailyAt('23:50');

        $schedule->command('plans:freeze')->dailyAt('00:10');
        $schedule->command('purasangre:plans:unfreeze')->dailyAt('00:15');

        $schedule->command('messages:send-notifications')->everyMinute();

        // $schedule->command('clases:first')->everyFifteenMinutes();

        $schedule->command('users:gone-away-email')->daily();

        /** Issue to SII receipts and send bill receipt to student  */
        $schedule->command('purasangre:invoicing:issue-receipts')->everyFifteenMinutes();

        $schedule->command('purasangre:plans:finish')->hourlyAt(16);
    }

    /**
     * Register the commands for the application.
     *
     * @return  void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
