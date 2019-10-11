<?php

namespace App\Console\Commands;

use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanIncomeSummary;
use Illuminate\Console\Command;

class UpdateIncome extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:income';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // PlanIncomeSummary::where('year', 2019)->delete();
        $years = [2017, 2018, 2019];
        $plans = Plan::all();
        foreach ($years as $year) {
            PlanIncomeSummary::where('year', $year)->delete();
            for ($i = 1; $i < 13; $i++) {
                foreach ($plans as $plan) {
                    $amount = Bill::join('plan_user', 'plan_user.id', 'bills.plan_user_id')
                        ->where('plan_user.plan_id', $plan->id)
                        ->whereMonth('date', $i)
                        ->whereYear('date', $year)
                        ->get()
                        ->sum('amount');

                    $quantity = Bill::join('plan_user', 'plan_user.id', 'bills.plan_user_id')
                        ->where('plan_user.plan_id', $plan->id)
                        ->whereMonth('date', $i)
                        ->whereYear('date', $year)
                        ->count('bills.id');

                    if ($amount || $quantity) {
                        PlanIncomeSummary::create([
                            'plan_id' => $plan->id,
                            'amount' => $amount,
                            'quantity' => $quantity,
                            'month' => $i,
                            'year' => $year
                        ]);
                    }
                }
            }
        }
    }
}

                    // $income = new PlanIncomeSummary;
                    // $income->plan_id = $plan->id;
                    // $income->amount = $amount;
                    // $income->quantity = $quantity;
                    // $income->month = $i;
                    // $income->year = $year;
                    // $income->save();