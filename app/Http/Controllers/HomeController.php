<?php

namespace App\Http\Controllers;

use App\Models\Plans\PlanUser;
use App\Models\Users\User;
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
        $plan_users = PlanUser::where('plan_status_id', 1)->where('finish_date','>=', now())->orderBy('finish_date')->get();
        $expired_plans = $this->ExpiredPlan();
        return view('home')->with('plan_users', $plan_users)->with('expired_plans', $expired_plans);
    }

    public function ExpiredPlan()
    {
        $expired_plans = collect(new PlanUser);
        // ->subDays(15)
        foreach (User::all() as $user){
            if (!$user->actual_plan){
                $plan_user = $user->plan_users->whereIn('plan_status_id', [3, 4])
                                              ->where('finish_date', '<', today())
                                              ->sortByDesc('finish_date')
                                              ->first();
                if ($plan_user){
                    $expired_plans->push($plan_user);
                }
            }
        }
        return $expired_plans->sortByDesc('finish_date');
    }
}

        // foreach (User::all() as $user) {
        //     $plan_users = $user->plan_users->whereIn('plan_status_id', [3, 4])
        //                                    ->where('finish_date' '<=', today()->subDays(15))
        //                                    ->orderBy('finish_date')
        //                                    ->get();
        //     if ($plan_users->count()) {
        //         $expired_plans = $user->plan_users->sortByDesc('finish_date')->first();
        //     }
        // }
       
        // ----------------ORDENAR UN ARRAY DE MANERA DESCENDENTE---------------------
        // $expired_plans = array_values(array_reverse(array_sort($expired_plans, function ($value) {
        //     return $value['finish_date'];
        // })));
        // --------------------------           ---                -------------------------
