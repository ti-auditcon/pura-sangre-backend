<?php

namespace App\Console\Commands\Clases;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Settings\Setting;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use App\Services\PushNotificationService;

class ClasesSendPushes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:clases:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications to users about their class reservations.';

    protected $pushNotificationService;

    /**
     * Create a new command instance.
     *
     * @param PushNotificationService $pushNotificationService
     * @return void
     */
    public function __construct(PushNotificationService $pushNotificationService)
    {
        parent::__construct();

        $this->pushNotificationService = $pushNotificationService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $settings = Setting::first(['id', 'minutes_to_send_notifications', 'minutes_to_remove_users']);

        $claseDateTime = $this->roundToMultipleOfFive(
            now()->copy()->addMinutes($settings->minutes_to_send_notifications)
        );

        $this->info('Clase date and time: ' . $claseDateTime->copy()->format('Y-m-d H:i:s'));

        $reservations = Reservation::join('users', 'users.id', '=', 'reservations.user_id')
            ->join('clases', 'clases.id', '=', 'reservations.clase_id')
            ->join('clase_types', 'clase_types.id', '=', 'clases.clase_type_id')
            ->where('reservation_status_id', ReservationStatus::PENDING)
            ->where('clases.date', $claseDateTime->copy()->format('Y-m-d H:i:s'))
            ->get([
                'reservations.id as id', 'reservation_status_id',
                'users.first_name', 'users.fcm_token',
                'clase_types.clase_type',
                'clases.start_at', 'clases.date'
            ]);

        $this->info('Reservations: ' . $reservations->count());

        foreach ($reservations as $reservation) {
            $title = 'Confirma tu clase de ' . strtoupper($reservation->clase_type);

            $body = 'Clase ' . strtoupper($reservation->clase_type) . ' - ' 
                . Carbon::parse($reservation->start_at)->format('H:i') 
                . 'hrs. Confirma o será eliminada en '
                . $this->minutesOfDifferenceBetweenPushesAndRemove($settings)
                . ' minutos.';

            $this->pushNotificationService->sendPushNotification(
                [$reservation->fcm_token], // Pass an array of tokens (even single)
                $title,
                $body
            );
        }
    }

    /**
     * @param   Setting|collection  $settings
     *
     * @return  int
     */
    public function minutesOfDifferenceBetweenPushesAndRemove($settings)
    {
        $minutes_of_difference = $settings->minutes_to_send_notifications - $settings->minutes_to_remove_users;

        return $minutes_of_difference > 0 ? $minutes_of_difference : 0;
    }

    /**
     * Get the rounded minute from an specific time,
     * useful in case of server trigger after the specific hour and minute
     * Also add the 0
     *
     * @param   Carbon\Carbon|string  $time
     *
     * @return  Carbon\Carbon
     */
    public function roundToMultipleOfFive($time) {
        $minutes = date('i', strtotime($time));

        return $time->setTime($time->format('H'), $minutes - ($minutes % 5));
    }

    /**
     * Get the rounded minute from an specific time,
     * useful in case of server trigger after the specific hour and minute
     * 
     * @param  Carbon\Carbon|string  $time
     *
     * @return Carbon\Carbon
     */
    public function roundToQuarterfHour($time) {
        $minutes = date('i', strtotime($time));
        
        return $time->setTime($time->format('H'), $minutes - ($minutes % 15));
    }
}
