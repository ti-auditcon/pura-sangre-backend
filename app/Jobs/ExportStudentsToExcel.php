<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Exports\UsersExport;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExportStudentsToExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        Excel::store(new UsersExport, $filePath, 'public');
    }
}
