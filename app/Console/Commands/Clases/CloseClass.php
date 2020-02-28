<?php

namespace App\Console\Commands\Clases;

use App\Models\Clases\Clase;
use Illuminate\Console\Command;

class CloseClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clases:close';

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
     * @return mixed
     */
    public function handle()
    {
        $clases = Clase::where('date', today())
                       ->where('start_at', now()->startOfHour())
                       ->get();
                       
        if (count($clases) != 0) {
            foreach ($clases as $clase) {
                foreach ($clase->reservations as $reservation) {
                    if ($reservation->reservation_status_id == 1) {
                        $reservation->reservation_status_id = 4;
                        $reservation->save();
                    }
                    if ($reservation->reservation_status_id == 2) {
                        $reservation->reservation_status_id = 3;
                        $reservation->save();
                    }
                }
            }
        }
    }
}
