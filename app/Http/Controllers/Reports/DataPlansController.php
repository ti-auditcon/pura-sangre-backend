<?php

namespace App\Http\Controllers\Reports;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use Illuminate\Http\Request;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Clases\Reservation;
use App\Models\Reports\PlanSummary;
use App\Http\Controllers\Controller;

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
        $first_date = Carbon::parse($request->first_date)->format('Y-m-d');
        
        $second_date = Carbon::parse($request->second_date)->format('Y-m-d');
        
        // dump($first_date, $second_date);
        $data = PlanSummary::where('date', $first_date)
                           ->orWhere('date', $second_date)
                           ->orderByDesc('date')
                           ->get();
        
        $new = $data->map(function ($data) {
            return [
                'day'                     => ucfirst(Carbon::parse($data->date)->isoFormat('dddd')),
                'date'                    => Carbon::parse($data->date)->format('d-m-Y'),
                'active_users_day'        => $data->active_users_day,
                'reservations_day'        => $data->reservations_day,
                'cumulative_reservations' => number_format($data->cumulative_reservations, $decimal = 0, '.', '.'),
                'day_incomes'             => '$ ' . number_format($data->day_incomes, $decimal = 0, '.', '.'),
                'cumulative_incomes'      => '$ ' . number_format($data->cumulative_incomes, $decimal = 0, '.', '.'),
                'day_plans_sold'          => $data->day_plans_sold,
                'cumulative_plans_sold'   => $data->cumulative_plans_sold
            ];
        });

        return response()->json(['data' => $new]);
    }
    
    public function add(Request $request)
    {
        $date = Carbon::parse($request->date);

        if ($date->gte(today())) {
            return response()->json(['error' => 'La fecha debe ser anterior a hoy.']);
        }

        $usersActiveOnDate = PlanUser::where('plan_id', '!=', Plan::TRIAL)
                            ->where('plan_status_id', '!=', PlanStatus::CANCELED)
                            ->where('start_date', '<=', $date->copy()->format('Y-m-d 23:59:59'))
                            ->where('finish_date', '>=', $date->copy()->format('Y-m-d 00:00:00'))
                            ->count('id');
        
        $reservationsForDate = Reservation::join('clases', 'clases.id', '=', 'reservations.clase_id')
                                       ->whereBetween(
                                            'clases.date', 
                                            [$date->copy()->format('Y-m-d 00:00:00'), $date->copy()->format('Y-m-d 23:59:59')]
                                        )
                                       ->count('reservations.id');
        
        $cumulative_reservations = Reservation::join('clases', 'clases.id', '=', 'reservations.clase_id')
                                              ->whereBetween(
                                                'clases.date', 
                                                [$date->copy()->startOfMonth(), $date->copy()->format('Y-m-d 23:59:59')]
                                            )
                                              ->count('reservations.id');
        
        $day_incomes = Bill::where('date', $date->copy()->format('Y-m-d'))->sum('amount');
        
        $cumulative_incomes = Bill::whereBetween('date', [$date->copy()->startOfMonth(), $date])
                                  ->sum('amount');
        
        $day_plans_sold = Bill::where('date', $date->copy()->format('Y-m-d'))->count('id');
        
        $cumulative_plans_sold = Bill::whereBetween('date', [$date->copy()->startOfMonth(), $date])
                                     ->count('id');
        
        $data = PlanSummary::where('date', $date->format('Y-m-d'))->first();
        
        if ($data) {
            $data->active_users_day = $usersActiveOnDate;
            $data->reservations_day = $reservationsForDate;
            $data->cumulative_reservations = $cumulative_reservations;
            $data->day_incomes = $day_incomes;
            $data->cumulative_incomes = $cumulative_incomes;
            $data->day_plans_sold = $day_plans_sold;
            $data->cumulative_plans_sold = $cumulative_plans_sold;
            $data->save();
        } else {
            $data = new PlanSummary();
            $data->date = $date;
            $data->active_users_day = $usersActiveOnDate;
            $data->reservations_day = $reservationsForDate;
            $data->cumulative_reservations = $cumulative_reservations;
            $data->day_incomes = $day_incomes;
            $data->cumulative_incomes = $cumulative_incomes;
            $data->day_plans_sold = $day_plans_sold;
            $data->cumulative_plans_sold = $cumulative_plans_sold;
            $data->save();
        }

        return response()->json(['data' => $data]);
    }
}
