<?php

use Faker\Generator as Faker;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Stage;
use App\Models\Exercises\ExerciseStage;
use App\Models\Exercises\Statistic;


$factory->define(Exercise::class, function (Faker $faker) {
    return [
        'exercise' => $faker->word,
    ];
});

$factory->define(Stage::class, function (Faker $faker) {
    return [
        'stage' => $faker->word,
    ];
});

$factory->define(ExerciseStage::class, function (Faker $faker) {
    return [
        'exercise_id' => Exercise::all()->random()->id,
        'stage_id' => Stage::all()->random()->id,
    ];
});

$factory->define(Statistic::class, function (Faker $faker) {
    return [
        'statistic' => $faker->word,
    ];
});
