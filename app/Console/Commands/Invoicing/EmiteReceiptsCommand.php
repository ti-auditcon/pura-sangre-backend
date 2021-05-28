<?php

namespace App\Console\Commands\Invoicing;

use App\Models\Invoicing\DTE;
use Illuminate\Console\Command;
use App\Models\Plans\PlanUserFlow;
use App\Models\Invoicing\DTEErrors;

class EmiteReceiptsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoicing:emite-receipts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send DTEs to SII through Haulmer API';

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
        $bills = PlanUserFlow::where('created_at', '>=', today())
                                ->whereNull('sii_token')
                                ->get();

        foreach ($bills as $bill) {
            try {
                $dte = new DTE;
                $sii_response = $dte->issueReceipt($bill);
                if (isset($sii_response->TOKEN)) {
                    $bill->update(['sii_token' => $sii_response->TOKEN]);
                    continue;
                }
                new DTEErrors($sii_response);
            } catch (\Throwable $error) {
                new DTEErrors($error);
            }
        }
    }
}
