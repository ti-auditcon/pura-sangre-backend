<?php

namespace App\Http\Controllers\Reports;

use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Clases\Clase;
use Illuminate\Http\Request;
use App\Models\Clases\Reservation;
use App\Http\Controllers\Controller;
use App\Models\Plans\PlanIncomeSummary;

class ReportController extends Controller
{
    public $months = ["Enero", "Febrero", "Marzo", "Abril", "Mayo",
               "Junio", "Julio", "Agosto", "Septiembre",
               "Octubre", "Noviembre", "Diciembre"];

    public function plans()
    {
        $plans = Plan::all()->pluck('plan');
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

    public function firstchart()
    {
        $anual = $this->monthIncomeAnual();
        $anual_sub = $this->monthIncomeAnualSub();
        return response()->json(array('anual' => $anual, 'anual_sub' => $anual_sub));
    }

    public function secondchart()
    {
        $q_anual = $this->totalPlansYearByMonth();
        $q_sub_anual = $this->totalPlansSubYearByMonth();
        return response()->json(array('q_anual' => $q_anual, 'q_sub_anual' => $q_sub_anual));
    }

    public function thirdchart()
    {
        $rsrvs_anual = $this->quantityAnualReservationsByMonth();
        $rsrvs_sub_anual = $this->quantitySubAnualReservationsByMonth();
        return response()->json(array('rsrvs_anual' => $rsrvs_anual, 'rsrvs_sub_anual' => $rsrvs_sub_anual));
    }

    public function totalplans()
    {
        $data = $this->quantityPlansYearByMonth();
        echo json_encode($data);
    }

    public function totalplanssub()
    {
        $data = $this->quantityPlansSubYearByMonth();
        echo json_encode($data);
    }

    //INGRESOS POR MES AÑO ACTUAL
    public function monthIncomeAnual()
    {
        for ($i=1; $i < 13; $i++) { 
            $amount[] = PlanIncomeSummary::where('month', $i)
                                         ->where('year', now()->year)
                                         ->get()
                                         ->sum('amount');
            $month_summ[$i-1] = ['month' => $this->months[$i-1], 'amount' => $amount[$i-1]];
        }
        return $month_summ;
    }

        //INGRESOS POR MES AÑO ANTERIOR
    public function monthIncomeAnualSub()
    {
        for ($i=1; $i < 13; $i++) { 
            $amount[] = PlanIncomeSummary::where('month', $i)
                                             ->where('year', now()->subYear()->year)
                                             ->get()
                                             ->sum('amount');
            $month_summ[$i-1] = ['month' => $this->months[$i-1], 'amount' => $amount[$i-1]];
        }
        return $month_summ;
    }

    // CANTIDAD DE PLANES EN EL AÑO POR MES
    public function totalPlansYearByMonth()
    {
        for ($i=1; $i < 13; $i++) { 
            $quantity[] = PlanIncomeSummary::where('month', $i)
                                           ->where('year', now()->year)
                                           ->get()
                                           ->sum('quantity');
            $quantity_plans[$i-1] = ['month' => $this->months[$i-1], 'quantity' => $quantity[$i-1]];
        }
        return $quantity_plans;
    }

    // CANTIDAD DE PLANES EN EL AÑO POR MES
    public function totalPlansSubYearByMonth()
    {
        for ($i=1; $i < 13; $i++) { 
            $quantity[] = PlanIncomeSummary::where('month', $i)
                                           ->where('year', now()->subYear()->year)
                                           ->get()
                                           ->sum('quantity');
            $quantity_plans[$i-1] = ['month' => $this->months[$i-1], 'quantity' => $quantity[$i-1]];
        }
        return $quantity_plans;
    }

    // CANTIDAD DE PLANES EN EL AÑO POR MES
    public function quantityPlansYearByMonth()
    {
        $plans = $this->plans(); 
        for ($i=0; $i < 12 ; $i++) {
            $quantity[] = PlanIncomeSummary::where('plan_id', $i+1)
                                           ->where('year', now()->year)
                                           ->get()
                                           ->sum('quantity');
            $plans_by_years[$i] = [$plans[$i], $quantity[$i]];
        }
        $plans_by_years = array_merge(['data' => $plans_by_years, 'year' => now()->year]);
        return $plans_by_years;
    }

    // CANTIDAD DE PLANES EN EL AÑO POR MES
    public function quantityPlansSubYearByMonth()
    {
        $plans = $this->plans(); 
        for ($i=0; $i < 12 ; $i++) {
            $quantity[] = PlanIncomeSummary::where('plan_id', $i+1)
                                           ->where('year', now()->subYear()->year)
                                           ->get()
                                           ->sum('quantity');
            $plans_by_years[$i] = [$plans[$i], $quantity[$i]];
        }
        $plans_by_years = array_merge(['data' => $plans_by_years, 'year' => now()->subYear()->year]);
        return $plans_by_years;
    }

    public function quantityAnualReservationsByMonth()
    {
        for ($i=0; $i < 12; $i++) { 
            $reservations[] = Reservation::join('clases', 'clases.id', 'reservations.clase_id')
                                         ->whereMonth('clases.date', $i+1)
                                         ->whereYear('clases.date', now()->year)
                                         ->get()
                                         ->count();
            $q_reservations[$i] = ['month' => $this->months[$i], 'reservations' => $reservations[$i]];
        }
        return $q_reservations;
    }

    public function quantitySubAnualReservationsByMonth()
    {
        for ($i=0; $i < 12; $i++) { 
            $reservations[] = Reservation::join('clases', 'clases.id', 'reservations.clase_id')
                                         ->whereMonth('clases.date', $i+1)
                                         ->whereYear('clases.date', now()->subYear()->year)
                                         ->get()
                                         ->count();
            $q_reservations[$i] = ['reservations' => $reservations[$i]];
        }
        return $q_reservations;
    }


    // //INGRESOS EN EL AÑO
    // public function yearIncome()
    // {
    //     $year_summ = PlanIncomeSummary::where('year', now()->year)
    //                                   ->get()
    //                                   ->sum('amount');
    //     return $year_summ;
    // }

    // //INGRESOS EN EL MES
    // public function monthIncome()
    // {
    //     $month_summ = PlanIncomeSummary::where('month', now()->month)
    //                                    ->get()
    //                                    ->sum('amount');
    //     return $month_summ;
    // }


    // // CANTIDAD DE PLANES EN EL AÑO
    // public function totalPlansYear()
    // {
    //     $plans_year = PlanIncomeSummary::where('year', now()->year)
    //                                            ->get()
    //                                            ->count();
    //     return $plans_year;
    // }

    // // CANTIDAD DE PLANES DEL AÑO ANTERIOR
    // public function totalPlansSubYear()
    // {
    //     $plans_before_year = PlanIncomeSummary::where('year', now()->subYear()->year)
    //                                            ->get()
    //                                            ->count();
    //     return $plans_before_year;
    // }

    // // CANTIDAD DE PLANES EN EL MES
    // public function totalPlansMonth()
    // {
    //     $plans_month = PlanIncomeSummary::where('month', now()->month)
    //                                            ->get()
    //                                            ->count();
    //     return $plans_month;
    // }

    // // CANTIDAD DE PLANES DEL MES ANTERIOR
    // public function totalPlansSubMonth()
    // {
    //     $plans_sub_month = PlanIncomeSummary::where('month', now()->subMonth()->month)
    //                                            ->get()
    //                                            ->count();
    //     return $plans_sub_month;
    // }

    // // ALUMNOS NUEVOS POR MES
    // public function newStudents()
    // {
    //     $new_students = User::whereMonth('created_at' , now()->month)
    //                         ->get()
    //                         ->count();
    //     return $new_students;
    // }

    // public function reservationsOfDay()
    // {
    //     $day_reservations = null;
    //     $clases = Clase::where('date', toDay())->get();
    //     foreach ($clases as $clase) {
    //         foreach ($clase->reservations as $reservation) {
    //             $day_reservations[] = $reservation;
    //         }
    //     }
    //     // dd($day_reservations);
    //     return $day_reservations;
    // }

    // public function reservationsOfMonth()
    // {
    //     $month_reservations = null;
    //     $clases = Clase::where('date', '>=', now()->startOfMonth())
    //                    ->where('date', '<=', now()->endOfMonth())
    //                    ->get();
    //     foreach ($clases as $clase) {
    //         foreach ($clase->reservations as $reservation) {
    //             $month_reservations[] = $reservation;
    //         }
    //     }
    //     // dd($month_reservations);
    //     return $month_reservations;
    // }

    // public function reservationsDailyMonth()
    // {
    //     $month_reservations = null;
    //     $clases = Clase::where('date', '>=', now()->startOfMonth())
    //                    ->where('date', '<=', now()->endOfMonth())
    //                    ->get();
    //     foreach ($clases as $clase) {
    //         foreach ($clase->reservations as $reservation) {
    //             $month_reservations[] = $reservation;
    //         }
    //     }
    //     // dd($month_reservations);
    //     return $month_reservations;
    // }


}
