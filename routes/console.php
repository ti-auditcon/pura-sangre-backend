<?php

use Pusher\Pusher;
use App\Models\Reports\Download;
use App\Events\DownloadCompleted;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('test:push', function () {
    try {

        $options = [
            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            'useTLS' => true
        ];
        
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            $options
        );

        // Log the details for debugging
        Log::info('Pusher configuration:', [
            'key' => config('broadcasting.connections.pusher.key'),
            'secret' => config('broadcasting.connections.pusher.secret'),
            'app_id' => config('broadcasting.connections.pusher.app_id'),
            'options' => $options
        ]);

        $pusher->trigger('downloads', 'download.completed', []);
        echo "Event triggered successfully.";
    } catch (\Throwable $th) {
        dd($th->getMessage());
    }
});