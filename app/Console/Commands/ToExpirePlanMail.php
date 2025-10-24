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

        $this->info('Found ' . $plansCloseToExpire->count() . ' plans about to expire');

        foreach ($plansCloseToExpire as $index => $planuser) {
            $user = $planuser->user;

            try {
                // Usar colas - no necesita sleep() porque el worker maneja el timing
                Mail::to($user->email)->queue(new ToExpireEmail($user, $planuser));
                $this->info('Email queued for: ' . $user->email . ' (' . ($index + 1) . '/' . $plansCloseToExpire->count() . ')');
            } catch (\Throwable $error) {
                $this->error('Failed to queue email for: ' . $user->email . ' - ' . $error->getMessage());
            }
        }

        $this->info('Finished sending expiration emails');
    }
}
