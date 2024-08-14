<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        $totalData = $studentReports->count('id');
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
