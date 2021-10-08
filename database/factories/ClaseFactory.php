<?php

use App\Models\Users\User;
use App\Models\Clases\Block;
use App\Models\Clases\Clase;
use Faker\Generator as Faker;
use App\Models\Clases\ClaseType;

$factory->define(Clase::class, function (Faker $faker) {
    return [
        'date'          => today(),
        'start_at'      => $faker->time(),
        'finish_at'     => $faker->time(),
        'block_id'      => factory(Block::class)->create()->id,
        'room'          => 1,
        'profesor_id'   => factory(User::class)->create()->id,
        'wod_id'        => 1,
        'quota'         => 20,
        'clase_type_id' => factory(ClaseType::class)->create()->id,
    ];
});

$factory->define(ClaseType::class, function (Faker $faker) {
    return [
        'clase_type'  => $faker->randomElement(['CrossFit', 'HIIT', 'Yoga', 'Levantamiento de Pesas', 'Halterofilia', 'Calistenia']),
        'clase_color' => '#0045b3',
        'icon'        => 'crossfit',
        'icon_white'  => 'crossfit',
        'active'      => $faker->boolean(80) // percentage of this class is going to be active
    ];
});

