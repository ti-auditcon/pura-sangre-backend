<?php

namespace App\Exports;

use App\Models\Users\User;
use App\Traits\ExpiredPlans;
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
        return $this->ExpiredPlan()->map(function ($plan) {
            
            return [                
                $plan->user->full_name,

                '+56 9 ' . $plan->user->phone,
                                
                $plan->plan->plan,
                
                $plan->finish_date->format('d-m-Y'),

                $plan->counter
            ];
        });
    }

    /**
     * headings for excel export
     * @return [type] [description]
     */
    public function headings(): array
    {
        return [
        	'Alumno',
            'N° de teléfono',
        	'Último Plan',
        	'Fecha de término del plan',
        	'Clases restantes',
        ];
    }
}