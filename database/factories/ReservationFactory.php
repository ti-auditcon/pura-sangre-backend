<?php

use App\Models\Users\User;
use App\Models\Clases\Clase;
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
        'clase_id' => Clase::all()->random()->id,
        'reservation_status_id' => mt_rand(1, 2),
        'user_id' => User::all()->random()->id,
        'by_god' => $faker->boolean(10),
        'details' => $faker->text(400),
    ];
});