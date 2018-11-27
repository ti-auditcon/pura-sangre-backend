<?php

use Illuminate\Http\Request;

/**
 * [Route Users ApiControllers]
 * @var [type]
 */
//Route::apiResource('users', 'API\Users\UserController')->except('destroy');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'API\Auth\AuthController@login');

Route::post('user/image', 'Users\UserController@image');

Route::get('clases', 'API\Clases\ClaseController@index');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('profile', 'API\Users\UserController@profile');
    Route::post('logout', 'API\Auth\AuthController@logout');

});
