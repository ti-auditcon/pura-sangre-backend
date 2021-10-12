<?php

namespace App\Console\Commands\Clases;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Settings\Setting;
use App\Jobs\SendPushNotification;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;

class ClasesSendPushes extends Command
{
    /**
     *  The name and signature of the console command.
     *
     *  @var  string
     */
    protected $signature = 'purasangre:clases:send-notifications';

    /**
     *  The console command description.
     *
     *  @var  string
     */
    protected $description = 'Command description';

    /**
     *  Create a new command instance.
     *
     *  @return  void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  Execute the console command.
     *
     *  @return  mixed
     */
    public function handle()
    {
        $settings = Setting::first(['id', 'minutes_to_send_notifications', 'minutes_to_remove_users']);

        $current_dateTime = now()->copy();

        $clase_hour = $this->roundToMultipleOfFive(
            $current_dateTime->copy()->addMinutes($settings->minutes_to_send_notifications)
        );

        $reservations = Reservation::join('users', 'users.id', '=', 'reservations.user_id')
                                    ->join('clases', 'clases.id', '=', 'reservations.clase_id')
                                    ->join('clase_types', 'clase_types.id', '=', 'clases.clase_type_id')
                                    ->where('reservation_status_id', ReservationStatus::PENDING)
                                    ->whereNull('reservations.deleted_at')
                                    ->where('clases.start_at', Carbon::parse($clase_hour)->copy()->format('H:i:s'))
                                    /** Keep the timezone day all the time */
                                    ->where('clases.date', $current_dateTime->copy()->format('Y-m-d'))
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
                    'hrs. No te olvides confirmar o tu reserva será eliminada en ' .
                    $this->minutesOfDifferenceBetweenPushesAndRemove($settings) . ' minutos';

            SendPushNotification::dispatch($reservation->fcm_token, $title, $body);
        }
    }


    /**
     *  @param   Setting|collection  $settings
     *
     *  @return  int
     */
    public function minutesOfDifferenceBetweenPushesAndRemove($settings)
    {
        $minutes_of_difference = $settings->minutes_to_send_notifications - $settings->minutes_to_remove_users;

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
     *  @param   Reservation  $reservation
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
     *  Get the rounded minute from an specific time,
     *  useful in case of server trigger after the specific hour and minute
     * 
     *  @param  Carbon\Carbon|string  $time
     *
     *  @return Carbon\Carbon
     */
    public function roundToQuarterfHour($time) {
        $minutes = date('i', strtotime($time));
        
        return $time->setTime($time->format('H'), $minutes - ($minutes % 15));
    }
}