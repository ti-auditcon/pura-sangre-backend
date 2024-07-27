<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Exports\UsersExport;
use Illuminate\Bus\Queueable;
use App\Models\Reports\Download;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExportStudentsToExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        $fileName = Carbon::now()->format('d-m-Y') . '_activos.xlsx';
        $filePath = 'downloads/' . $fileName;

        try {
            Excel::store(new UsersExport, $filePath, 'public');

            $this->download->update([
                'file_name' => $fileName,
                'status' => 'completado',
                'url' => Storage::disk('public')->url($filePath),
                'size' => Storage::disk('public')->size($filePath),
            ]);
            
        } catch (\Throwable $th) {
            $this->download->update([
                'status' => 'fallido'
            ]);
        }
    }
}
