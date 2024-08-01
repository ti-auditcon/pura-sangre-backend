<?php

namespace App\Console\Commands\Reports;

use App\Models\Users\User;
use Illuminate\Console\Command;
use App\Models\Reports\MonthlyStudentReport as ReportModel;

class MonthlyStudentReport extends Command
{
    protected $signature = 'purasangre:reports:monthly-students';
    protected $description = 'Close data for a month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $startPreviousMonth = now()->subMonth()->startOfMonth();
        $endOfPreviousMonth = now()->subMonth()->endOfMonth();

        $activeUserStart = User::activeInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');
        $activeUserFinish = User::finishedInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');
        $dropouts = User::dropouts($endOfPreviousMonth)->count('users.id');
        $newStudents = User::newStudentsInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');
        $turnaround = User::turnaroundInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');

        $previousMonthDifference = $activeUserStart - $activeUserFinish;
        $growthRate = $activeUserStart != 0 ? ($activeUserStart - $activeUserFinish) / $activeUserStart : 0;
        $retentionRate = $activeUserStart != 0 ? ($activeUserStart - $activeUserFinish) / $activeUserStart : 0;
        $rotation = $activeUserStart != 0 ? ($activeUserStart - $activeUserFinish) / $activeUserStart : 0;

        ReportModel::create([
            'year'                      => now()->format('Y'),
            'month'                     => now()->format('m'),
            'active_users_day'          => $activeUserStart,
            'active_users'              => $activeUserStart,
            'inactive_users'            => $activeUserFinish,
            'dropouts'                  => $dropouts,
            'dropout_percentage'        => $activeUserStart != 0 ? ($dropouts / $activeUserStart) * 100 : 0,
            'new_students'              => $newStudents,
            'new_students_percentage'   => $activeUserStart != 0 ? ($newStudents / $activeUserStart) * 100 : 0,
            'turnaround'                => $turnaround,
            'previous_month_difference' => $previousMonthDifference,
            'growth_rate'               => $growthRate,
            'retention_rate'            => $retentionRate,
            'rotation'                  => $rotation,
        ]);
    }
}
