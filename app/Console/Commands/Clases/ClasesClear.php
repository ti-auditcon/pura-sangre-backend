<?php

namespace App\Console\Commands\Clases;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Settings\Setting;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;

class ClasesClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:clases:clear';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Delete reservations for pending users reservations';

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
        $settings = Setting::first(['id', 'minutes_to_remove_users']);

        $claseDateTime = $this->roundMinutesToMultipleOfFive(
            now()->addMinutes($settings->minutes_to_remove_users)
        );

        $this->info("The dateTime being iterated is: " . $claseDateTime->copy()->format('Y-m-d H:i:s'));

        $reservations = Reservation::join('users', 'users.id', '=', 'reservations.user_id')
                                    ->join('clases', 'clases.id', '=', 'reservations.clase_id')
                                    ->join('clase_types', 'clase_types.id', '=', 'clases.clase_type_id')
                                    ->leftJoin('plan_user', 'plan_user.id', '=', 'reservations.plan_user_id')
                                    ->where('reservation_status_id', ReservationStatus::PENDING)
                                    ->where('clases.date', $claseDateTime->format('Y-m-d H:i:s'))
                                    ->get([
                                        'reservations.id', 'reservation_status_id', 'reservations.plan_user_id',
                                        'users.first_name', 'users.fcm_token',
                                        'clase_types.clase_type',
                                        'clases.start_at', 'clases.date',
                                        'plan_user.id as planUserId', 'plan_user.counter'
                                    ]);
                     
        foreach ($reservations as $reservation) {
            $reservation->delete();
        }
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
    public function roundMinutesToMultipleOfFive($time) {
        $minutes = date('i', strtotime($time));

        return $time->setTime($time->format('H'), $minutes - ($minutes % 5));
    }
}
