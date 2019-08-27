<?php

namespace App\Console\Commands;

use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        // now()->addHour()
        // Carbon::create(1975, 12, 25, 13, 00, 16)
        $hora_clase = $this->roundToQuarterfHour(now()->addHour())->format('H:i');
        
        $reservations = Reservation::where('reservation_status_id', 1)
                                   ->join('users', 'users.id', '=', 'reservations.user_id')
                                   ->join('clases', 'clases.id', '=', 'reservations.clase_id')
                                   ->join('clase_types', 'clase_types.id', '=', 'clases.clase_type_id')
                                   ->where('clases.start_at', $hora_clase)
                                   ->where('clases.date', toDay())
                                   ->get([
                                        'reservations.id', 'users.first_name', 'users.fcm_token', 'clase_types.clase_type',
                                        'clases.start_at'
                                    ]);

        foreach ($reservations as $resrv) {
            $title = $resrv->first_name . ' recuerda confirmar ahora';

            $body = 'Tienes una clase de ' . strtoupper($resrv->clase_type) . ' las ' . Carbon::parse($resrv->start_at)->format('h:i') . ', no te olvides confirmar o tu reserva sera eliminada en 15 minutos';

            // $this->notification($resrv->fcm_token, $title, $body);
        }
    }

    /**
     * Get the rounded minute from an specific time,
     * useful in case of server trigger after the specific hour and minute
     * 
     * @param  Carbon\Carbon $time
     * @return Carbon\Carbon
     */
    public function roundToQuarterfHour($time) {
        $minutes = date('i', strtotime($time));
        
        return $time->setTime($time->format('H'), $minutes - ($minutes % 15));
    }

    /**
     * [notification description]
     * @param  [type] $token [description]
     * @param  [type] $title [description]
     * @param  [type] $body  [description]
     * @return [type]        [description]
     */
    public function notification($token, $title, $body)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token = $token;

        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to' => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData,
        ];

        $headers = [
            'Authorization: key=AAAAEWU-ai4:APA91bFCm4Yxb9Hh4m8te_RCrvk8HY_IaR9LfXUGQcuClcFs5Fy6a7d4irPoSbcIi48ei6kNnvodQCUua1Mb8h9QKEFtusbeCAcPpEAwSXxbKIjyrKDl3Ncm_tTFfnoQmqT9ZCD2hPSH',
            'Content-Type: application/json',
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
