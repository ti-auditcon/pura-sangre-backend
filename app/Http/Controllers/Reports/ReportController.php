<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
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
        // dd(now()->year);
        $year_income = $this->yearIncome();
        $month_income = $this->monthIncome();
        $plans_month = $this->totalPlansMonth();
        $new_students = $this->newStudents();
        // dd($new_students);
        
        $summaries = PlanIncomeSummary::all();
        return view('reports.index')->with('summaries', $summaries);
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

    // CANTIDAD DE PLANES EN EL MES
    public function totalPlansMonth()
    {
        $plans_month = PlanIncomeSummary::where('month', now()->month)
                                               ->get()
                                               ->count();
        return $plans_month;
    }

    // ALUMNOS NUEVOS POR MES
    public function newStudents()
    {
        $new_students = User::whereMonth('created_at' , now()->month)
                            ->get()
                            ->count();
        return $new_students;
    }

}
