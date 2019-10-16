<?php

use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use Faker\Generator as Faker;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanPeriod;
use App\Models\Bills\Installment;
use App\Models\Bills\PaymentStatus;


$factory->define(PlanUser::class, function (Faker $faker) {
    // $plan = Plan::inRandomOrder()->where('id', $faker->numberBetween($min = 3, $max = 12))
    //                              ->first();
    $plan = factory(\App\Models\Plans\Plan::class)->create();

    $starts_at = Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '-14 months', $endDate = '-1 weeks')
                       ->getTimeStamp());

    $ends_at = Carbon::createFromFormat("Y-n-j G:i:s", $starts_at)
                    ->addMonths($plan->plan_period->period_number ?? 1)
                    ->subDay();

    if ($starts_at >= today()) {
        $plan_status_id = 3;
    }
   
    if ($ends_at >= today()) {
        $plan_status_id = 1;
    } 
   
    if ($ends_at < today()) {
        $plan_status_id = 4;
    }

    return [
        'start_date' => $starts_at,
        'finish_date' => $ends_at,
        'counter' => $plan->class_numbers *
                     optional($plan->plan_period)->period_number ?? 1 *
                     $plan->daily_clases,
        'plan_status_id' => $plan_status_id,
        'user_id' => factory(\App\Models\Users\User::class)->create(),
        'plan_id' => $plan->id,
    ];
});


$factory->define(Plan::class, function (Faker $faker) {
    return [
        'plan' => $faker->word,
        'class_numbers' => $faker->numberBetween($min = 12, $max = 24),
        'amount' => 45000,
        'custom' => 0,
        'daily_clases' => 1,
        'contractable' => 1,
        'convenio' => 0,
        'description' => $faker->sentence,
    ];
});
