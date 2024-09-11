<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\PushNotificationService;

class SendPushNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * FCM token from Firebase
     * 
     * @var string
    */
    protected $token;

    /** 
     * @var string [title notification] 
    */
    protected $title;

    /** 
     * @var string [body notification]
     */
    protected $body;

    protected $pushNotificationService;

    /**
     * Create a new job instance.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param PushNotificationService $pushNotificationService
     * @return void
     */
    public function __construct($token, $title, $body, PushNotificationService $pushNotificationService)
    {
        $this->token = $token;
        $this->title = $title;
        $this->body = $body;
        $this->pushNotificationService = $pushNotificationService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->pushNotificationService->sendPushNotification( 
                [$this->token],
                $this->title,
                $this->body
            );
        } catch (\Exception $e) {
            Log::error('Error sending push notification: ' . $e->getMessage());
        }
    }
}
