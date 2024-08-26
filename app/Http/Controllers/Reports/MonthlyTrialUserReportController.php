<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Clases\ReservationStatus;
use App\Models\Reports\MonthlyTrialUserReport;

class MonthlyTrialUserReportController extends Controller
{
    public function filterReports(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $start = $request->input('start');
        $length = $request->input('length');

        $query = MonthlyTrialUserReport::query();

        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $previousMonth = Carbon::now()->subMonth()->month;

        if ($year == $currentYear && !$month) {
            // Retrieve data for previous months from the database
            $studentReports = $query->where('year', $year)
                ->get([
                    'id', 'year', 'month', 'trial_plans', 'trial_classes_consumed', 
                    'trial_classes_consumed_percentage', 'trial_convertion',
                    'trial_convertion_percentage'
                ]);

            // Calculate accumulated data for the previous month
            $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
            $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();
            $previousMonthData = $this->calculateAccumulatedData($startOfPreviousMonth, $endOfPreviousMonth);

            // Calculate accumulated data for the current month up to today
            $startOfCurrentMonth = Carbon::now()->startOfMonth();
            $today = Carbon::now();
            $currentMonthData = $this->calculateAccumulatedData($startOfCurrentMonth, $today);

            // Combine both months' data with existing reports
            $studentReports->push((object) $previousMonthData);
            $studentReports->push((object) $currentMonthData);

            $totalData = $studentReports->count();
            $totalFiltered = $totalData;

            $json_data = [
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data"            => $studentReports
            ];

            return response()->json($json_data);
        } elseif ($year == $currentYear && ($month == $currentMonth || $month == $previousMonth)) {
            // Calculate the accumulated data for the specified month
            if ($month == $previousMonth) {
                $startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
                $endOfPreviousMonth = Carbon::now()->subMonth()->endOfMonth();
                $studentReport = $this->calculateAccumulatedData($startOfPreviousMonth, $endOfPreviousMonth);
            } elseif ($month == $currentMonth) {
                $startOfCurrentMonth = Carbon::now()->startOfMonth();
                $today = Carbon::now();
                $studentReport = $this->calculateAccumulatedData($startOfCurrentMonth, $today);
            }

            $json_data = [
                "draw"            => intval($request->input('draw')),
                "recordsTotal"    => 1, // Only one row for the specified month
                "recordsFiltered" => 1,
                "data"            => [$studentReport] // Wrap the data in an array
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
                    'id', 'year', 'month', 'trial_plans', 'trial_classes_consumed', 
                    'trial_classes_consumed_percentage', 'trial_convertion',
                    'trial_convertion_percentage'
                ]);

            // Calculate total data count and total filtered count
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
        $allTrialPlans = $this->trialPlansAt($startOfMonth, $today);
        $trialClassesConsumed = $this->trialClassesConsumedAt($startOfMonth, $today);
        $trialClassesConsumedPercentage = $trialClassesConsumed != 0 ? ($trialClassesConsumed / $allTrialPlans) * 100 : 0;
        $trialConvertion = $this->trialConvertionAt($startOfMonth, $today);
        $trialConvertionPercentage = $trialClassesConsumed > 0 ? ($trialConvertion / $trialClassesConsumed) * 100 : 0;

        return [
            'year'                              => $startOfMonth->year,
            'month'                             => $today->month,
            'trial_plans'                       => $allTrialPlans,
            'trial_classes_consumed'            => $trialClassesConsumed,
            'trial_classes_consumed_percentage' => round($trialClassesConsumedPercentage, 2),
            'trial_convertion'                  => $trialConvertion,
            'trial_convertion_percentage'       => round($trialConvertionPercentage, 2)
        ];
    }

    public function trialPlansAt($start, $end)
    {
        return PlanUser::join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plans.id', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->select('plan_user.id as id', 'plan_user.user_id', 'plans.id as plan_id')
            ->distinct('plan_user.id')
            ->count('plan_user.id');
    }
    
    public function trialClassesConsumedAt($start, $end)
    {
        // todos los alumnos que tienen un plan de prueba donde la clase se haya consumido en el mes
        return PlanUser::join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->join('reservations', 'reservations.plan_user_id', '=', 'plan_user.id')
            ->join('clases', 'clases.id', '=', 'reservations.clase_id')
            // ->whereBetween('plan_user.start_date', [$start, $end])
            ->whereBetween('clases.date', [$start, $end])
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plans.id', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->where('reservations.reservation_status_id', ReservationStatus::CONSUMED)
            ->select('plan_user.id as id', 'plan_user.user_id', 'plans.id as plan_id')
            ->distinct('plan_user.user_id')
            ->count('plan_user.user_id');  
    }

    /**
     * Gets the number of users who converted to a normal plan after having a trial plan with at least one consumed class
     *
     * @param   Carbon  $start
     * @param   Carbon  $end
     *
     * @return  integer
     */
    public function trialConvertionAt(Carbon $start, Carbon $end)
    {
        return PlanUser::join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->where('plan_user.plan_status_id', '!=', PlanStatus::CANCELED)
            ->where('plans.id', '!=', Plan::TRIAL)
            ->whereBetween('plan_user.start_date', [$start, $end])
            ->whereExists(function ($subQuery) {
                $subQuery->select(DB::raw(1))
                    ->from('plan_user as trialPlan')
                    ->join('reservations', 'reservations.plan_user_id', '=', 'trialPlan.id')
                    ->where('trialPlan.plan_id', Plan::TRIAL)
                    ->whereColumn('trialPlan.user_id', 'plan_user.user_id')
                    ->where('trialPlan.plan_status_id', '!=', PlanStatus::CANCELED)
                    ->whereRaw('trialPlan.finish_date < plan_user.start_date')
                    // where trial.finish_date not be more than 14 days before the start date of plan_user
                    ->whereRaw('trialPlan.finish_date > DATE_SUB(plan_user.start_date, INTERVAL 14 DAY)')
                    ->where('reservations.reservation_status_id', ReservationStatus::CONSUMED)
                    ->whereNull('trialPlan.deleted_at');
            })
            ->whereNull('plan_user.deleted_at')
            ->distinct('plan_user.user_id')
            ->count('plan_user.user_id');
    }
}
