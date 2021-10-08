<?php


use App\Models\Users\User;
use App\Models\Clases\Clase;
use Faker\Generator as Faker;
use App\Models\Plans\PlanUser;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;


$factory->define(ReservationStatus::class, function (Faker $faker) {
  return [
      'reservation_status' => $faker->word,
  ];
});

$factory->define(Reservation::class, function (Faker $faker) {
    return [
        'clase_id'              => factory(Clase::class)->create()->id,
        'reservation_status_id' => $faker->randomElement([ReservationStatus::PENDING, ReservationStatus::CONFIRMED]),
        'user_id'               => factory(User::class)->create()->id,
        'plan_user_id'          => factory(PlanUser::class)->create()->id,
    ];
});
	// $clase = Clase::find(Clase::all()->random()->id);
	// // $date_class = Carbon\Carbon::parse($clase->date)->format('y-m-d');
	// // if ($date_class > today()) {
	// // 	$status = $faker->randomElement($array = array (1, 2));
	// // }else {
	// // 	$status = 1;
	// // }
