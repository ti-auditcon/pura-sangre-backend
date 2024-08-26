<?php

use Pusher\Pusher;
use App\Models\Users\User;
use Illuminate\Support\Str;
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
        if (!app()->environment('local')) {
            return;
        }
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

Artisan::command('purasangre:new-user {--email=} {--pass=}', function ($email = null, $pass = null) {
    if (!app()->environment('local')) {
        $this->info('No puede realizar este comando en producción');

        return;
    }
    
    try {
        $user = User::create([
            'email' => $email ?? 'test' . rand(1, 9999) .  '@mail.com',
            'password' => bcrypt($pass ?? '123123'),
            'first_name' => 'Test',
            'last_name' => 'User',
            'birthdate' => now()->subYears(20),
            'since' => now()->subYears(2),
            'gender' => 'otro',
            'address' => 'Test address',
        ]);

        $this->info('Usuario creado con correo: ' . $user->email);
    } catch (\Throwable $th) {
        dd($th->getMessage());
    }
});