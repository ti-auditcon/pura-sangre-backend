<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanStatus;

class UserReportService
{
    public function activeUsersAt(Carbon $date)
    {
        return User::join('plan_user', 'users.id', '=', 'plan_user.user_id')
            ->where('plan_user.start_date', '<=', $date)
            ->where('plan_user.finish_date', '>=', $date)
            ->whereNotIn('plan_user.plan_status_id', [PlanStatus::CANCELED, PlanStatus::FROZEN])
            ->where('plan_user.plan_id', '!=', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'users.avatar', 'users.phone', 'users.rut')
            ->distinct('users.id');
    }

    public function activeUsersAtLastDay(Carbon $date)
    {
        return User::join('plan_user', 'users.id', '=', 'plan_user.user_id')
            ->join('plans', 'plans.id', '=', 'plan_user.plan_id')
            ->where('plan_user.start_date', '<=', $date->copy()->endOfDay())
            ->where('plan_user.finish_date', '>=', $date->copy()->startOfDay())
            ->whereNotIn('plan_user.plan_status_id', [PlanStatus::CANCELED, PlanStatus::FROZEN])
            ->where('plans.id', '!=', Plan::TRIAL)
            ->whereNull('plan_user.deleted_at')
            ->select('users.id as id', 'users.first_name', 'users.last_name', 'users.email', 'users.avatar', 'users.phone', 'users.rut')
            ->distinct('users.id');
    }
}