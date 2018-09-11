<?php

use Carbon\Carbon;
use App\Models\Users\User;
use App\Models\Plans\Plan;
use Faker\Generator as Faker;
use App\Models\Plans\PlanUser;

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
      // 'start_date' => $faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now'),
      // 'finish_date' => $faker->dateTimeInInterval($startDate = PlanUser::$start_date, $interval = '+ 30 days'),
      'start_date' => $starts_at,
      'finish_date' => $ends_at,
      'amount' => $faker->randomElement($array = array ('40000', '50000', '160000', '23000', '80000')),
      'plan_state' => $faker->randomElement($array = array ('activo', 'inactivo', 'pendiente', 'completado', 'cancelado')),
      // 'discount_id' => $faker->numberBetween($min = 1, $max = 3),
      'plan_id' => Plan::all()->random()->id,
      'user_id' => User::all()->random()->id,
    ];
});
