<?php

use App\Models\Bills\Bill;
use App\Models\Users\User;
use Faker\Generator as Faker;
use App\Models\Bills\PaymentType;
use App\Models\Plans\PlanUser;



$factory->define(Bill::class, function (Faker $faker) {

    return [
        'payment_type_id' => PaymentType::inRandomOrder()->first()->id,
        'plan_user_id' => '1',
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'detail' => $faker->paragraph(1),
        'amount' => 0,
        'start_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'finish_date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    ];
});
