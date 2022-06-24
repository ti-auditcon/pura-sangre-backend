<?php

namespace App\Exports;

use App\Models\Users\User;
use App\Traits\ExpiredPlans;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use App\Models\Users\StatusUser;
use Freshwork\ChileanBundle\Rut;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class InactiveUsersExport implements FromCollection, WithHeadings
{
    use ExpiredPlans;

    /**
     *  @return  \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->exportUsers();
    }

    /**
     *  Headings for excel export
     * 
     *  @return  array
     */
    public function headings(): array
    {
        return [
        	'Alumno',
            'Correo',
            'N° de teléfono',
            'Atleta desde',
        	'Último Plan',
        	'Fecha de término del plan',
        	'Clases restantes',
        ];
    }

    /**
     *  Export users with inactive plans
     *
     *  @return  \Illuminate\Support\Collection
     */
    public function exportUsers()
    {
        $users = User::where('status_user_id', StatusUser::INACTIVE)
                        ->with('last_plan')
                        ->get();

        return $users->map(function ($user) {
            $plan_user = $user->last_plan;

            return [
                $user->full_name,
                $user->email,
                $user->phone,
                $user->since ? $user->since->format('d-m-Y') : 'sin información',
                $plan_user ? $plan_user->plan->plan : 'sin registro',
                $plan_user ? $plan_user->finish_date->format('d-m-Y') : 'no aplica',
                $plan_user ? $plan_user->counter : 'no aplica',
            ];
        });
    }
}


        // get all users with expired plans
        // $users = PlanUser::join('users', 'users.id', '=', 'plan_user.user_id')
        //                     ->join('plans', 'plans.id', '=', 'plan_user.plan_id')
        //                     ->where('users.status_user_id', '=', StatusUser::INACTIVE)
        //                     ->whereIn('plan_user.plan_status_id', '=', [PlanStatus::ACTIVE, PlanStatus::COMPLETED])
        //                     ->where('plan_user.expiration_date', '<', date('Y-m-d'))
        //                     ->select('users.*', 'plan_user.plan_id', 'plan_user.expiration_date')
        //                     ->get();
        //             //          ->where('plan_status_id', '!=', 5)
        //             // ->orderByDesc('finish_date');

        // foreach (User::all(['id', 'status_user_id']) as $user) {
        //     if ($user->isInactive()) {
        //         $plan_user = $user->plan_user()
        //                             ->whereIn('plan_status_id', [PlanStatus::PRE_PURCHASE, PlanStatus::COMPLETED])
        //                             ->where('finish_date', '<', today())
        //                             ->orderBy('finish_date')
        //                             ->first();

        //         if ($plan_user) {
        //             $plan_user->push($plan_user);
        //         }
        //     }
        // }

        // return $plan_users->map(function ($plan) {
        //     return [                
        //         $plan->user->full_name,
        //         $plan->user->email,
        //         '+56 9 ' . $plan->user->phone,
        //         optional($plan->user->since)->format('d-m-Y'),
        //         $plan->plan->plan,
        //         $plan->finish_date->format('d-m-Y'),
        //         $plan->counter
        //     ];
        // });