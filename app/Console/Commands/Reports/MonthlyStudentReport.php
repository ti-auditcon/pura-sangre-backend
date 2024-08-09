<?php

namespace App\Console\Commands\Reports;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
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

        $activeUserStart = $this->activeUsersAt($startPreviousMonth)->count('users.id');
        // $activeUserStart = User::activeInDateRange($startPreviousMonth, $startPreviousMonth->copy()->endOfDay())->count('users.id');
        $activeUserFinish = $this->activeUsersAt($endOfPreviousMonth)->count('users.id');
        // $activeUserFinish = User::activeInDateRange($endOfPreviousMonth->copy()->startOfDay(), $endOfPreviousMonth)->count('users.id');

        $dropouts = User::getDropouts($startPreviousMonth, $endOfPreviousMonth)->count();
        $newStudents = User::newStudentsInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');
        
        // pending (soon), tal vez pueden ser los alumno que el mes pasado se les termino el plan y no contrataron, y este mes si lo hicieron
        // $turnaround = User::turnaroundInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');

        $previousMonthDifference = $activeUserStart - $activeUserFinish;
        $growthRate = $activeUserStart != 0 ? (($activeUserFinish - $activeUserStart) / $activeUserStart) * 100 : 0;
        
        $retentionRate = $activeUserStart != 0 ? (($activeUserFinish - $newStudents) / $activeUserStart) * 100 : 0;

        $churnRate = $activeUserStart != 0 ? ($dropouts / $activeUserStart) * 100 : 0;

        ReportModel::create([
            'year'                      => $startPreviousMonth->copy()->format('Y'),
            'month'                     => $startPreviousMonth->copy()->format('m'),
            'active_students_start'     => $activeUserStart,
            'active_students_end'       => $activeUserFinish,
            'dropouts'                  => $dropouts,
            'dropout_percentage'        => $activeUserStart != 0 ? ($dropouts / $activeUserStart) * 100 : 0,
            'new_students'              => $newStudents, 
            'new_students_percentage' => $activeUserFinish != 0 ? ($newStudents / $activeUserFinish) * 100 : 0,
            'previous_month_difference' => $previousMonthDifference,
            'growth_rate'               => $growthRate,
            'retention_rate'            => $retentionRate,
            'churn_rate'                => $churnRate,
        ]);
    }

    public function activeUsersAt(Carbon $date)
    {
        return User::join('plan_user', 'users.id', '=', 'plan_user.user_id')
            ->join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->where('plan_user.start_date', '<=', $date)
            ->where('plan_user.finish_date', '>=', $date)
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plans.id', '!=', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'users.avatar', 'users.phone', 'users.rut')
            ->distinct('users.id');
    }
}
