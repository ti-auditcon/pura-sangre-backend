<?php

namespace App\Exports;

use DateTime;
use App\Models\Users\User;
use App\Models\Plans\PlanStatus;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection, WithHeadings
{
    /**
     *  @return  \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with(['todayPlan' => function ($todayPlan) {
            $todayPlan->with(['plan:id,plan'])
                ->select(
                    'id',
                    'user_id',
                    'plan_id',
                    'start_date',
                    'finish_date',
                    'counter',
                    'plan_status_id'
                );
            }, 
            'emergency:id,contact_name,contact_phone,user_id'])
            ->distinct()
            ->get([
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.address',
                // DB::raw('CONCAT(users.first_name, " ", users.last_name) as alumno'),
                'users.birthdate',
                'users.rut',
                'users.email',
                DB::raw('CONCAT("+569", users.phone) as telefono'),
            ])->map(function ($user) {
                $hasTodayPlan = boolval($user->todayPlan);

                return [
                    $user->first_name,
                    $user->last_name,
                    $user->rut_formated,
                    $user->address,
                    $user->email,
                    $user->birthdate->format('d/m/Y'),
                    $user->telefono,
                    $hasTodayPlan ? $user->todayPlan->plan->plan : 'no aplica',
                    $hasTodayPlan ? app(PlanStatus::class)->getPlanStatus($user->todayPlan->plan_status_id) : 'no aplica',
                    $hasTodayPlan ? (new DateTime($user->todayPlan->finish_date))->format('d/m/Y') : 'no aplica',
                    $hasTodayPlan ? (new DateTime($user->todayPlan->start_date))->format('d/m/Y') : 'no aplica',
                    $hasTodayPlan ? (new DateTime($user->todayPlan->finish_date))->format('d/m/Y') : 'no aplica',
                    optional($user->emergency)->contact_name,
                    optional($user->emergency)->contact_phone,
                ];
            }
        );
    }

    /**
     *  Headers indexes for the exported excel
     *
     *  @return  array
     */
    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'RUN',
            'Dirección',
            'Correo',
            'Fecha de Nacimiento',
            'Teléfono',
            'Plan',
            'Estado del plan',
            'Vencimiento',
            'Inicio',
            'Término',
            'Contacto de emergencia',
            'Nº contacto de emergencia'
        ];
    }
}
