<?php

namespace App\Traits;

use App\Models\Plans\PlanUser;
use App\Models\Users\User;

trait ExpiredPlans
{
	public function ExpiredPlan($request)
    {
        $columns = array(
            0 => 'users.first_name',
            1 => 'plans.plan',
            2 => 'users.date',
            3 => 'users.phone'
        );

        $users = User::where('status_user_id', 2)
                     ->with(['last_plan:id,plan_status_id,finish_date,counter,user_id,plan_id', 'last_plan.plan:id,plan'])
                     ->get(['id', 'first_name', 'last_name', 'status_user_id', 'phone']);

        // dd($users->count());

        // foreach (User::all() as $user) {
        //     if ($user->status_user_id == 2) {
        //         $user = $user->users->whereIn('plan_status_id', [3, 4])
                                              
        //                                       ->where('finish_date', '<', today())
                                              
        //                                       ->sortByDesc('finish_date')
                                              
        //                                       ->first();
                
        //         if ($user) {
                
        //             $users->push($user);
                
        //         }
            
        //     }
        // }

        $totalData = $users->count();

        $totalFiltered = $users->count(); 

        $data = array();
        
        if ($users) {
            foreach ($users as $user) {
                // dd($user);
                $nestedData['full_name'] = '<a href="'.url("/users/{$user->id}").'">'. $user->full_name .'</a>';
                $nestedData['phone'] = $user->phone;
                if (! $user->last_plan) {
                    $nestedData['plan'] = 'sin plan';
                    $nestedData['date'] = 'no aplica';
                    $nestedData['remaining_clases'] = 'no aplica';
                    $nestedData['date_raw'] = '';
                }else {
                    $nestedData['plan'] = $user->last_plan->plan->plan;
                    $nestedData['date'] = $user->last_plan->finish_date->format('d-m-Y');
                    $nestedData['remaining_clases'] = $user->last_plan->counter;
                    $nestedData['date_raw'] = $user->last_plan->finish_date;
                }
               

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
}
