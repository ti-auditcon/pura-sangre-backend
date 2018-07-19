<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Student::class, function (Faker $faker) {
  return [
    'rut' => $faker->unique()->numberBetween($min = 70000000, $max = 90000000),
    'first_name' => $faker->firstName,
    'last_name' => $faker->lastName.' '.$faker->lastName,
    'email' => $faker->freeEmail,
    'plan' => $faker->randomElement($array = array ('plan a','plan b','plan c')),
    'status' => $faker->randomElement($array = array ('ACTIVO','ACTIVO','ACTIVO','ACTIVO','INACTIVO','DEUDA')),
    'avatar' => 'u'.$faker->numberBetween($min = 1, $max = 11).'.jpg',
  ];
});
