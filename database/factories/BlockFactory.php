<?php

use Carbon\Carbon;
use App\Models\Users\User;
use Faker\Generator as Faker;
use App\Models\Clases\ClaseType;

$factory->define(App\Models\Clases\Block::class, function (Faker $faker) {
    return [
        'start'         => now()->startOfHour()->format('H:i:s'),
        'end'           => now()->startOfHour()->addHour()->format('H:i:s'),
        'title'         => $faker->word,
        'date'          => date('Y-m-d'),
        'coach_id'   => factory(User::class)->create()->id,
        'quota'         => $faker->randomElement([19, 20, 22, 21]),
        'clase_type_id' => factory(ClaseType::class)->create()->id,
        'dow'           => $faker->numberBetween($min = 1, $max = 5),
    ];
});
