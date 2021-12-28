<?php

namespace App\Exports;

use App\Models\Users\User;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, WithHeadings
{
    // use EliminarTildes;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::leftJoin('plan_user', function ($join) {
                                $join->on('users.id', '=', 'plan_user.user_id')
                                    ->whereNull('plan_user.deleted_at')
                                    ->where('plan_user.start_date', '<=', today())
                                    ->where('plan_user.finish_date', '>=', today());
                            })
                        ->leftJoin('plans', 'plan_user.plan_id', '=', 'plans.id')
                        ->whereNull('users.deleted_at')
                        ->get([
                            DB::raw('CONCAT(users.first_name, " ", users.last_name) as Alumno'),
                            'users.rut as rut',
                            'users.email as Correo',
                            DB::raw('DATE_FORMAT(users.birthdate, "%d/%m/%Y") as fecha_nacimiento'),
                            // 'users.birthdate as fecha_nacimiento',
                            DB::raw('CONCAT("+569", users.phone) as telefono'),
                            'plans.plan as Último plan',
                            DB::raw('DATE_FORMAT(plan_user.finish_date, "%d/%m/%Y") as Vencimiento'),
                            DB::raw('DATE_FORMAT(plan_user.start_date, "%d/%m/%Y") as inicio'),
                            DB::raw('DATE_FORMAT(plan_user.finish_date, "%d/%m/%Y") as termino'),
                        ]);

        // return User::all()->map(function ($user) {
        //     if (isset($user->actual_plan)) {
        //         $plan = $user->actual_plan->plan->plan;
        //         $vence = $user->actual_plan->finish_date->format('d/m/Y');
        //         $inicio = $user->actual_plan->start_date->format('d/m/Y');
        //         $termino = $user->actual_plan->finish_date->format('d/m/Y');
        //     } else {
        //         $plan = 'sin plan';
        //         $vence = 'no aplica';
        //         $inicio = 'no aplica';
        //         $termino = 'no aplica';
        //     }
        //     return [
        //         $user->full_name,
        //         $user->rut_formated,
        //         $user->email,
        //         $user->birthdate->format('d/m/Y'),
        //         '+569 ' . $user->phone,
        //         $plan,
        //         $vence,
        //         $inicio,
        //         $termino,
        //     ];
        // });
    }

    public function headings(): array
    {
        return [
            'Alumno',
            'RUN',
            'Correo',
            'Fecha de Nacimiento',
            'Telefono',
            'Plan Activo',
            'Vencimiento',
            'inicio',
            'termino',
        ];
    }
}
