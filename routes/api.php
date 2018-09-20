<?php

use Illuminate\Http\Request;

/**
 * [Route Users ApiControllers]
 * @var [type]
 */
Route::apiResource('users', 'API\Users\UserController')->except('destroy');

/**
 * Register  POST | oauth/token with this middleware insted of throttle
 */
 Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');


 // Route::get('users/{user}', function (App\Models\Users\User $user) {
 //     return $user->email;
 // });
