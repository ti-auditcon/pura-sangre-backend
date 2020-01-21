<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Bills\Bill;
use App\Models\Clases\Reservation;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanIncomeSummary;
use App\Models\Plans\PlanUser;

class ReportController extends Controller
{
    public $months = [
        "Enero", "Febrero", "Marzo",
        "Abril", "Mayo", "Junio", "Julio", "Agosto",
        "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ];

    /**
     * Get all plans
     * @return Collection
     */
    public function plans()
    {
        $plans = Plan::all(['id', 'plan']);

        return $plans;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    
        return view('reports.index');
    }

    /**
     * [firstchart description]
     * 
     * @return [type] [description]
     */
    public function firstchart()
    {
        $data = Bill::selectRaw('SUM(amount) as total, YEAR(DATE) as year, MONTH(date) as month')
                    ->whereYear('date', today()->year)
                    ->orWhereYear('date', today()->subYear()->year)
                    ->groupBy(\DB::raw('YEAR(date)'))
                    ->groupBy(\DB::raw('MONTH(date)'))
                    ->get();

        return response()->json([
            'annual'     => $data->where('year', today()->year)->pluck('total'),
            'sub_annual' => $data->where('year', today()->subYear()->year)->pluck('total'),
            'months'     => $this->months
        ]);
    }

    /**
     * [secondchart description]
     * 
     * @return [type] [description]
     */
    public function secondchart()
    {
        list($q_anual, $q_sub_anual) = $this->totalPlansYearByMonth();

        return response()->json([
            'q_anual' => $q_anual,
            'q_sub_anual' => $q_sub_anual,
            'months' => $this->months
        ]);
    }

    /**
     * [thirdchart description]
     * 
     * @return [type] [description]
     */
    public function thirdchart()
    {
        $rsrvs_anual = $this->quantityAnualReservationsByMonth();

        $rsrvs_sub_anual = $this->quantitySubAnualReservationsByMonth();

        return response()->json([
            'rsrvs_anual' => $rsrvs_anual,
            'rsrvs_sub_anual' => $rsrvs_sub_anual,
            'months' => $this->months
        ]);
    }

    /**
     * Get the quantity of the plans sorted by plan type around the year (monthly),
     * get from bills 
     * 
     * @return [type] [description]
     */
    public function quantityTypePlansByMonth()
    {
        $data = Bill::join('plan_user', 'bills.plan_user_id', '=', 'plan_user.id')
                    ->join('plans', 'plan_user.plan_id', '=', 'plans.id')
                    ->whereYear('bills.date', today()->year)
                    ->where('plans.plan_status_id', 1)
                    ->groupBy('plan_user.plan_id', \DB::raw('YEAR(bills.date), MONTH(bills.date)'))
                    ->selectRaw('COUNT(bills.id) as total, YEAR(bills.date) as year, 
                                 MONTH(bills.date) as month, plans.id as plan')
                    ->get();

        foreach (Plan::where('plan_status_id', 1)->get(['id', 'plan']) as $key => $plan) {
            for ($i = 0; $i < 13; $i++) {
                if ($i == 0) {
                    $result[$key]['plan'] = $plan->plan;
                } else {
                    $value = $data->where('plan', $plan->id)
                                  ->where('month', $i)
                                  ->where('year', today()->year)
                                  ->first();

                    $result[$key][$this->months[$i - 1]] = $value ? $value->total : 0;
                }
            }
        }

        echo json_encode(['data' => $result, 'max' => $data->max()->total]);
    }

    // CANTIDAD DE PLANES EN EL AÑO POR MES
    public function totalPlansYearByMonth()
    {
        for ($i = 1; $i < 13; $i++) {
            $year_plans[] = PlanIncomeSummary::where('month', $i)
                ->where('year', now()->year)
                ->get()
                ->sum('quantity');

            $actual_year[] = $year_plans[$i - 1];

            $past_year_plans[] = PlanIncomeSummary::where('month', $i)
                ->where('year', now()->subYear()->year)
                ->get()
                ->sum('quantity');

            $past_year[] = $past_year_plans[$i - 1];
        }

        return [$actual_year, $past_year];
    }

    /**
     * CANTIDAD DE PLANES EN EL AÑO POR MES
     * 
     * @return array
     */
    public function totalPlansSubYearByMonth()
    {
        for ($i = 1; $i < 13; $i++) {
            $quantity[] = PlanIncomeSummary::where('month', $i)
                                           ->where('year', now()->subYear()->year)
                                           ->get()
                                           ->sum('quantity');
            $quantity_plans[$i - 1] = ['month' => $this->months[$i - 1], 'quantity' => $quantity[$i - 1]];
        }

        return $quantity_plans;
    }

    /**
     * [quantityAnualReservationsByMonth description]
     * @return [type] [description]
     */
    public function quantityAnualReservationsByMonth()
    {
        for ($i = 0; $i < 12; $i++) {
            $reservations[] = Reservation::join('clases', 'clases.id', 'reservations.clase_id')
                                         ->whereMonth('clases.date', $i + 1)
                                         ->whereYear('clases.date', now()->year)
                                         ->count('reservations.id');
        }

        return $reservations;
    }

    public function quantitySubAnualReservationsByMonth()
    {
        for ($i = 0; $i < 12; $i++) {
            $reservations[] = Reservation::join('clases', 'clases.id', 'reservations.clase_id')
                                         ->whereMonth('clases.date', $i + 1)
                                         ->whereYear('clases.date', now()->subYear()->year)
                                         ->count('reservations.id');
        }

        return $reservations;
    }

    public function incomesCalibrate()
    {
        $plans = Plan::all();
        $year = now()->year;
        
        PlanIncomeSummary::where('year', $year)->delete();
        for ($i = 1; $i < 13; $i++) {
            foreach ($plans as $plan) {
                $amount = Bill::join('plan_user', 'plan_user.id', 'bills.plan_user_id')
                              ->where('plan_user.plan_id', $plan->id)
                              ->whereMonth('date', $i)
                              ->whereYear('date', $year)
                              ->get()
                              ->sum('amount');

                $quantity = Bill::join('plan_user', 'plan_user.id', 'bills.plan_user_id')
                                ->where('plan_user.plan_id', $plan->id)
                                ->whereMonth('date', $i)
                                ->whereYear('date', $year)
                                ->count('bills.id');

                if ($amount || $quantity) {
                    PlanIncomeSummary::create([
                        'plan_id' => $plan->id,
                        'amount' => $amount,
                        'quantity' => $quantity,
                        'month' => $i,
                        'year' => $year
                    ]);
                }
            }
        }

        return back()->with('success', 'Se han recalculado los ingresos');
    }

    public function heatMap() {
        return view('reports.heatmap');
    }
}

    // //INGRESOS POR MES AÑO ACTUAL
    // public function monthIncomeAnual()
    // {
    //     $incomes = PlanIncomeSummary::selectRaw('month, year, SUM(amount) as total')
    //                                    ->where('year', toDay()->year)
    //                                    ->orWhere('year', toDay()->subYear()->year)
    //                                    ->groupBy('year', 'month')
    //                                    ->get();

    //     for ($i = 0; $i < 12; $i++) {
    //         $actual_income = $incomes->where('month', $i + 1)
    //                                  ->where('year', toDay()->year)
    //                                  ->pluck('total')
    //                                  ->toArray();

    //         $actual_year[] = count($actual_income) ? (int)$actual_income[0] : 0;

    //         $past_year_income = $incomes->where('month', $i + 1)
    //                                  ->where('year', toDay()->subYear()->year)
    //                                  ->pluck('total')
    //                                  ->toArray();

    //         $past_year[] = count($past_year_income) ? (int)$past_year_income[0] : 0;
    //     }

    //     return [$actual_year, $past_year];
    // }

    // //INGRESOS POR MES AÑO ANTERIOR
    // public function monthIncomeAnualSub()
    // {
    //     $month_summ = PlanIncomeSummary::selectRaw('month, year, SUM(amount) as total')
    //                                    ->orWhere('year', now()->subYear()->year)
    //                                    ->groupBy('year', 'month')
    //                                    ->get();

    //     return $month_summ;
    // }
    // 
    // 
        // $data = $data->map(function ($plan) {
        //     return [
        //         'plan' => $plan->plan_id,
        //         'enero' => ,
        //         'febrero' => ,
        //         'marzo' => ,
        //         'abril' => ,
        //         'mayo' => ,
        //         'junio' => ,
        //         'julio' => ,
        //         'agosto' => ,
        //         'septiembre' => ,
        //         'octubre' => ,
        //         'noviembre' => ,
        //         'diciembre' => 
        //     ];
        // });
        // 
        // 
        // GET FROM PLANUSER TABLE
        //         // $data = PlanUser::selectRaw(
        //                     'COUNT(id) as total, YEAR(start_date) as year,
        //                     MONTH(start_date) as month, plan_id as plan'
        //                   )
        //                 ->whereYear('start_date', today()->year)
        //                 ->with(['plan' => function($plan) {
        //                     $plan->where('plan_status_id', 1);
        //                 }])
        //                 ->groupBy(\DB::raw('YEAR(start_date)'))
        //                 ->groupBy(\DB::raw('MONTH(start_date)'))
        //                 ->groupBy('plan_id')
        //                 ->get();