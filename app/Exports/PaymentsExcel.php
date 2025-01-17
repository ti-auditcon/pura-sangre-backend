<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class PaymentsExcel implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    /**
     * Define the query for fetching data.
     *
     * @return Builder
     */
    public function query()
    {
        return Bill::query()
            ->select([
                'bills.*',
                'payment_types.payment_type',
                'users.first_name',
                'users.last_name',
                'users.email',
                'plans.plan as plan_name'
            ])
            ->leftJoin('payment_types', 'bills.payment_type_id', '=', 'payment_types.id')
            ->leftJoin('plan_user', 'bills.plan_user_id', '=', 'plan_user.id')
            ->leftJoin('plans', 'plan_user.plan_id', '=', 'plans.id')
            ->leftJoin('users', 'plan_user.user_id', '=', 'users.id');
    }

    /**
     * Define the headings for the exported file.
     *
     * @return array
     */
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
            'Total',
            'Total Pagado',
        ];
    }

    /**
     * Map each row of data to the desired format.
     *
     * @param $bill
     * @return array
     */
    public function map($bill): array
    {
        return [
            Carbon::parse($bill->created_at)->format('d-m-Y'),
            trim("{$bill->first_name} {$bill->last_name}") ?: 'sin informacion',
            $bill->email ?: 'sin informacion',
            $bill->plan_name ?: 'sin informacion',
            $bill->payment_type ?: 'sin informacion',
            Carbon::parse($bill->date)->format('d-m-Y'),
            Carbon::parse($bill->start_date)->format('d-m-Y'),
            Carbon::parse($bill->finish_date)->format('d-m-Y'),
            $bill->amount,
            $bill->total_paid,
        ];
    }

    /**
     * Specify the chunk size for reading data.
     *
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000; // Process 1000 rows at a time
    }
}
