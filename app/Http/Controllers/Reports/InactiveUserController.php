<?php

namespace App\Http\Controllers\Reports;

use App\Exports\InactiveUsersExport;
use App\Http\Controllers\Controller;
use App\Traits\ExpiredPlans;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InactiveUserController extends Controller
{
    use ExpiredPlans;

    /**
     * Show all the users who has expired plans
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('reports.inactives_users');
    }

    public function inactiveUsers(Request $request)
    {
        return $this->ExpiredPlan($request);
    }

    /**
     * Export Excel of Inactive System Users
     * 
     * @return Maatwebsite\Excel\Facades\Excel
     */
    public function export()
    {
        return Excel::download(new InactiveUsersExport, toDay()->format('d-m-Y') . '_usuarios_inactivos.xlsx');
    }
}