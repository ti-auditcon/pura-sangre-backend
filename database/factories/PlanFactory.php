<?php

use App\Models\Bills\Bill;
use App\Models\Bills\Installment;
use App\Models\Bills\PaymentStatus;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanPeriod;
use App\Models\Plans\PlanUser;
use Carbon\Carbon;
use Faker\Generator as Faker;


// $factory->define(Plan::class, function (Faker $faker) {
//     return [
//       'plan' => $faker->word,
//       'class_numbers' => $faker->numberBetween($min = 12, $max = 24),
//     ];
// });


$factory->define(PlanUser::class, function (Faker $faker) {
//
  $plan = Plan::inRandomOrder()->first();


  $starts_at = Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now')->getTimeStamp());
  $ends_at= Carbon::createFromFormat("Y-n-j G:i:s", $starts_at)->addMonths($plan->plan_period->period_number ?? 1 );

  if($starts_at >= today()){
    $plan_status_id = '3';
  } else {
    if($ends_at >= today())
    {
      $plan_status_id = '1';
    } else {
      $plan_status_id = '4';
    }


  }
  $plan_period = PlanPeriod::where('id', $plan->plan_period_id)->first();
  if ($plan_period) {
    $period_number = $plan_period->period_number;
  }else{
    $period_number = 0;
  }

    return [
      'start_date' => $starts_at,
      'finish_date' => $ends_at,
      'counter' => $plan->class_numbers*$period_number,
      'plan_status_id' => $plan_status_id,
      'user_id' => 1,
      'plan_id' => $plan->id,
    ];
});


// $factory->define(Plan::class, function (Faker $faker) {
//     return [
//       'plan' => $faker->word,
//       'class_numbers' => $faker->numberBetween($min = 12, $max = 24),
//     ];
// });
//
// $factory->define(PlanUser::class, function (Faker $faker) {
//
// $starts_at = Carbon::createFromTimestamp($faker->dateTimeBetween($startDate = '-1 months', $endDate = 'now')->getTimeStamp());
// $ends_at= Carbon::createFromFormat("Y-n-j G:i:s", $starts_at)->addMonths($faker->numberBetween( 1, 2, 3));
//
//     return [
//       'start_date' => $starts_at,
//       'finish_date' => $ends_at,
//       'counter' => 5,
//       'plan_status_id' => $faker->randomElement($array = array ('1', '2', '3', '4', '5')),
//       // 'plan_status_id' => 1,
//       'plan_id' => Plan::all()->random()->id,
//     ];
// });
//

// $factory->define(Installment::class, function (Faker $faker) {
//     return [
//         'bill_id' => Bill::all()->random()->id,
//         'payment_status_id' => PaymentStatus::all()->random()->id,
//         'commitment_date' => $faker->date($format = 'Y-n-j G:i:s', $max = 'now'),
//         'payment_date' => $faker->date($format = 'Y-n-j G:i:s', $max = 'now'),
//         'expiration_date' => $faker->date($format = 'Y-n-j G:i:s', $max = 'now'),
//         'amount' => $faker->randomNumber($nbDigits = 7, $strict = false),
//     ];
// });
