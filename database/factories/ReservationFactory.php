<?php

use Faker\Generator as Faker;
use App\Models\Clases\Reservation;

// $factory->define(ReservationStatus::class, function (Faker $faker) {
//     return [
//         'reservation_status' => $faker->word,
//     ];
// });

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'plan_user_id' => null,
        'clase_id' => App\Models\Clases\Clase::all()->random()->id,
        'reservation_status_id' => mt_rand(1, 2),
        'user_id' => App\Models\Users\User::all()->random()->id,
        'by_god' => mt_rand(0, 1),
        'details' => $faker->text(400),
    ];
});