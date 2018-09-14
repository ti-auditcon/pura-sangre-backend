<?php

use Illuminate\Http\Request;

  /**
   * [Route Users ApiControllers]
   * @var [type]
   */
  //Route::apiResource('users', 'API\Users\UserController');

  /**
   * Register  POST | oauth/token with this middleware insted of throttle
   */
 //Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

 Route::middleware('auth:api')->get('/user', function (Request $request) {
     return $request->user();
 });

Route::apiResource('users', 'Api\Users\UserController');



 // Route::get('users/{user}', function (App\Models\Users\User $user) {
 //     return $user->email;
 // });
