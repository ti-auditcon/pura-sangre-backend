<?php

use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Bills\Bill;
use Faker\Generator as Faker;
use App\Models\Plans\PlanUser;
use App\Models\Bills\Installment;
use App\Models\Bills\PaymentStatus;

$factory->define(Plan::class, function (Faker $faker) {
    return [
      'plan' => $faker->word,
      'class_numbers' => $faker->numberBetween($min = 12, $max = 24),
    ];
});

$factory->define(PlanUser::class, function (Faker $faker) {

$starts_at = Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now')->getTimeStamp());
$ends_at= Carbon::createFromFormat("Y-n-j G:i:s", $starts_at)->addMonths($faker->numberBetween( 1, 2, 3));

    return [
      'start_date' => $starts_at,
      'finish_date' => $ends_at,
      'amount' => $faker->randomElement($array = array ('40000', '50000', '160000', '23000', '80000')),
      'plan_state' => $faker->randomElement($array = array ('activo', 'inactivo', 'pendiente', 'completado', 'cancelado')),
      'plan_id' => Plan::all()->random()->id,
    ];
});

$factory->define(Installment::class, function (Faker $faker) {
    return [
        'bill_id' => Bill::all()->random()->id,
        'payment_status_id' => PaymentStatus::all()->random()->id,
        'commitment_date' => $faker->date($format = 'm-d-Y', $max = 'now'),
        'payment_date' => $faker->date($format = 'm-d-Y', $max = 'now'),
        'expiration_date' => $faker->date($format = 'm-d-Y', $max = 'now'),
        'amount' => $faker->randomNumber($nbDigits = 7, $strict = false),
    ];
});
