<?php

namespace App\Console\Commands;

use App\Mail\ToExpireEmail;
use App\Models\Plans\PlanUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ToExpiredPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toexpire:plan';

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
        $plans_about_expired = PlanUser::whereFinishDate(toDay()->addDays(3))->get();
        foreach ($plans_about_expired as $planuser) {
            $user = $planuser->user;
            Mail::to($user->email)->send(new ToExpireEmail($user, $planuser));
        }
    }
}
