<?php

namespace App\Jobs;

use Carbon\Carbon;
use Pusher\Pusher;
use Illuminate\Bus\Queueable;
use App\Exports\PaymentsExcel;
use App\Models\Reports\Download;
use App\Events\DownloadCompleted;
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

    protected $download;

    public function __construct(Download $download)
    {
        $this->download = $download;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileName = Carbon::now()->format('d-m-Y') . '_pagos.xlsx';
        $filePath = 'downloads/' . $fileName;

        try {
            // Excel::store(new PaymentsExcel, $filePath, 'public');

            // $this->download->update([
            //     'file_name' => $fileName,
            //     'status'    => 'completado',
            //     'url'       => Storage::disk('public')->url($filePath),
            //     'size'      => Storage::disk('public')->size($filePath),
            // ]);

            $this->completedPush();
        } catch (\Throwable $th) {
            Log::error('Export failed: ' . $th->getMessage());
            $this->download->update([
                'status' => 'fallido'
            ]);
        }
    }
}
