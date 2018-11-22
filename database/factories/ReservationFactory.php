<?php


use Faker\Generator as Faker;
use App\Models\Clases\Reservation;
use App\Models\Clases\Clase;
use App\Models\Users\user;
use App\Models\Clases\ReservationStatus;


$factory->define(ReservationStatus::class, function (Faker $faker) {
  return [
      'reservation_status' => $faker->word,
  ];
});

$factory->define(Reservation::class, function (Faker $faker) {
	$clase = Clase::find(Clase::all()->random()->id);
	$date_class = Carbon\Carbon::parse($clase->date)->format('y-m-d');
	if ($date_class > today()) {
		$status = $faker->randomElement($array = array (1, 2));
	}else {
		$status = 1;
	}
   return [
      'clase_id' => $clase->id,
      'user_id' => User::all()->random()->id,
      'reservation_status_id' => $status,
   ];
});
