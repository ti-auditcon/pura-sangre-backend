<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Student::class, function (Faker $faker) {
  return [
    'rut' => $faker->unique()->numberBetween($min = 70000000, $max = 90000000),
    'first_name' => $faker->firstName,
    'last_name' => $faker->lastName,
  ];
});
