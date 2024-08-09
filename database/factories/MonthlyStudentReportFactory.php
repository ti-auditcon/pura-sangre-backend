<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Models\Reports\MonthlyStudentReport;

$factory->define(MonthlyStudentReport::class, function (Faker $faker) {
    return [
        'year' => $faker->numberBetween(2019, 2025),
        'month' => $faker->numberBetween(1, 12),
        'active_students_start' => $faker->numberBetween(200, 400),
        'active_students_end' => $faker->numberBetween(200, 400),
        'dropouts' => $faker->numberBetween(10, 100),
        'new_students' => $faker->numberBetween(10, 100),
        'dropout_percentage' => $faker->randomFloat(2, 0, 25),
        'new_students_percentage' => $faker->randomFloat(2, 0, 10),
        'turnaround' => $faker->numberBetween(-10, 50),
        'previous_month_difference' => $faker->numberBetween(-50, 50),
        'growth_rate' => $faker->randomFloat(2, -10, 10),
        'retention_rate' => $faker->randomFloat(2, 70, 100),
        'churn_rate' => $faker->randomFloat(2, 0, 30),
    ];
});

// factory(\App\Models\Reports\MonthlyStudentReport::class, 50)->create();