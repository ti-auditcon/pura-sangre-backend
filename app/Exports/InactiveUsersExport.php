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
     * @return  \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->exportUsers();
    }

    /**
     * Headings for excel export
     *
     * @return  array
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
     * Export users with inactive plans
     *
     * @return  \Illuminate\Support\Collection
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
