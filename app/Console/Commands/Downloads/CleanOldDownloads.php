<?php

namespace App\Console\Commands\Downloads;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Reports\Download;
use Illuminate\Support\Facades\Storage;

class CleanOldDownloads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:downloads:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete downloads older than two month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starting cleanup of old downloads...');

        // Fetch downloads older than one month
        $oldDownloads = Download::where('created_at', '<', Carbon::now()->subMonths(2))->get();

        $deletedFiles = 0;
        $deletedRecords = 0;

        foreach ($oldDownloads as $download) {
            // Attempt to delete the file
            if (Storage::disk('public')->exists('downloads/' . $download->file_name)) {
                Storage::disk('public')->delete('downloads/' . $download->file_name);
                $deletedFiles++;
            }

            // Delete the database record
            $download->delete();
            $deletedRecords++;
        }

        $this->info("Cleanup complete. Deleted {$deletedFiles} files and {$deletedRecords} database records.");

        return 0;
    }
}
