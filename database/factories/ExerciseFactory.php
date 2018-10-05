<?php

use App\Models\Clases\Clase;
use App\Models\Clases\ClaseStage;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\ExerciseStage;
use App\Models\Exercises\Stage;
use App\Models\Exercises\StageType;
use App\Models\Exercises\Statistic;
use Faker\Generator as Faker;


$factory->define(Exercise::class, function (Faker $faker) {
    return [
        'exercise' => $faker->word,
    ];
});

$factory->define(Stage::class, function (Faker $faker) {
	$stage_type_id = StageType::all()->random()->id;
	$stage_name = StageType::where('id', $stage_type_id)->pluck('stage_type');
    return [
        'stage_type_id' => $stage_type_id,
        'name' => $stage_name,
        'description' => $faker->text,
        'star' => 0,
    ];
});

$factory->define(ClaseStage::class, function (Faker $faker) {
    return [
        'clase_id' => Clase::all()->random()->id,
        'stage_id' => Stage::all()->random()->id,
    ];
});

// $factory->define(ExerciseStage::class, function (Faker $faker) {
//     return [
//         'exercise_id' => Exercise::all()->random()->id,
//         'stage_id' => Stage::all()->random()->id,
//         'repetitions' => $faker->numberBetween( 1, 6),
//         'round' => $faker->numberBetween(1, 2)
//     ];
// });

$factory->define(Statistic::class, function (Faker $faker) {
    return [
        'statistic' => $faker->word,
    ];
});
