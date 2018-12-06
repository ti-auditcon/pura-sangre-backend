<?php


use App\Models\Clases\Clase;
use App\Models\Clases\Reservation;
use App\Models\Clases\ReservationStatus;
use App\Models\Plans\PlanUser;
use App\Models\Users\user;
use Faker\Generator as Faker;


$factory->define(ReservationStatus::class, function (Faker $faker) {
  return [
      'reservation_status' => $faker->word,
  ];
});

$factory->define(Reservation::class, function (Faker $faker) {
   return [
      'clase_id' => Clase::all()->random()->id,
      'plan_user_id' => PlanUser::all()->random()->id,
      'reservation_status_id' => $faker->randomElement($array = array (1, 2)),
   ];
});
	// $clase = Clase::find(Clase::all()->random()->id);
	// // $date_class = Carbon\Carbon::parse($clase->date)->format('y-m-d');
	// // if ($date_class > today()) {
	// // 	$status = $faker->randomElement($array = array (1, 2));
	// // }else {
	// // 	$status = 1;
	// // }
