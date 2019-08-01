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
        $plan_users = collect(new PlanUser);

        foreach (User::all() as $user) {
            if ($user->status_user_id == 2) {
                $plan_user = $user->plan_users->whereIn('plan_status_id', [3, 4])
                                              
                                              ->where('finish_date', '<', today())
                                              
                                              ->sortByDesc('finish_date')
                                              
                                              ->first();
                
                if ($plan_user) {
                
                    $plan_users->push($plan_user);
                
                }
            
            }
        }

        $totalData = $plan_users->count();

        $totalFiltered = $plan_users->count(); 

        $data = array();
        
        if ($plan_users) {
            foreach ($plan_users as $plan_user) {
                $nestedData['first_name'] = '<a href="'.url("/users/{$plan_user->user->id}").'">'. $plan_user->user->first_name . ' ' . $plan_user->user->last_name.'</a>';
                $nestedData['phone'] = $plan_user->user->phone;
                $nestedData['plan'] = $plan_user->plan->plan;
                $nestedData['date'] = $plan_user->finish_date->format('d-m-Y');
                $nestedData['remaining_clases'] = $plan_user->counter;
                $nestedData['date_raw'] = $plan_user->finish_date;

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
