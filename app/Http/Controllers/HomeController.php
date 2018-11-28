<?php

namespace App\Http\Controllers;

use App\Models\Plans\PlanUser;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd('hola');
        $plan_users = PlanUser::where('plan_status_id', 1)->where('finish_date','>=', now())->orderBy('finish_date')->get();
        return view('home')->with('plan_users', $plan_users);
    }
}
