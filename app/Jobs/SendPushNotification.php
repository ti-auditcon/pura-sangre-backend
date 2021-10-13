<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
     *  FCM token from Firebase
     * 
     *  @var string
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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($token, $title, $body)
    {
        $this->token = $token;
        
        $this->title = $title;
        
        $this->body = $body;
    }

        /**
     *  The fcm_token runs as address to know to who send the PUSH notification
     *
     *  @return  string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fcmUrl = env('FIREBASE_CLOUD_MESSAGING_URL', 'https://fcm.googleapis.com/fcm/send');

        $notification = ['title' => $this->title, 'body' => $this->body, 'sound' => true];
        
        $fcmNotification = [
            'to'           => $this->token, //single token
            'notification' => $notification,
            'data'         => $notification
        ];

        $headers = [
            'Authorization: key=' . env('FIREBASE_AUTHORIZATION_KEY', 'AAAAEWU-ai4:APA91bFCm4Yxb9Hh4m8te_RCrvk8HY_IaR9LfXUGQcuClcFs5Fy6a7d4irPoSbcIi48ei6kNnvodQCUua1Mb8h9QKEFtusbeCAcPpEAwSXxbKIjyrKDl3Ncm_tTFfnoQmqT9ZCD2hPSH'),
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        return true;
    }
}
