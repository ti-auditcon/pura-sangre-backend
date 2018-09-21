<?php

use App\Models\Bills\Bill;
use App\Models\Users\User;
use Faker\Generator as Faker;
use App\Models\Bills\PaymentType;

$factory->define(PaymentType::class, function (Faker $faker) {
    return [
        'payment_type' => $faker->word,
    ];
});

// $factory->define(PaymentStatus::class, function (Faker $faker) {
//     return [
//         'payment_status' => $faker->word,
//     ];
// });

$factory->define(Bill::class, function (Faker $faker) {
    return [
        'payment_type_id' => PaymentType::all()->random()->id,
        'user_id' => User::all()->random()->id,
        'date' => $faker->date($format = 'm-d-Y', $max = 'now'),
        'detail' => $faker->paragraph(1),
        'amount' => $faker->randomNumber($nbDigits = 7, $strict = false),
        'subtotal' => $faker->randomNumber($nbDigits = 7, $strict = false),
        'total' => $faker->randomNumber($nbDigits = 7, $strict = false),
    ];
});
