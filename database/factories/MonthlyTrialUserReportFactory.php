<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Reports\MonthlyTrialUserReport;

$factory->define(MonthlyTrialUserReport::class, function (Faker $faker) {
    return [
        // 2021, 2022
        'year' => $faker->numberBetween(2019, 2025),
        'month' => $faker->numberBetween(1, 12),
        'plans_sold' => $faker->numberBetween(50, 200),
        'trial_users' => $faker->numberBetween(20, 100),
        'trial_classes_consumed' => $faker->numberBetween(20, 100),
        'trial_classes_taken_percentage' => $faker->randomFloat(2, 50, 100),
        'trial_conversion' => $faker->numberBetween(1, 20),
        'trial_convertion_percentage' => $faker->randomFloat(2, 0, 100),
        'trial_retention_percentage' => $faker->randomFloat(2, 0, 100),
        'inactive_users' => $faker->numberBetween(0, 50),
    ];
});

// factory(\App\Models\Reports\MonthlyTrialUserReport::class, 50)->create();
