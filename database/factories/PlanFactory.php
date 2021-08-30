<?php

use Carbon\Carbon;
use App\Models\Bills\Bill;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use Faker\Generator as Faker;
use App\Models\Plans\PlanUser;
use App\Models\Plans\PlanPeriod;
use App\Models\Plans\PlanStatus;
use App\Models\Bills\Installment;
use App\Models\Plans\PlanUserFlow;
use App\Models\Bills\PaymentStatus;
use App\Models\Plans\PostponePlan;

$factory->define(PlanUser::class, function (Faker $faker) {
    return [
        'start_date'     => $faker->dateTimeBetween('- 2 months', 'now'),
        'finish_date'    => $faker->dateTimeBetween('now', '+ 2 months'),
        'counter'        => 20,
        'plan_status_id' => PlanStatus::ACTIVE,
        'user_id'        => factory(User::class)->create()->id,
        'plan_id'        => factory(Plan::class)->create()->id,
    ];
});

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'plan'           => $faker->word,
        'class_numbers'  => $faker->numberBetween($min = 12, $max = 24),
        'description'    => $faker->paragraph,
        'plan_period_id' => 1,
        'has_clases'     => true,
        'amount'         => 19990,
        'custom'         => false,
        'convenio'       => false,
        'contractable'   => true,
        'daily_clases'   => 1,
        'plan_status_id' => PlanStatus::ACTIVO
    ];
});

$factory->define(PlanUserFlow::class, function(Faker $faker)  {
    $plan = factory(Plan::class)->create();

    $starts_at = Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '-14 months', $endDate = '-1 weeks')->getTimeStamp());

    $ends_at= Carbon::createFromFormat("Y-n-j G:i:s", $starts_at)
                    ->addMonths($plan->plan_period->period_number ?? 1)
                    ->subDay();

    return [
        'start_date'     => $starts_at,
        'finish_date'    => $ends_at,
        'amount'         => 19990,
        'payment_date'   => null,
        'bill_pdf'       => null,
        'sii_token'      => null,
        'counter'        => 10,
        'plan_status_id' => 1,
        'plan_id'        => $plan->id,
        'user_id'        => factory(User::class)->create()->id,
        'observations'   => $faker->sentence,
        'paid'           => false
    ];
});

$factory->define(PostponePlan::class, function(Faker $faker) {
    return [
        'plan_user_id' => factory(PlanUser::class)->create()->id,
        'start_date'   => $faker->date(),
        'finish_date'  => $faker->date(),
        'days'         => $faker->randomDigit,
        'revoked'      => false
    ];
});