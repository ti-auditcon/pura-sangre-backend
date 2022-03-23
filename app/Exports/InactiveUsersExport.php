<?php

namespace App\Exports;

use App\Models\Users\User;
use App\Traits\ExpiredPlans;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanStatus;
use Freshwork\ChileanBundle\Rut;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class InactiveUsersExport implements FromCollection, WithHeadings
{
    use ExpiredPlans;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->exportUsers();
    }

    /**
     * headings for excel export
     * 
     * @return [type] [description]
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

    public function exportUsers()
    {
        $plan_users = collect();

        foreach (User::all(['id', 'status_user_id']) as $user) {
            if ($user->isInactive()) {
                $plan_user = $user->plan_users()
                                    ->whereIn('plan_status_id', [PlanStatus::PRE_PURCHASE, PlanStatus::COMPLETED])
                                    ->where('finish_date', '<', today())
                                    ->orderBy('finish_date')
                                    ->first();

                if ($plan_user) {
                    $plan_users->push($plan_user);
                }
            }
        }

        return $plan_users->map(function ($plan) {
            return [                
                $plan->user->full_name,
                $plan->user->email,
                '+56 9 ' . $plan->user->phone,
                optional($plan->user->since)->format('d-m-Y'),
                $plan->plan->plan,
                $plan->finish_date->format('d-m-Y'),
                $plan->counter
            ];
        });
    }
}