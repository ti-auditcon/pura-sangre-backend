<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Reports\PlanSummary;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DataPlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reports.data_plans');
    }

    public function compare(Request $request)
    {
        $first_date = Carbon::parse($request->date)->format('Y-m-d');
        
        $second_date = Carbon::parse($request->date)->subMonthWithoutOverflow()->format('Y-m-d');
        // subMonthWithoutOverflow
        
        $data = PlanSummary::where('date', $first_date)
                           ->orWhere('date', $second_date)
                           ->orderByDesc('date')
                           ->get();
        
        $new = $data->map(function ($data) {
            return [
                'day' => ucfirst(Carbon::parse($data->date)->formatLocalized('%A')),
                'date' => Carbon::parse($data->date)->format('d-m-Y'),
                'active_users_day' => $data->active_users_day,
                'reservations_day' => $data->reservations_day,
                'cumulative_reservations' => number_format($data->cumulative_reservations, $decimal = 0, '.', '.'),
                'day_incomes' => '$ ' . number_format($data->day_incomes, $decimal = 0, '.', '.'),
                'cumulative_incomes' => '$ ' . number_format($data->cumulative_incomes, $decimal = 0, '.', '.'),
                'day_plans_sold' => $data->day_plans_sold,
                'cumulative_plans_sold' => $data->cumulative_plans_sold
            ];
        });

        return response()->json(['data' => $new]);
    }
}
