<?php


use Faker\Generator as Faker;
use App\Models\Clases\Reservation;
use App\Models\Clases\Clase;
use App\Models\Users\user;


$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'clase_id' => Clase::all()->random()->id,
        'user_id' => User::all()->random()->id,
        'reservation_status_id' => 1,
    ];
});
