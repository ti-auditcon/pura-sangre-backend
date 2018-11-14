<?php

use App\Models\Bills\Bill;
use App\Models\Users\User;
use Faker\Generator as Faker;
use App\Models\Bills\PaymentType;
use App\Models\Plans\PlanUser;

// $factory->define(PaymentType::class, function (Faker $faker) {
//     return [
//         'payment_type' => $faker->word,
//     ];
// });

// $factory->define(PaymentStatus::class, function (Faker $faker) {
//     return [
//         'payment_status' => $faker->word,
//     ];
// });

$factory->define(Bill::class, function (Faker $faker) {

   $planUser = PlanUser::inRandomOrder()->first();


    return [
        'payment_type_id' => PaymentType::inRandomOrder()->first()->id,
        'plan_user_id' => $planUser->id,
        'date' => $planUser->start_date,
        'detail' => $faker->paragraph(1),
        'amount' => $planUser->plan->amount,
        'start_date' => $planUser->start_date,
        'finish_date' => $planUser->finish_date,
    ];
});
