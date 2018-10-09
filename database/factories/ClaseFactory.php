<?php

use App\Models\Users\User;
use Faker\Generator as Faker;
use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Exercises\Statistic;
// use App\Models\Clases\ClassStage;
use App\Models\Exercises\ExerciseStage;
use App\Models\Clases\ReservationStatus;
use App\Models\Clases\ReservationStatisticStage;

// $factory->define(Clase::class, function (Faker $faker) {
//     return [
//       'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
//       'profesor_id' => User::all()->random()->id,
//       'quota' => $faker->numberBetween($min = 22, $max = 24),
//     ];
// });


$factory->define(ReservationStatus::class, function (Faker $faker) {
  return [
      'reservation_status' => $faker->word,
  ];
});

$factory->define(Reservation::class, function (Faker $faker) {
  return [
    'clase_id' => Clase::all()->random()->id,
    'reservation_status_id' => ReservationStatus::all()->random()->id,
    'user_id' => User::all()->random()->id,
  ];
});

// $factory->define(ReservationStatisticStage::class, function (Faker $faker) {
//   return [
//     'statistic_id' => Statistic::all()->random()->id,
//     'reservation_id' => Reservation::all()->random()->id,
//     'exercise_stage_id' => ExerciseStage::all()->random()->id,
//     'weight' => $faker->randomElement($array = array ('5', '10', '15', '20', '25')),
//     'time' => $faker->numberBetween($min = 1, $max = 45),
//     'round' => $faker->numberBetween($min = 1, $max = 6),
//     'repetitions' => $faker->numberBetween($min = 1, $max = 4),
//   ];
// });
