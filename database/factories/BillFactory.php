<?php

use App\Models\Bills\Bill;
use Faker\Generator as Faker;
use App\Models\Plans\PlanUser;
use App\Models\Bills\PaymentType;

$factory->define(Bill::class, function (Faker $faker) {
    $plan_user = factory(PlanUser::class)->create();
    
    return [
        'payment_type_id' => $faker->randomElement([
            PaymentType::EFECTIVO, PaymentType::TRANSFERENCIA, PaymentType::CHEQUE,
            PaymentType::DEBITO, PaymentType::CREDITO, PaymentType::FLOW 
        ]),
        'plan_user_id' => $plan_user->id,
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'detail' => $faker->paragraph(1),
        'amount' => 29990,
        'start_date' => $plan_user->start_date,
        'finish_date' => $plan_user->finish_date,
    ];
});
