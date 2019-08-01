<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Clases\Reservation;

class PushClases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:clases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $hora_clase = now()->startOfHour()->addHour();
        $reservations = Reservation::where('reservation_status_id', 1)
                                   ->join('clases', 'clases.id', '=', 'reservations.clase_id')
                                   ->where('clases.start_at', $hora_clase)
                                   ->where('clases.date', toDay())
                                   ->get();

        foreach ($reservations as $resrv){
            $title = $resrv->user->first_name.' recuerda confirmar';
            $body = 'Recuerda que tienes una clase a las '. $hora_clase->format('H:i').', no te olvides confirmarla';
            $this->notification($resrv->user->fcm_token, $title, $body);
        }
    }

    public function notification($token, $title, $body)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token=$token;

        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => true,
        ];
        
        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $headers = [
            'Authorization: key=AAAAEWU-ai4:APA91bFCm4Yxb9Hh4m8te_RCrvk8HY_IaR9LfXUGQcuClcFs5Fy6a7d4irPoSbcIi48ei6kNnvodQCUua1Mb8h9QKEFtusbeCAcPpEAwSXxbKIjyrKDl3Ncm_tTFfnoQmqT9ZCD2hPSH',
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
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
