<?php

use Illuminate\Support\Facades\Route;

/** *****************************************************
*  *******       EXTERNAL ROUTES        ************** 
* ****************************************************** */
Route::post('/external/contact-form', 'Web\ContactEmailController@sendEmail');

Route::get('/planes/contractables', 'Web\PlanController@index');

Route::post('users/{user}/image', 'Users\UserController@image');

/** *****************************************************
*  *******         PWA ROUTES           **************
* ****************************************************** */
Route::prefix('pwa')->group(function () {
    Route::post('/auth/login', 'API\Auth\AuthController@login');

    Route::middleware('auth:api')->group(function () {
        Route::post('/auth/logout', 'API\Auth\AuthController@logout');

        // Usuario autenticado
        Route::get('/me', 'API\Users\UserController@me');

        // Clases
        Route::get('/clases', 'API\Clases\ClaseController@index');
        Route::get('/clases/{clase}', 'API\Clases\ClaseController@show');

        // Reservas del alumno
        Route::get('/reservations', 'API\Clases\ReservationController@index');
        Route::post('/reservations', 'API\Clases\ReservationController@store');
        Route::delete('/reservations/{reservation}', 'API\Clases\ReservationController@destroy');
        Route::put('/reservations/{reservation}/confirm', 'API\Clases\ReservationController@confirm');
    });
});
