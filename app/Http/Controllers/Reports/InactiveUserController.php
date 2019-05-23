<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Traits\ExpiredPlans;
use Illuminate\Http\Request;

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
        // dd($inactive_users->count());
        return view('reports.inactives_users')->with('inactive_users', $inactive_users);
    }
}