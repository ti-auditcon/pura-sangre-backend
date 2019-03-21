<?php

namespace App\Http\Controllers;

use Session;
use App\Models\Clases\ClaseType;
use App\Models\Plans\PlanIncomeSummary;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use App\Traits\ExpiredPlans;
use Illuminate\Http\Request;

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
        if(!Session::has('clases-type-id'))
        {
            Session::put('clases-type-id',1);
            Session::put('clases-type-name',ClaseType::find(1)->clase_type);
        }
        $plan_users = $this->expiredNext();
        $expired_plans = $this->ExpiredPlan();
        return view('home')->with('plan_users', $plan_users)->with('expired_plans', $expired_plans);
    }

    public function expiredNext()
    {
        $plan_users = PlanUser::where('plan_status_id', 1)
                              ->where('finish_date','>=', now())
                              ->orderBy('finish_date')
                              ->get();

        return $plan_users->map(function ($plan){
            return [
                'user_id' => isset($plan->user) ? $plan->user->id : '',
                'alumno' => isset($plan->user) ? $plan->user->first_name.' '.$plan->user->last_name : '',
                'plan' => isset($plan->plan) ? $plan->plan->plan : '',
                'fecha_termino' => \Date::parse($plan->finish_date)->diffForHumans(),
                'telefono' => isset($plan->user) ? $plan->user->phone : '',
            ];
        });
    }

    public function ExpiredPlan()
    {
        $expired_plans = collect(new PlanUser);
        foreach (User::all() as $user){
            if (!$user->actual_plan){
                $plan_user = $user->plan_users->whereIn('plan_status_id', [3, 4])
                                              ->where('plan_id', '!=', 1)
                                              ->where('finish_date', '<', today())
                                              ->sortByDesc('finish_date')
                                              ->first();
                if ($plan_user){
                    $expired_plans->push($plan_user);
                }
            }
        }

        return $expired_plans->sortByDesc('finish_date')->map(function ($plan) {
               return [
                'user_id' => isset($plan->user) ? $plan->user->id : '',
                'alumno' => isset($plan->user) ? $plan->user->first_name.' '.$plan->user->last_name: '',
                'plan' => isset($plan->plan) ? $plan->plan->plan : '',
                'fecha_termino' => \Date::parse($plan->finish_date)->diffForHumans(),
                'telefono' => isset($plan->user) ? $plan->user->phone : '',
            ];
        });

        // return $expired_plans;
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

    public function incomessummary()
    {
        //obtener todos los planes del mes actual que tengan anexada una boleta
        //sin contar boletas eliminadas
        $mes['periodo'] = 'mensual';
        $month_amount = PlanUser::whereDate('plan_user.created_at', '>=', today()->startOfMonth())
                                ->whereDate('plan_user.created_at', '<=', today()->endOfMonth())
                                ->join('bills', 'bills.plan_user_id', '=', 'plan_user.id')
                                ->get()
                                ->sum('amount');
        $mes['ingresos'] = '$ '.number_format($month_amount, $decimal = 0, '.', '.');
        $mes['cantidad'] = PlanUser::whereDate('plan_user.created_at', '>=', today()->startOfMonth())
                                   ->whereDate('plan_user.created_at', '<=', today()->endOfMonth())
                                   ->join('bills', 'bills.plan_user_id', '=', 'plan_user.id')
                                   ->count();

        //obtener todos los planes de este día que tengan anexada una boleta
        //sin contar boletas eliminadas
        $dia['periodo'] = 'hoy';
        $day_amount = PlanUser::whereDate('plan_user.created_at', today())
                              ->join('bills', 'bills.plan_user_id', '=', 'plan_user.id')
                              ->get()
                              ->sum('amount');
        $dia['ingresos'] = '$ '.number_format($day_amount, $decimal = 0, '.', '.');
        $dia['cantidad'] = PlanUser::whereDate('plan_user.created_at', today())
                                   ->join('bills', 'bills.plan_user_id', '=', 'plan_user.id')
                                   ->count();
        $in_sum = array_merge([$dia, $mes]);
        echo json_encode($in_sum);
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
