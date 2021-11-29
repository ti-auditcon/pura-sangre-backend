<?php

namespace App\Exports;

use App\Models\Bills\Bill;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PaymentsExcel implements FromCollection, WithHeadings
{
    /**
     *  @return  \Illuminate\Support\Collection
     */
    public function collection()
    {
    	$bills = Bill::with([
                        'payment_type:id,payment_type',
    					'plan_user:id,user_id,plan_id',
    					'plan_user.plan:id,plan',
    					'plan_user.user:id,first_name,last_name,email'
                    ])
    				->get([
    				    'id',
                        'payment_type_id',
                        'plan_user_id',
                        'date',
    				 	'start_date',
                        'finish_date',
                        'amount',
                        'total_paid',
                        'created_at'
    				]);

        return $bills->map(function ($bill) {
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
        });
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
}
