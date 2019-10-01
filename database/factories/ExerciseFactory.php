<?php

use App\Models\Wods\Stage;
use App\Models\Clases\Clase;
use Faker\Generator as Faker;
use App\Models\Wods\StageType;
use App\Models\Clases\ClaseStage;
use App\Models\Exercises\Exercise;
use App\Models\Exercises\Statistic;
use App\Models\Exercises\ExerciseStage;


$factory->define(Exercise::class, function (Faker $faker) {
    return [
        'exercise' => $faker->word,
    ];
});

$factory->define(Stage::class, function (Faker $faker) {
	$stage_type_id = StageType::all()->random()->id;
    if ($stage_type_id == 1) {
        $stage_name = 'WARM-UP';
    }
    elseif ($stage_type_id == 2) {
        $stage_name = 'SKILL';
    }
    elseif ($stage_type_id == 3) {
        $stage_name = 'WOD';
    }
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
