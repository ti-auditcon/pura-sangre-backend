<?php

namespace App\Console\Commands\Messages;

use App\Models\Users\User;
use Illuminate\Console\Command;
use App\Models\Users\Notification;
use App\Jobs\SendPushNotification;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send programed notifications';

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
        $notification = Notification::where('trigger_at', now()->startOfMinute()->format('Y-m-d H:i:s'))
                                    ->first();

        if ($notification) {
            $users_id = explode(',', $notification->users);

            $users = User::whereIn('id', $users_id)->get(['id', 'fcm_token']);

            foreach ($users as $user) {
                SendPushNotification::dispatch($user->fcm_token, $notification->title, $notification->body);
            }

            $notification->update(['sended' => 1]);
        }
    }
}
