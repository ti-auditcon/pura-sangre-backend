<?php

namespace App\Console\Commands\Reports;

use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Console\Command;
use App\Models\Reports\MonthlyStudentReport as ReportModel;

class MonthlyStudentReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:reports:monthly-students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close data for a month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start = Carbon::parse('2018-01-01');
        $end = Carbon::parse('2024-07-31');

        while ($start->lte($end)) {
            $this->handleMonth($start);

            $start->addMonth();
        }
    }

    public function handleMonth($startPreviousMonth)
    {
        $endOfPreviousMonth = $startPreviousMonth->copy()->endOfMonth();

        $activeUserStart = User::activeInDateRange($startPreviousMonth, $startPreviousMonth->copy()->endOfDay())->count('users.id');
        $activeUserFinish = User::activeInDateRange($endOfPreviousMonth->copy()->startOfDay(), $endOfPreviousMonth)->count('users.id');

        $dropouts = User::dropouts($startPreviousMonth, $endOfPreviousMonth)->count('users.id');
        $newStudents = User::newStudentsInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');
        $turnaround = User::turnaroundInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');

        $previousMonthDifference = $activeUserStart - $activeUserFinish;
        $growthRate = $activeUserStart != 0 ? ($activeUserStart - $activeUserFinish) / $activeUserStart : 0;
        $retentionRate = $activeUserStart != 0 ? ($activeUserStart - $activeUserFinish) / $activeUserStart : 0;
        $rotation = $activeUserStart != 0 ? ($activeUserStart - $activeUserFinish) / $activeUserStart : 0;

        ReportModel::create([
            'year'                      => $startPreviousMonth->copy()->format('Y'),
            'month'                     => $startPreviousMonth->copy()->format('m'),
            'active_students_start'     => $activeUserStart,
            'active_students_end'       => $activeUserFinish,
            'dropouts'                  => $dropouts,
            'new_students'              => $newStudents,
            'dropout_percentage'        => $activeUserStart != 0 ? ($dropouts / $activeUserStart) * 100 : 0,
            'new_students_percentage'   => $activeUserStart != 0 ? ($newStudents / $activeUserStart) * 100 : 0,
            'turnaround'                => $turnaround,
            'previous_month_difference' => $previousMonthDifference,
            'growth_rate'               => $growthRate,
            'retention_rate'            => $retentionRate,
            'rotation'                  => $rotation,
        ]);
    }
}
