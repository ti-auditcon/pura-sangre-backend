<?php

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
    return [
      'start_date' => $faker->date($format = 'm-d-Y', $max = 'now'),
      'finish_date' => $faker->date($format = 'm-d-Y', $max = 'now'),
      'amount' => $faker->randomElement($array = array ('40000', '50000', '160000', '23000', '80000')),
      // 'discount_id' => $faker->numberBetween($min = 1, $max = 3),
      'plan_id' => Plan::all()->random()->id,
      'user_id' => User::all()->random()->id,
    ];
});
