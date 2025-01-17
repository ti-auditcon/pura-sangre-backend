<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Exports\PaymentsExcel;
use App\Models\Reports\Download;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
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
        $fileName = Carbon::now()->format('d-m-Y') . '_pagos.xlsx';
        $filePath = 'downloads/' . $fileName;

        try {
            // Start the process
            Log::info('Exporting payments to Excel started for download ID: ' . $this->download->id);

            // Stream Excel to storage to reduce memory usage
            Excel::store(new PaymentsExcel, $filePath, 'public');

            // Update download record
            $this->download->update([
                'file_name' => $fileName,
                'status'    => 'completado',
                'url'       => Storage::disk('public')->url($filePath),
                'size'      => Storage::disk('public')->size($filePath),
            ]);

            Log::info('Exporting payments to Excel completed for download ID: ' . $this->download->id);
        } catch (\Throwable $th) {
            Log::error('Exporting payments to Excel failed for download ID: ' . $this->download->id . '. Error: ' . $th->getMessage());

            $this->download->update([
                'status' => 'fallido',
            ]);

            throw $th; // Allow the job to retry
        }
    }
}
