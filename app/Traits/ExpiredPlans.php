<?php

namespace App\Traits;

use App\Models\Plans\PlanUser;
use App\Models\Users\User;

trait ExpiredPlans
{
	public function ExpiredPlan()
    {
        $expired_plans = collect(new PlanUser);

        foreach (User::all() as $user) {
            if (! $user->actual_plan) {
                $plan_user = $user->plan_users->whereIn('plan_status_id', [3, 4])
                                              
                                              ->where('finish_date', '<', today())
                                              
                                              ->sortByDesc('finish_date')
                                              
                                              ->first();
                
                if ($plan_user) {
                
                    $expired_plans->push($plan_user);
                
                }
            
            }
        }

        return $expired_plans->sortByDesc('finish_date');
    }
}
