<?php

namespace App\Exports;

use App\Models\Users\User;
use Freshwork\ChileanBundle\Rut;
use Maatwebsite\Excel\Concerns\FromCollection;

class InactiveUsersExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all()->map(function ($user) {
            if (isset($user->actual_plan)) {
                $plan = $user->actual_plan->plan->plan;
                $vence = $user->actual_plan->finish_date->format('d/m/Y');
                $inicio = $user->actual_plan->start_date->format('d/m/Y');
                $termino = $user->actual_plan->finish_date->format('d/m/Y');
            } else {
                $plan = 'sin plan';
                $vence = 'no aplica';
                $inicio = 'no aplica';
                $termino = 'no aplica';
            }
            return [
                $user->full_name,
                Rut::set($user->rut)->fix()->format(),
                $user->birthdate->format('d/m/Y'),
                '+569 ' . $user->phone,
                $plan,
                $vence,
                $inicio,
                $termino,
            ];
        });
    }

    public function headings(): array
    {
        return [
        	'Alumno',
        	'Último Plan',
        	'Fecha de término del plan'
        	'N° de teléfono'
        ];
    }
}
