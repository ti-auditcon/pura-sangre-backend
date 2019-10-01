<?php

namespace App\Console\Commands\Clases;

use App\Models\Clases\Clase;
use Illuminate\Console\Command;
use App\Models\Clases\Reservation;

class ClearClases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clases:clear';

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
        $hour_clase = $this->roundToQuarterHour(now()->addMinutes(45))->format('H:i');

        $clase = Clase::where('date', today())
                      ->whereStartAt($hour_clase)
                      ->first('id');
        
        if ($clase) {
            $reservations = Reservation::whereClaseId($clase->id)
                                       ->whereReservationStatusId(1)
                                       ->get();

            foreach ($reservations as $reserv) {
                $reserv->delete();
            }
        }
    }

    /**
     * Get the rounded minute from an specific time,
     * useful in case of server trigger after the specific hour and minute
     * 
     * @param  Carbon\Carbon $time
     * @return Carbon\Carbon
     */
    public function roundToQuarterHour($time) {
        $minutes = date('i', strtotime($time));
        
        return $time->setTime($time->format('H'), $minutes - ($minutes % 15));
    }
}
