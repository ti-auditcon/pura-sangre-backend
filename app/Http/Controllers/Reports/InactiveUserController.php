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
     * [inactive_users show users with expired plans]
     * @return [array] [description]
     */
    public function index()
    {
        $inactive_users = $this->ExpiredPlan();

        return view('reports.inactives_users')->with('inactive_users', $inactive_users);
    }

    public function export()
    {
        dd($this->ExpiredPlan());
        return Excel::download(new InactiveUsersExport, toDay()->format('d-m-Y') . '_usuarios_inactivos.xls');
    }
}