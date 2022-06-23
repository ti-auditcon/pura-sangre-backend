<?php

namespace App\Exports;

use App\Models\Users\User;
use App\Traits\ExpiredPlans;
use App\Models\Plans\PlanUser;
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

        return $plan_users->map(function ($plan) {
            $user = $plan->user;
            return [
                $user ? $user->full_name : 'sin datos',

                $user ? $user->email : 'sin datos',

                $user ? '+56 9 ' . $user->phone : 'no aplica',

                $user ? $user->since->format('d-m-Y') : 'no aplica',

                optional($plan->plan)->plan,

                $plan->finish_date->format('d-m-Y'),

                $plan->counter
            ];
        });
    }
}
