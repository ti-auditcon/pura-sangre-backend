<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Plans\PlanIncomeSummary;
use App\Models\Users\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd($this->monthIncomeAnual());
        $chartjs = $this->firstChart();
        $summaries = PlanIncomeSummary::all();

        return view('reports.index')->with('summaries', $summaries)
                                    ->with(compact('chartjs'));
                       
    }

    //INGRESOS EN EL AÑO
    public function yearIncome()
    {
        $year_summ = PlanIncomeSummary::where('year', now()->year)
                                      ->get()
                                      ->sum('amount');
        return $year_summ;
    }


    //INGRESOS EN EL MES
    public function monthIncome()
    {
        $month_summ = PlanIncomeSummary::where('month', now()->month)
                                       ->get()
                                       ->sum('amount');
        return $month_summ;
    }

    //INGRESOS POR MES AÑO ACTUAL
    public function monthIncomeAnual()
    {
        for ($i=1; $i < 13; $i++) { 
            $month_summ[] = PlanIncomeSummary::where('month', $i)
                                             ->where('year', now()->year)
                                             ->get()
                                             ->sum('amount');
        }
        return $month_summ;
    }

        //INGRESOS POR MES AÑO ANTERIOR
    public function monthIncomeAnualSub()
    {
        for ($i=1; $i < 13; $i++) { 
            $month_summ[] = PlanIncomeSummary::where('month', $i)
                                             ->where('year', now()->subYear()->year)
                                             ->get()
                                             ->sum('amount');
        }
        // dd($month_summ);
        return $month_summ;
    }

    // CANTIDAD DE PLANES EN EL AÑO
    public function totalPlansYear()
    {
        $plans_year = PlanIncomeSummary::where('year', now()->year)
                                               ->get()
                                               ->count();
        return $plans_year;
    }

    public function totalPlansbeforeYear()
    {
        $plans_year = PlanIncomeSummary::where('year', now()->year -1)
                                               ->get()
                                               ->count();
        return $plans_year;
    }

    // CANTIDAD DE PLANES DEL AÑO ANTERIOR
    public function totalPlansSubYear()
    {
        $plans_before_year = PlanIncomeSummary::where('year', now()->subYear()->year)
                                               ->get()
                                               ->count();
        return $plans_before_year;
    }

    // CANTIDAD DE PLANES EN EL MES
    public function totalPlansMonth()
    {
        $plans_month = PlanIncomeSummary::where('month', now()->month)
                                               ->get()
                                               ->count();
        return $plans_month;
    }

    // CANTIDAD DE PLANES DEL MES ANTERIOR
    public function totalPlansSubMonth()
    {
        $plans_sub_month = PlanIncomeSummary::where('month', now()->subMonth()->month)
                                               ->get()
                                               ->count();
        return $plans_sub_month;
    }

    // ALUMNOS NUEVOS POR MES
    public function newStudents()
    {
        $new_students = User::whereMonth('created_at' , now()->month)
                            ->get()
                            ->count();
        return $new_students;
    }

    public function reservationsOfDay()
    {
        $day_reservations = null;
        $clases = Clase::where('date', toDay())->get();
        foreach ($clases as $clase) {
            foreach ($clase->reservations as $reservation) {
                $day_reservations[] = $reservation;
            }
        }
        // dd($day_reservations);
        return $day_reservations;
    }

    public function reservationsOfMonth()
    {
        $month_reservations = null;
        $clases = Clase::where('date', '>=', now()->startOfMonth())
                       ->where('date', '<=', now()->endOfMonth())
                       ->get();
        foreach ($clases as $clase) {
            foreach ($clase->reservations as $reservation) {
                $month_reservations[] = $reservation;
            }
        }
        // dd($month_reservations);
        return $month_reservations;
    }

    public function reservationsDailyMonth()
    {
        $month_reservations = null;
        $clases = Clase::where('date', '>=', now()->startOfMonth())
                       ->where('date', '<=', now()->endOfMonth())
                       ->get();
        foreach ($clases as $clase) {
            foreach ($clase->reservations as $reservation) {
                $month_reservations[] = $reservation;
            }
        }
        // dd($month_reservations);
        return $month_reservations;
    }

    public function firstChart()
    {
        $actual_year = $this->monthIncomeAnual();
        $past_year = $this->monthIncomeAnualSub();
        $chartjs = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'])
            ->datasets([
                [
                    "label" => "Año 2019",
                    'backgroundColor' => ['rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)'],
                    'data' => $actual_year,
                ],
                [
                    "label" => "Año 2018",
                    'backgroundColor' => ['rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)',],
                    'data' => $past_year,
                ],
            ])
            ->optionsRaw("{

            }");

        return $chartjs;
    }

    public function secondChart()
    {
        $actual_year = $this->monthIncomeAnual();
        $past_year = $this->monthIncomeAnualSub();
        $secondchart = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'])
            ->datasets([
                [
                    "label" => "Año 2019",
                    'backgroundColor' => ['rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)', 'rgba(54, 162, 235, 0.5)'],
                    'data' => $actual_year,
                ],
                [
                    "label" => "Año 2018",
                    'backgroundColor' => ['rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)', 'rgba(255, 99, 132, 0.5)',],
                    'data' => $past_year,
                ],
            ])
            ->optionsRaw("{

            }");

        return $secondchart;
    }

}
