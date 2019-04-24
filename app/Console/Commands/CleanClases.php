<?php

namespace App\Console\Commands;

use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use Illuminate\Console\Command;

class CleanClases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:clase';

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
        $clase = Clase::where('date', today())->whereStartAt(now()->addHour()->startOfHour())
            ->first();
        if ($clase) {
            $reservations = Reservation::whereClaseId($clase->id)->whereReservationStatusId(1)->get();
            foreach ($reservations as $reserv) {
                $reserv->delete();
            }
        }
    }
}
