<?php

use Illuminate\Http\Request;
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/**
 * [User_route rutas para el usuario]
 * @var [type]
 */
Route::resource('users', 'Users\UserController')->except(['create', 'edit']);

/**
 * [Route description]
 * @var [type]
 */
Route::resource('bills', 'Bills\BillController')->middleware('can:view,bill');

/**
 * [Route description]
 * @var [type]
 */
Route::resource('exercises', 'Exercises\ExerciseController');

/**
 * [Route description]
 * @var [type]
 */
Route::resource('plans', 'Plans\PlanController');

//Se cambia de middleware de throttle a api (this)
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
