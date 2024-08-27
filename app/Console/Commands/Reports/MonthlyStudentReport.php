<?php

namespace App\Console\Commands\Reports;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
use App\Services\UserReportService;
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

    private $userReportSevice;

    public function __construct(UserReportService $userReportSevice)
    {
        parent::__construct();

        $this->userReportSevice = $userReportSevice;
    }

    public function handle()
    {
        $start = Carbon::parse('2018-12-01');
        $end = Carbon::parse('2024-06-30');

        while ($start->lte($end)) {
            $this->handleMonth($start->copy()->startOfMonth());

            $start->addMonth();
        }

        // $this->handleMonth(now()->startOfMonth()->subMonths(2));
    }

    public function handleMonth($startPreviousMonth)
    {
        $startPreviousMonth = $startPreviousMonth;
        $endOfPreviousMonth = $startPreviousMonth->copy()->endOfMonth();

        $activeUserStart = $this->userReportSevice->activeUsersAt($startPreviousMonth)->count('users.id');
        $activeUserFinish = $this->userReportSevice->activeUsersAtLastDay($endOfPreviousMonth)->count('users.id');

        $dropouts = User::getDropouts($startPreviousMonth, $endOfPreviousMonth)->count();
        $newStudents = User::newStudentsInDateRange($startPreviousMonth, $endOfPreviousMonth)->count('users.id');
        
        $monthDifference = $activeUserFinish - $activeUserStart;

        ReportModel::create([
            'year'                      => $startPreviousMonth->copy()->format('Y'),
            'month'                     => $startPreviousMonth->copy()->format('m'),
            'active_students_start'     => $activeUserStart,
            'active_students_end'       => $activeUserFinish,
            'dropouts'                  => $dropouts,
            'new_students'              => $newStudents,
            'new_students_percentage'   => $activeUserFinish != 0 ? ($newStudents / $activeUserFinish) * 100 : 0,
            'month_difference'          => $monthDifference,
            'growth_rate'               => $activeUserStart != 0 ? (($activeUserFinish - $activeUserStart) / $activeUserStart) * 100 : 0,
            'retention_rate'            => $activeUserStart != 0 ? (($activeUserFinish - $newStudents) / $activeUserStart) * 100 : 0,
            'churn_rate'                => $activeUserStart != 0 ? ($dropouts / $activeUserStart) * 100 : 0
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

    public function activeUsersAtLastDay(Carbon $date)
    {
        return User::join('plan_user', 'users.id', '=', 'plan_user.user_id')
            ->join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->where('plan_user.start_date', '<=', $date->copy()->endOfDay())
            ->where('plan_user.finish_date', '>=', $date->copy()->startOfDay())
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plans.id', '!=', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'users.avatar', 'users.phone', 'users.rut')
            ->distinct('users.id');
    }
}
