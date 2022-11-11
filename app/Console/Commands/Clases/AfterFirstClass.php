<?php

namespace App\Console\Commands\Clases;

use App\Models\Users\User;
use App\Models\Clases\Clase;
use Illuminate\Console\Command;
use App\Mail\SendFirstClassEmail;
use App\Models\Clases\Reservation;
use Illuminate\Support\Facades\Mail;

class AfterFirstClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clases:first';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to test users after her first consumed class';

    /**
     * $subject for email
     * 
     * @var string
     */
    protected $subject = 'Has finalizado tu primera clase!!';

    // /**
    //  * $message Body for email
    //  * @var string
    //  */
    // protected $message = "Esperamos como Equipo, 
    //     que tu primera Clase haya sido con todo,
    //      por lo general las primeras clases son las más duras,
    //     por eso te invitamos a seguir dandolo todo.
    //     Por nuestra parte queremos saber que tal fuimos contigo";

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
        $hour_clase = $this->roundToQuarterHour(now()->subHour())->format('H:i');

        $clase = Clase::where('date', today())
                      ->whereStartAt($hour_clase)
                      ->first('id');

        $users = User::join('reservations', 'users.id', '=', 'reservations.user_id')
                     ->where('reservations.clase_id', $clase->id)
                     ->where('users.status_user_id', 3)
                     ->where('reservations.reservation_status_id', 3)
                     ->get(['users.id', 'users.first_name', 'users.last_name', 'users.email']);

        foreach ($users as $user) {
            $count_reservs = Reservation::where('user_id', $user->id)
                                        ->where('reservation_status_id', 3)
                                        ->count('id');

            if ($count_reservs == 1) {
                $email = collect([
                    'subject' => $this->subject,
                    'first_name' => $this->first_name,
                ]);

                Mail::to($user->email)->send(new SendFirstClassEmail($email));

                // Agregar enviar PUSH
            }
        }
    }

    /**
     * Get the rounded minute from an specific time,
     * useful in case of server trigger after the specific hour and minute
     * 
     * @param  Carbon\Carbon $time
     * 
     * @return Carbon\Carbon
     */
    public function roundToQuarterHour($time) {
        $minutes = date('i', strtotime($time));
        
        return $time->setTime($time->format('H'), $minutes - ($minutes % 15));
    }
}
