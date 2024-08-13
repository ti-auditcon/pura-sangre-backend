<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
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

        $studentReports = $query->when($year, function ($query) use ($year) {
                return $query->where('year', $year);
            })
            ->when($month, function ($query) use ($month) {
                return $query->where('month', $month);
            })
            // ->orderBy($order, $dir)
            ->offset($start)
            ->limit($length)
            ->get(['id', 'year', 'month', 'active_students_start', 'active_students_end', 'dropouts', 'dropout_percentage', 'new_students', 'new_students_percentage', 'turnaround', 'month_difference', 'growth_rate', 'retention_rate', 'churn_rate']);

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
