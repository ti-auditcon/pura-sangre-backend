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
        'App\Console\Commands\ToExpirePlanMail',
        'App\Console\Commands\Users\UsersGoneAway',
        'App\Console\Commands\Reports\MonthlyTrialUserReport',
        'App\Console\Commands\Reports\MonthlyStudentReport',
        'App\Console\Commands\Downloads\CleanOldDownloads',
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
        /** Close all the clases, changing the status of the users */
        $schedule->command('purasangre:clases:close')->everyFiveMinutes();

        $schedule->command('purasangre:plans:refresh')->daily();
        $schedule->command('clases:create')->weekly();
        $schedule->command('purasangre:mails:plans-to-expire')->dailyAt('9:10');

        $schedule->command('reports:daily')->dailyAt('00:05');

        $schedule->command('plans:freeze')->dailyAt('00:10');
        $schedule->command('purasangre:plans:unfreeze')->dailyAt('00:15');

        $schedule->command('messages:send-notifications')->everyMinute();

        // $schedule->command('clases:first')->everyFifteenMinutes();

        $schedule->command('users:gone-away-email')->daily();

        /** Issue to SII receipts and send bill receipt to student  */
        $schedule->command('purasangre:invoicing:issue-receipts')->everyFifteenMinutes();

        $schedule->command('purasangre:plans:finish')->hourlyAt(16);

        $schedule->command('purasangre:reports:monthly-trial-users')->monthlyOn(1, '1');

        $schedule->command('purasangre:reports:monthly-students')->monthlyOn(1, '1');

        $schedule->command('purasangre:downloads:clean')->twiceMonthly();
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
