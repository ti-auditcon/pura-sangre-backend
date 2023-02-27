<?php

namespace App\Console\Commands\Clases;

use Illuminate\Console\Command;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;

class CloseClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clases:close';

    /**
     * The minutes of difference between the start of the class, and the moment that the list has to be pass
     *
     * @var integer
     */
    public const MINUTES_TO_PASS_LIST = 15;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status classes every hour, change status to users from confirmado to consumida';

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
     * - Get all reservations of those clases which class date is 15 minutes ago
     * - Change the status of the reservations:
     *   - If the reservation is pending, change to lost
     *   - If the reservation is confirmed, change to consumed
     *   - If the reservation is consumed, do nothing
     *
     * @return mixed
     */
    public function handle()
    {
        $claseDateTime = $this->roundToMultipleOfFive(
            now()->subMinutes(self::MINUTES_TO_PASS_LIST)
        );

        $reservations = Reservation::join('clases', 'clases.id', '=', 'reservations.clase_id')
            ->where('clases.date', $claseDateTime->format('Y-m-d H:i:s'))
            ->select([
                'reservations.id', 'reservations.reservation_status_id',
                'clases.id', 'clases.date'
                ])->get();

        foreach ($reservations as $reservation) {
            switch ($reservation->reservation_status_id) {
                case ReservationStatus::PENDING:
                    $reservation->reservation_status_id = ReservationStatus::LOST;
                    $reservation->save();
                    break;

                case ReservationStatus::CONFIRMED:
                    $reservation->reservation_status_id = ReservationStatus::CONSUMED;
                    $reservation->save();
                    break;                
            }
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
    public function roundToMultipleOfFive($time)
    {
        $minutes = date('i', strtotime($time));

        return $time->setTime($time->format('H'), $minutes - ($minutes % 5));
    }
}
