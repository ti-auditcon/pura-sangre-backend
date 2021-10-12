<?php

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Users\RoleUser;
use App\Models\Users\StatusUser;
use Freshwork\ChileanBundle\Rut;


$factory->define(RoleUser::class, function (Faker $faker) {
    return [
        'role_id' => factory(Role::class)->create()->id,
        'user_id' => factory(User::class)->create()->id,
    ];
});

$factory->define(Role::class, function (Faker $faker) {
    return [
        'role' => $faker->word,
    ];
});


$factory->define(User::class, function (Faker $faker) {
    return [
        'rut'            => Rut::parse($faker->numberBetween($min = 1000000, $max = 100000000))->normalize(),
        'first_name'     => $faker->firstName($gender = 'male'|'female'),
        'last_name'      => $faker->lastName,
        'email'          => $faker->unique()->safeEmail,
        'password'       => bcrypt('123123'), // 123123
        'avatar'         => url('/').'/storage/users/u ('.$faker->numberBetween($min = 1, $max = 54).').jpg',
        'phone'          => $faker->numberBetween($min = 40000000, $max = 99876599),
        'birthdate'      => $faker->date($format = 'Y-m-d', $max = 'now'), // '1979-06-09',
        'since'          => today(),
        'gender'         => $faker->randomElement($array = array ('hombre', 'mujer')),
        'address'        => $faker->streetAddress,
        'status_user_id' => $faker->randomElement([StatusUser::ACTIVE, StatusUser::INACTIVE, StatusUser::TEST]),
        'remember_token' => Str::random(10),
        'fcm_token'      => Str::random(130),
        'tutorial'       => true,
    ];
});
