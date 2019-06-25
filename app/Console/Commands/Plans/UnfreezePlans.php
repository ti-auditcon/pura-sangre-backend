<?php

namespace App\Console\Commands\Plans;

use Illuminate\Console\Command;

class UnfreezePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:unfreeze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unfreeze all the plans who has today the unfreeze date';

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
        //
    }
}
