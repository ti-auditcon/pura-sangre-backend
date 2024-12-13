<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExcel implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Bill::query()->with([
            'payment_type:id,payment_type',
            'plan_user:id,user_id,plan_id',
            'plan_user.plan:id,plan',
            'plan_user.user:id,first_name,last_name,email'
        ]);
    }

    public function headings(): array
    {
        return [
            'Fecha registro',
            'Alumno',
            'Correo',
            'Plan',
            'Tipo de Pago',
            'Fecha Boleta',
            'Fecha Inicio plan',
            'Fecha término plan',
            'total',
            'total pagado'
        ];
    }

    public function map($bill): array
    {
        return [
            $bill->created_at->format('d-m-Y'),
            $bill->plan_user ? $bill->plan_user->user->full_name : 'sin informacion',
            $bill->plan_user ? $bill->plan_user->user->email : 'sin informacion',
            $bill->plan_user ? $bill->plan_user->plan->plan : 'sin informacion',
            $bill->payment_type->payment_type,
            Carbon::parse($bill->date)->format('d-m-Y'),
            Carbon::parse($bill->start_date)->format('d-m-Y'),
            Carbon::parse($bill->finish_date)->format('d-m-Y'),
            $bill->amount,
            $bill->total_paid
        ];
    }
}