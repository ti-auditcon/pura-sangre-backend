<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Users\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Users\PasswordReset;

$factory->define(PasswordReset::class, function (Faker $faker) {
    return [
        'email'   => factory(User::class)->create()->email,
        'token'   => Str::random(150),
        'expired' => false
    ];
});
