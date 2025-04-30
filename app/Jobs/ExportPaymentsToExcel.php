<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Bills\Bill;
use Illuminate\Bus\Queueable;
use App\Models\Reports\Download;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExportPaymentsToExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10; // Delay retries for 10 seconds
    protected $download;

    public function __construct(Download $download)
    {
        $this->download = $download;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $fileName = Carbon::now()->format('d-m-Y') . '_pagos.csv';
        $filePath = 'downloads/' . $fileName;
        
        try {
            // Create a temporary in-memory file
            $tempHandle = fopen('php://temp', 'w+');
            
            // Write headers
            fputcsv($tempHandle, [
                'Fecha registro',
                'Alumno',
                'Correo',
                'Plan',
                'N° de Boleta',
                'Tipo de Pago',
                'Fecha Boleta',
                'Fecha Inicio plan',
                'Fecha término plan',
                'Total',
                'Total Pagado',
            ]);
            

            $bills = Bill::query()
                ->select([
                    'bills.id',
                    'bills.created_at',
                    'bills.date',
                    'bills.start_date',
                    'bills.finish_date',
                    'bills.amount',
                    'bills.total_paid',
                    'payment_types.payment_type',
                    'users.first_name',
                    'users.last_name',
                    'users.email',
                    'plans.plan as plan_name'
                ])
                ->leftJoin('payment_types', 'bills.payment_type_id', '=', 'payment_types.id')
                ->leftJoin('plan_user', 'bills.plan_user_id', '=', 'plan_user.id')
                ->leftJoin('plans', 'plan_user.plan_id', '=', 'plans.id')
                ->leftJoin('users', 'plan_user.user_id', '=', 'users.id')
                ->chunk(500, function ($bills) use ($tempHandle) {
                    foreach ($bills as $bill) {
                        fputcsv($tempHandle, [
                            Carbon::parse($bill->created_at)->format('d-m-Y'),
                            trim("{$bill->first_name} {$bill->last_name}") ?: 'sin informacion',
                            $bill->email ?: 'sin informacion',
                            $bill->plan_name ?: 'sin informacion',
                            $bill->id,
                            $bill->payment_type ?: 'sin informacion',
                            Carbon::parse($bill->date)->format('d-m-Y'),
                            Carbon::parse($bill->start_date)->format('d-m-Y'),
                            Carbon::parse($bill->finish_date)->format('d-m-Y'),
                            $bill->amount,
                            $bill->total_paid,
                        ]);
                    }
                });
            
            rewind($tempHandle); // Important! Reset pointer to start
            Storage::disk('public')->put($filePath, stream_get_contents($tempHandle));
            fclose($tempHandle);

            $this->download->update([
                'file_name' => $fileName,
                'status'    => 'completado',
                'url'       => Storage::disk('public')->url($filePath),
                'size'      => Storage::disk('public')->size($filePath),
            ]);

            Log::info('Exporting payments to Excel completed for download ID: ' . $this->download->id);
        } catch (\Throwable $th) {
            Log::error('Full error: ' . $th->getMessage() . "\n" . $th->getTraceAsString());

            $this->download->update([
                'status' => 'fallido',
            ]);
        }
    }
}
