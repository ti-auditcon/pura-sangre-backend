<?php

namespace App\Http\Controllers;

use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Traits\ExpiredPlans;
use App\Models\Plans\PlanUser;

class HomeController extends Controller
{
    use ExpiredPlans;
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

    public function withoutrenewal()
    {
        $plan_users = PlanUser::whereIn('plan_status_id', [1,4])
                          ->whereBetween('finish_date', [now()->subMonth()->endOfMonth(), now()->today()->subDay()])
                          ->orderBy('finish_date')
                          ->get();
        $inactives = 0;
        foreach ($plan_users as $plan_user) {
            if (!$plan_user->user->actual_plan) {
                $inactives += 1;
            }
        }
        $actives = 0;
        foreach (User::all() as $user) {
            if ($user->actual_plan) {
                $actives += 1;
            }
        }
        $no_renoval = array_merge(['actives' => $actives, 'inactives' => $inactives]);
        echo json_encode($no_renoval);
    }

    public function genders()
    {
        $women = 0;
        $men = 0;
        foreach (User::all() as $user) {
            if ($user->gender == 'hombre' && $user->actual_plan) {
                $men += 1;
            }elseif ($user->gender == 'mujer' && $user->actual_plan) {
                $women += 1;
            }
        }
        $genders = array_merge(['mujeres' => $women, 'hombres' => $men]);
        echo json_encode($genders);
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
