<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Clases\Block::class, function (Faker $faker) {
    $start = $faker->time($format = 'H:i', $max = 'now');
    return [
        // 'start' => $faker->word,
        // 'end' => $faker->word,
        // 'dow' => $faker->numberBetween($min = 1, $max = 5),
    ];
});
