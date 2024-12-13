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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PusherTrait;

    public $tries = 3; 
    protected $download;

    public function __construct(Download $download)
    {
        Log::info('Exporting payments to Excel job started');
        $this->download = $download;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Exporting payments to Excel job started');
        $fileName = Carbon::now()->format('d-m-Y') . '_pagos.xlsx';
        $filePath = 'downloads/' . $fileName;

        try {
            Log::info('Exporting payments to Excel job started');
            $this->startPush();

            Log::info('Exporting payments to Excel job started');
            Excel::store(new PaymentsExcel, $filePath, 'public');

            Log::info('Exporting payments to Excel job started');
            $this->download->update([
                'file_name' => $fileName,
                'status'    => 'completado',
                'url'       => Storage::disk('public')->url($filePath),
                'size'      => Storage::disk('public')->size($filePath),
            ]);

            Log::info('Exporting payments to Excel job started');
            $this->completedPush();
        } catch (\Throwable $th) {
            Log::error('Exporting payments to Excel job failed: ' . $th->getMessage());

            $this->download->update([
                'status' => 'fallido'
            ]);
        }
    }
}
