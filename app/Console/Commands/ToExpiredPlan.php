<?php

namespace App\Console\Commands;

use App\Mail\ToExpireEmail;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use App\Models\Plans\PlanStatus;
use Illuminate\Support\Facades\Mail;

class ToExpiredPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:toexpire';

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
        $plans_to_expire = PlanUser::whereFinishDate(toDay()->addDays(3))
                                    ->wherePlanStatusId(PlanStatus::ACTIVE)
                                    ->get();

        foreach ($plans_to_expire as $planuser) {
            $user = $planuser->user;

            Mail::to($user->email)->send(new ToExpireEmail($user, $planuser));
        }
    }
}
