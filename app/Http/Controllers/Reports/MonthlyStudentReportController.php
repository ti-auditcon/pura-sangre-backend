<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Plans\PlanStatus;
use App\Http\Controllers\Controller;
use App\Models\Reports\MonthlyStudentReport;

class MonthlyStudentReportController extends Controller
{
    public function index()
    {
        return view('reports.students');
    }

    public function filterReports(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $start = $request->input('start');
        $length = $request->input('length');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');

        $query = MonthlyStudentReport::query();

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        if ($year == $currentYear && !$month) {
            // Get all months data from the database for the selected year
            $studentReports = $query->where('year', $year)
                ->get([
                    'id', 'year', 'month', 'active_students_start', 'active_students_end', 
                    'dropouts', 'new_students', 'new_students_percentage', 
                    'month_difference', 'growth_rate', 'retention_rate', 'churn_rate'
                ]);

            // Calculate the accumulated data for the current month up to today
            $startOfMonth = Carbon::now()->startOfMonth();
            $today = Carbon::now();

            $accumulatedData = $this->calculateAccumulatedData($startOfMonth, $today);

            // Append the accumulated data for the current month
            $studentReports->push((object) $accumulatedData);

            $totalData = $studentReports->count();
            $totalFiltered = $totalData;

            $json_data = [
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $studentReports
            ];

            return response()->json($json_data);
        } elseif ($year == $currentYear && $month == $currentMonth) {
            // Calculate the accumulated data for the current month up to today
            $startOfMonth = Carbon::now()->startOfMonth();
            $today = Carbon::now();

            $accumulatedData = $this->calculateAccumulatedData($startOfMonth, $today);

            $json_data = [
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => 1, // Only one row for the current month
                "recordsFiltered" => 1,
                "data"            => [$accumulatedData] // Wrap the accumulated data in an array
            ];

            return response()->json($json_data);
        } else {
            // Normal query for completed months
            $studentReports = $query->when($year, function ($query) use ($year) {
                    return $query->where('year', $year);
                })
                ->when($month, function ($query) use ($month) {
                    return $query->where('month', $month);
                })
                ->offset($start)
                ->limit($length)
                ->get([
                    'id', 'year', 'month', 'active_students_start', 'active_students_end', 
                    'dropouts', 'new_students', 'new_students_percentage', 
                    'month_difference', 'growth_rate', 'retention_rate', 'churn_rate'
                ]);

            $totalData = $query->count('id');
            $totalFiltered = $totalData;

            $json_data = [
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $studentReports
            ];

            return response()->json($json_data);
        }
    }

    protected function calculateAccumulatedData($startOfMonth, $today)
    {
        // Use the logic from your cron job to calculate the data
        $activeUserStart = $this->activeUsersAt($startOfMonth)->count('users.id');
        $activeUserFinish = $this->activeUsersAt($today)->count('users.id');

        $dropouts = User::getDropouts($startOfMonth, $today)->count();
        $newStudents = User::newStudentsInDateRange($startOfMonth, $today)->count('users.id');

        $monthDifference = $activeUserFinish - $activeUserStart;
        $growthRate = $activeUserStart != 0 ? (($activeUserFinish - $activeUserStart) / $activeUserStart) * 100 : 0;

        $retentionRate = $activeUserStart != 0 ? (($activeUserFinish - $newStudents) / $activeUserStart) * 100 : 0;

        $churnRate = $activeUserStart != 0 ? ($dropouts / $activeUserStart) * 100 : 0;

        return [
            'year'                      => $startOfMonth->year,
            'month'                     => $today->month,
            'active_students_start'     => $activeUserStart,
            'active_students_end'       => $activeUserFinish,
            'dropouts'                  => $dropouts,
            'new_students'              => $newStudents,
            'new_students_percentage'   => $activeUserFinish != 0 ? round($newStudents / $activeUserFinish, 2) : 0,
            'month_difference'          => $monthDifference,
            'growth_rate'               => $growthRate,
            'retention_rate'            => round($retentionRate, 2),
            'churn_rate'                => round($churnRate, 2),
        ];
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

    // Use the methods from your cron job to calculate activeUsersAt, getDropouts, and newStudentsInDateRange
}