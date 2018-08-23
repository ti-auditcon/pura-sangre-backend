<?php

use Faker\Generator as Faker;
use App\Models\Plans\Plan;
use App\Models\Plans\Discount;
use App\Models\Plans\PlanUser;

$factory->define(Plan::class, function (Faker $faker) {
    return [
        'plan' => $faker->word,
        'class_numbers' => $faker->numberBetween($min = 12, $max = 24),
    ];
});

$factory->define(PlanUser::class, function (Faker $faker) {
    return [
        // 'discount_id' => $faker->numberBetween($min = 1, $max = 3),
        'plan_id' => Plan::all()->random()->id,
        'start_time' => $faker->date($format = 'm-d-Y', $max = 'now'),
        'finish_time' => $faker->date($format = 'm-d-Y', $max = 'now'),
    ];
});

// $factory->define(Discount::class, function (Faker $faker) {
//     return [
//         // 'plan' => $faker->word,
//         // 'class_numbers' => $faker->randomElement($array = array (12, 24)),
//     ];
// });
