<?php

use Carbon\Carbon;
use App\Models\Users\User;
use Faker\Generator as Faker;
use App\Models\Clases\ClaseType;

$factory->define(App\Models\Clases\Block::class, function (Faker $faker) {
    // the minutes has to be multiple of five
    $start = now()->startOfHour();

    return [
        'start'         => $start,
        'end'           => Carbon::parse($start)->addHour()->format('H:i:s'),
        'title'         => $faker->word,
        'date'          => date('Y-m-d'),
        'profesor_id'   => factory(User::class)->create()->id,
        'quota'         => $faker->randomElement([19, 20, 22, 21]),
        'clase_type_id' => factory(ClaseType::class)->create()->id,
        'dow'           => $faker->numberBetween($min = 1, $max = 5),
    ];
});
