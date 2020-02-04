<?php

namespace App\Http\Controllers;

use App\Models\Bills\Bill;
use App\Models\Clases\Clase;
use App\Models\Clases\ClaseType;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanIncomeSummary;
use App\Models\Plans\PlanUser;
use App\Models\Users\User;
use App\Traits\ExpiredPlans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Session;

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
        if (!Session::has('clases-type-id')) {
            Session::put('clases-type-id', 1);
            Session::put('clases-type-name', ClaseType::find(1)->clase_type);
        }

        $plan_users = $this->expiredNext();
        return view('home')->with('plan_users', $plan_users);
    }

    public function expiredNext()
    {
        $plan_users = PlanUser::where('plan_status_id', 1)
                                ->where('finish_date', '>=', now())
                                ->orderBy('finish_date')
                                ->take(10)
                                ->get();

        return $plan_users->map(function ($plan) {
            return [
                'user_id' => isset($plan->user) ? $plan->user->id : '',
                'alumno' => isset($plan->user) ? $plan->user->first_name . ' ' . $plan->user->last_name : '',
                'plan' => isset($plan->plan) ? $plan->plan->plan : '',
                'fecha_termino' => Carbon::parse($plan->finish_date)->diffForHumans(),
                'telefono' => isset($plan->user) ? $plan->user->phone : '',
            ];
        });
    }

    /**
     * Get expired plans
     * 
     * @param  Request $request [description]
     * @return json
     */
    public function ExpiredPlan(Request $request)
    {
        $columns = array(
            0 => 'users.first_name',
            1 => 'plans.plan',
            2 => 'users.date',
            3 => 'users.phone'
        );
        
        $totalData = 8;

        $users = PlanUser::join('users', 'plan_user.user_id', '=', 'users.id')
                         ->join('plans', 'plan_user.plan_id', '=', 'plans.id')
                         ->where('users.status_user_id', 2)
                         ->where('plan_user.plan_status_id', 4)
                         ->where('plan_user.plan_id', '!=', 1)
                         ->where('plan_user.finish_date', '<', today())
                         ->orderByDesc('finish_date')
                         ->select('users.id', 'users.first_name', 'users.last_name', 'plans.plan', 'plan_user.finish_date', 'users.phone')
                         ->take(8)
                         ->get();

        $totalFiltered = 8; 

        $data = array();
        
        if ($users) {
            foreach ($users as $user) {
                $nestedData['first_name'] = '<a href="'.url("/users/{$user->id}").'">'. $user->first_name . ' ' . $user->last_name.'</a>';
                $nestedData['plan'] = $user->plan;
                $nestedData['date'] = $user->finish_date->format('d-m-Y');
                $nestedData['phone'] = $user->phone;

                $data[] = $nestedData;
            }
        }
        
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        
        echo json_encode($json_data);
    }

    /**
     * [withoutrenewal description]
     * @return [type] [description]
     */
    public function withoutrenewal()
    {
        $users = User::all(['id', 'status_user_id', 'gender']);
        $inactives = $actives = $tests = 0; 
        $women = $men = 0;
        
        foreach ($users as $user) {
            switch ($user->status_user_id) {
                case 1:
                    $actives += 1;
                    if ($user->gender == 'hombre') {
                        $men += 1;
                    } else {
                        $women += 1;
                    }
                    break;

                case 2:
                    $inactives += 1;
                    break;

                case 3:
                    $tests += 1;
                    break;
                
                default:
                    break;
            }
        }
        $data = array_merge([
            'mujeres' => $women,
            'hombres' => $men,
            'actives' => $actives,
            'inactives' => $inactives,
            'tests' => $tests
        ]);

        return response()->json($data);
    }

    // public function genders()
    // {
    //     foreach (User::all() as $user) {
    //         if ($user->actual_plan) {
    //             if ($user->gender == 'hombre') {
    //                 $men += 1;
    //             }
    //             if ($user->gender == 'mujer') {
    //                 $women += 1;
    //             }
    //         }
    //     }
    //     $genders = array_merge(['mujeres' => $women, 'hombres' => $men]);
    //     echo json_encode($genders);
    // }

    /**
     * Summary of today and this month bills (amount and quantity)
     * 
     * @return array
     */
    public function incomessummary()
    {
        $today_bills = Bill::whereDate('bills.date', today())->get(['id', 'amount']);

        $month_bills = Bill::whereDate('bills.date', '>=', today()->startOfMonth())
                           ->whereDate('bills.date', '<=', today()->endOfMonth())
                           ->get(['id', 'amount']);

        $data = [
            'hoy_cantidad' => $today_bills->count('id') . ' planes',
            'hoy_total' => '$ ' . number_format($today_bills->sum('amount'), $decimal = 0, '.', '.'),
            'mes_cantidad' => $month_bills->count('id') . ' planes',
            'mes_total' => '$ ' . number_format($month_bills->sum('amount'), $decimal = 0, '.', '.'),
        ];

        return response()->json($data);
    }

    public function updateIncomeSummary()
    {
        $years = [2017, 2018, 2019];
        $plans = Plan::all();
        foreach ($years as $year) {
            for ($i = 1; $i < 13; $i++) {
                foreach ($plans as $plan) {
                    $amount = Bill::join('plan_user', 'plan_user.id', 'bills.plan_user_id')
                        ->where('plan_user.plan_id', $plan->id)
                        ->whereMonth('date', $i)
                        ->whereYear('date', $year)
                        ->get()
                        ->sum('amount');
                    $quantity = Bill::join('plan_user', 'plan_user.id', 'bills.plan_user_id')
                        ->where('plan_user.plan_id', $plan->id)
                        ->whereMonth('date', $i)
                        ->whereYear('date', $year)
                        ->count();
                    $income = new PlanIncomeSummary;
                    $income->plan_id = $plan->id;
                    $income->amount = $amount;
                    $income->quantity = $quantity;
                    $income->month = $i;
                    $income->year = $year;
                    $income->save();
                }
            }
        }
    }

    public function fixClases()
    {
        $clases = Clase::where('date', '>=', today())->get();
        foreach ($clases as $clase) {
            $clase->update(['quota' => $clase->block->quota]);
        }
        return 'Finalizado';
    }
}

// ----------------ORDENAR UN ARRAY DE MANERA DESCENDENTE---------------------
// $expired_plans = array_values(array_reverse(array_sort($expired_plans, function ($value) {
//     return $value['finish_date'];
// })));
// --------------------------           ---                -------------------------
