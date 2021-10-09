<?php

namespace App\Console\Commands\Clases;

use Carbon\Carbon;
use App\Models\Clases\Clase;
use Illuminate\Console\Command;
use App\Models\Settings\Setting;
use App\Jobs\SendPushNotification;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;

class PushClases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clases:push';

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
    {@
            $settings = Setting::first(['id', 'minutes_for_confirmation_clases', 'minutes_to_remove_users']);

            $current_dateTime = now()->copy();

            $clase_hour = $this->roundToMultipleOfFive(
                $current_dateTime->copy()->addMinutes($settings->minutes_for_confirmation_clases)
            );

            dd(Reservation::join('users', 'users.id', '=', 'reservations.user_id')
                                        ->join('clases', 'clases.id', '=', 'reservations.clase_id')
                                        ->join('clase_types', 'clase_types.id', '=', 'clases.clase_type_id')->get());

            $reservations = Reservation::join('users', 'users.id', '=', 'reservations.user_id')
                                        ->join('clases', 'clases.id', '=', 'reservations.clase_id')
                                        ->join('clase_types', 'clase_types.id', '=', 'clases.clase_type_id')
                                        ->where('reservation_status_id', ReservationStatus::PENDING)
                                        ->whereNull('reservations.deleted_at')
                                        // start_at deberia tener la zona horaria del box y no utc
                                        ->where('clases.start_at', Carbon::parse($clase_hour)->copy()->format('H:i:s'))
                                        ->where('clases.date', $current_dateTime->copy()->format('Y-m-d')) /** Keep the timezone day all the time */
                                        ->get([
                                            'reservations.id', 'reservation_status_id',
                                            'users.first_name', 'users.fcm_token',
                                            'clase_types.clase_type',
                                            'clases.start_at', 'clases.date'
                                        ]);

            foreach ($reservations as $reservation) {
                $title = $reservation->first_name . ' recuerda confirmar ahora';

                $body = 'Tienes una clase de ' . strtoupper($reservation->clase_type) .
                        ' a las ' . Carbon::parse($reservation->start_at)->format('H:i') .
                        ', no te olvides confirmar o tu reserva sera eliminada en ' .
                        $this->minutesOfDifferenceBetweenPushesAndRemove($settings) . ' minutos';

                SendPushNotification::dispatch($reservation->fcm_token, $title, $body);
            }

            DB::purge('tenant');
    }


    /**
     *  [minutesOfDifferenceBetweenPushesAndRemove description]
     *
     *  @param   collection
     *
     *  @return  int
     */
    public function minutesOfDifferenceBetweenPushesAndRemove($box_parameters)
    {
        $minutes_of_difference = $box_parameters->minutes_for_confirmation_clases - $box_parameters->minutes_to_remove_users;

        return $minutes_of_difference > 0 ? $minutes_of_difference : 0;
    }

    /**
     *  Get the rounded minute from an specific time,
     *  useful in case of server trigger after the specific hour and minute
     *  Also add the 0
     *
     *  @param   Carbon\Carbon|string  $time
     *
     *  @return  Carbon\Carbon
     */
    public function roundToMultipleOfFive($time) {
        $minutes = date('i', strtotime($time));

        return $time->setTime($time->format('H'), $minutes - ($minutes % 5));
    }

    /**
     *  We need to tell the user the correct start class hour, it depends of the user timezone
     *
     *  @param   [type]  $reservation  [$reservation description]
     *
     *  @return  Carbon
     */
    public function claseTimeForTimezonedUser($reservation, $sport_center_timezone)
    {
        $hours_of_difference = $this->hoursOfDifferenceBetweenSportCenterTimezoneAndOtherTimezone(
            $sport_center_timezone,
            $reservation->timezone
        );

        return Carbon::parse($reservation->start_at)
                        ->addHours($hours_of_difference)
                        ->format('H:i');
    }

    /**
     * [hoursOfDifferenceBetweenSportCenterTimezoneAndOtherTimezone description]
     *
     *  @param   string  $sport_center_timezone
     *  @param   string  $other_timezone
     *
     *  @return  int                             Could be a possitive or negative value
     */
    public function hoursOfDifferenceBetweenSportCenterTimezoneAndOtherTimezone($sport_center_timezone, $other_timezone)
    {
        return today($other_timezone)->diffInHours(today($sport_center_timezone), false);
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
     * Prepare and send PUSH Notification
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
