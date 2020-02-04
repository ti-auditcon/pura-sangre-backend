<?php

namespace App\Console\Commands\Reports;

use App\Models\Users\User;
use Illuminate\Console\Command;
use App\Models\Users\StatusUser;

class NoConvertUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:noconverted';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email to no converted users';

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
        $no_converted_users = User::join('users.plan_user', 'users.id', '=', 'plan_user.user_id')
                                  ->where('users.status_user_id', StatusUser::INACTIVE)
                                  ->where('plan_user.finish_date', today()->subWeek())
                                  ->get([ 
                                    'users.id', 'users.first_name', 'users.email'
                                  ]);

        foreach($no_converted_users as $user) {
            Mail::to($user->email)->send(new GoneAwayUserEmail($user->first_name));
        }
    }
}
