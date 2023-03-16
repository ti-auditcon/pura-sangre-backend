<?php

namespace App\Console\Commands;

use App\Mail\ToExpireEmail;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
use Illuminate\Support\Facades\Mail;

class ToExpirePlanMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purasangre:mails:plans-to-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to user who has a plan about to expire';

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
        $plansCloseToExpire = PlanUser::where('finish_date', '>=', now()->addDays(3)->startOfDay())
                                    ->where('finish_date', '<=', now()->addDays(3)->endOfDay())
                                    ->where('plan_status_id', PlanStatus::ACTIVE)
                                    ->get();

        foreach ($plansCloseToExpire as $planuser) {
            $user = $planuser->user;

            Mail::to($user->email)->send(new ToExpireEmail($user, $planuser));
        }
    }
}
