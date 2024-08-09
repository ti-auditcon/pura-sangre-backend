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
        $start = Carbon::parse('2018-12-01');
        $end = Carbon::parse('2024-07-31');

        while ($start->lte($end)) {
            $this->handleMonth($start->copy()->startOfMonth());

            $start->addMonth();
        }
    }

    public function handleMonth($startPreviousMonth)
    {
        $startPreviousMonth->copy()->startOfDay();
        $endOfPreviousMonth = $startPreviousMonth->copy()->endOfMonth();

        $activeUserStart = User::activeInDateRange($startPreviousMonth, $startPreviousMonth->copy()->endOfDay())->count('users.id');
        $activeUserFinish = User::activeInDateRange($endOfPreviousMonth->copy()->startOfDay(), $endOfPreviousMonth)->count('users.id');

        $dropouts = User::getDropouts($startPreviousMonth, $endOfPreviousMonth)->count();
        $newStudents = User::newStudentsInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');
        
        // pending (soon), tal vez pueden ser los alumno que el mes pasado se les termino el plan y no contrataron, y este mes si lo hicieron
        // $turnaround = User::turnaroundInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');

        $previousMonthDifference = $activeUserStart - $activeUserFinish;
        $growthRate = $activeUserFinish != 0 ? ($activeUserFinish - $activeUserStart) / $activeUserStart : 0;
        
        $retentionRate = $activeUserFinish != 0 ? (($activeUserFinish - $newStudents) / $activeUserStart) * 100 : 0;

        $churnRate = $activeUserStart != 0 ? ($dropouts / $activeUserStart) * 100 : 0;

        ReportModel::create([
            'year'                      => $startPreviousMonth->copy()->format('Y'),
            'month'                     => $startPreviousMonth->copy()->format('m'),
            'active_students_start'     => $activeUserStart,
            'active_students_end'       => $activeUserFinish,
            'dropouts'                  => $dropouts,
            'dropout_percentage'        => $activeUserFinish != 0 ? ($dropouts / $activeUserFinish) * 100 : 0,
            'new_students'              => $newStudents,
            'new_students_percentage'   => $activeUserStart != 0 ? ($newStudents / $activeUserStart) * 100 : 0,
            // 'turnaround'                => $turnaround,
            'previous_month_difference' => $previousMonthDifference,
            'growth_rate'               => $growthRate,
            'retention_rate'            => $retentionRate,
            'churn_rate'                => $churnRate,
        ]);
    }
}
