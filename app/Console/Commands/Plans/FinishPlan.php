<?php

namespace App\Console\Commands\Plans;

use App\Jobs\SendPushNotification;
use Illuminate\Support\Arr;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
use App\Models\Clases\Reservation;
use Illuminate\Support\Facades\App;
use App\Models\Clases\ReservationStatus;

class FinishPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:plans:finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish all plans with 0 or less quoas left in the current plan.';

        /**
     *  Messages for title on PUSH notifications
     *
     *  @var  array
     */
    protected $finishPlanTitles = [
        "El plan finalizó.",
        "Terminó tu plan.",
        "Se ha acabado el plan.",
        "Plan finalizado, vamos no te detengas.",
        "Esta todo bien, solo renueva tu plan."
    ];

    /**
     *  Messages for messages on PUSH notifications
     *
     *  @var  array
     */
    protected $finishPlanMessages = [
        "😎 Has terminado las clases de tu plan, estás en 🔥🔥 sigue entrenando con todo",
        "Sigue motivado 🔥🔥, y tu mente hará el resto",
        "Entreno 🏋️‍♀️🔥🏋️, luego existo 🤔",
        "Hagas lo que hagas, no dejes de entrenar 🏋️",
        "Tomemos un poco de aire, okey sigamos con todo 💪",
        "Estás más cerca de tus objetivos, sigamos entrenando",
    ];

    /**
     *  Array for the tokens which are going to be sended
     *
     *  @var  array
     */
    private $fcmTokens = [];

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
        try {
            $plansOfUsers = PlanUser::join('plans', 'plans.id', 'plan_user.plan_id')
                        ->join('users', 'users.id', 'plan_user.user_id')
                        ->where('plan_user.plan_status_id', PlanStatus::ACTIVE)
                        ->where('plans.class_numbers', '>', 0)
                        ->where('plan_user.counter', '<=', 0)
                        ->get([
                            'plan_user.id', 'plan_user.user_id', 'plan_user.plan_id', 'plan_user.counter',
                            'plan_user.start_date', 'plan_user.finish_date', 'plan_user.plan_status_id',
                            'plans.id as planId', 'plans.class_numbers', 'plans.plan',
                            'users.id as userId', 'users.fcm_token'
                        ]);

            $this->info("Plan numbers to be iterated: " . count($plansOfUsers));

            foreach ($plansOfUsers as $planUser) {
                if ($this->thereAreNoNextClassesWithPendingOrConfirmedStatusesFor($planUser)) {
                    $this->line("Plan with id: {$planUser->id} is going to be closed");

                    $planUser->finish();

                    $this->addFcmTokenToStack($planUser->fcm_token);
                } else {
                    $this->line("Plan with id: {$planUser->id} can't be closed yet");
                }
            }

        } catch (\Throwable $th) {
            dump($th->getMessage());
        }

        $this->sendPushNotifications();
    }

    /**
     * Add the given fcm token to the stack
     *
     * @param   string  $fcmToken
     *
     * @return  void
     */
    public function addFcmTokenToStack($fcmToken)
    {
        if (!in_array($fcmToken, $this->fcmTokens)) {
            $this->fcmTokens[] = $fcmToken;
        }
    }
    
    /**
     * Check if the given plan is associated to pending on days between today and finish plan date
     *
     * @param   PlanUser  $planUser
     *
     * @return  bool
     */
    public function thereAreNoNextClassesWithPendingOrConfirmedStatusesFor($planUser): bool
    {
        return Reservation::where('plan_user_id', $planUser->id)
                            ->join('clases', 'clases.id', '=', 'reservations.clase_id')
                            ->where('clases.date', '>=', today())
                            ->whereIn(
                                'reservation_status_id',
                                [ReservationStatus::PENDING, ReservationStatus::CONFIRMED]
                            )
                            ->doesntExist('id');
    }

    /**
     * Send the PUSH notifications to the users
     * with the fcm tokens in the stack
     * 
     * @return  void
     */
    public function sendPushNotifications()
    {
        if (count($this->fcmTokens) > 0 && App::environment('production')) {
            $pushes = array_chunk($this->fcmTokens, 1000);

            foreach ($pushes as $push) {
                $fcmUrl = config('services.firebase.url');

                $notification = [
                    'title' => Arr::random($this->finishPlanTitles),
                    'body'  => Arr::random($this->finishPlanMessages),
                    'sound' => true
                ];

                $fcmNotification = [
                    'registration_ids' => $push,
                    'notification'     => $notification,
                    'data'             => [
                        "message"  => $notification,
                        "moredata" => "no"
                    ],
                ];

                $headers = [
                    'Authorization: key=' . config('services.firebase.key'),
                    'Content-Type: application/json'
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
            }
        }
    }
}
