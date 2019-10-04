<?php

use App\Models\Users\Emergency;
use App\Models\Users\Millestone;
use App\Models\Users\Role;
use App\Models\Users\StatusUser;
use App\Models\Users\User;
use Faker\Generator as Faker;
use Freshwork\ChileanBundle\Rut;


$factory->define(StatusUser::class, function (Faker $faker) {
    return [
        'status_user' => $faker->word,
    ];
});

$factory->define(Role::class, function (Faker $faker) {
    return [
        'role' => $faker->word,
    ];
});

$factory->define(User::class, function (Faker $faker) {
    return [
        'rut' => Rut::parse($faker->numberBetween($min = 1000000, $max = 100000000))->normalize(),
        'first_name' => $faker->firstName($gender = 'male'|'female'),
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'birthdate' => $faker->date($format = 'Y-m-d', $max = 'now'), // '1979-06-09',
        'gender' => $faker->randomElement($array = array ('male','female')),
        'password' => bcrypt('123123'), // secret
        'avatar' => url('/').'/storage/users/u ('.$faker->numberBetween($min = 1, $max = 54).').jpg',
        'phone' => $faker->numberBetween($min = 40000000, $max = 99876599),
        'address' => $faker->streetAddress,
        'status_user_id' => 3,
        'remember_token' => str_random(10),
    ];
});

//numeros de emergencia
$factory->define(Emergency::class, function (Faker $faker) {
    return [
        'contact_name' => $faker->name,
        'contact_phone' => $faker->numberBetween($min = 50000001, $max = 99999999),
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