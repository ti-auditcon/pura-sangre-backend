<?php

use App\Models\Users\User;
use Faker\Generator as Faker;
use App\Models\Users\Emergency;
use App\Models\Users\Millestone;
use App\Models\Users\StatusUser;

$factory->define(Emergency::class, function (Faker $faker) {
    return [
        'contact_name' => $faker->name,
        'contact_phone' => $faker->numberBetween($min = 50000001, $max = 99999999),
    ];
});

$factory->define(StatusUser::class, function (Faker $faker) {
    return [
        'status_user' => $faker->word,
    ];
});

$factory->define(Millestone::class, function (Faker $faker) {
    return [
        'millestone' => $faker->word,
    ];
});

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName($gender = 'male'|'female'),
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'birthdate' => $faker->date($format = 'Y-m-d', $max = 'now'), // '1979-06-09',
        'gender' => $faker->randomElement($array = array ('male','female')),
        'password' => bcrypt('123123'), // secret
        'emergency_id' => Emergency::all()->random()->id,
        'status_user_id' => StatusUser::all()->random()->id,
        'remember_token' => str_random(10),
    ];
});
//
// $factory->define(MillestoneUser::class, function (Faker $faker) {
//     return [
//         'payment_type_id' => PaymentType::all()->random()->id,
//         'user_id' => User::all()->random()->id,
//         'date' => $faker->date($format = 'd-m-Y', $max = 'now'),
//         'detail' => $faker->paragraph(1),
//         'amount' => $faker->randomNumber($nbDigits = 7, $strict = false),
//         'subtotal' => $faker->randomNumber($nbDigits = 7, $strict = false),
//         'total' => $faker->randomNumber($nbDigits = 7, $strict = false),
//     ];
// });



/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
