<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', 'HomeController@index');

Route::middleware(['auth'])->prefix('/')->group(function () {

    /**
     * Exercises Routes (exercises)
     */
    Route::resource('exercises', 'Exercises\ExerciseController');
    //Route::resource('stages', 'Exercises\StageController');
    Route::resource('wods', 'Wods\WodController');

    /**
     * Clases routes (clases, clases-alumnos, bloques)
     */
    Route::resource('blocks', 'Clases\BlockController')->middleware('role:1');

    //Tal vez mas adelante se necesite el store de clases
    Route::resource('clases', 'Clases\ClaseController')
           ->except('create', 'edit', 'store', 'update');
    // Route::resource('clases.users', 'Clases\ClaseUserController')
    //        ->only('store', 'update', 'destroy');

    Route::resource('reservation', 'Clases\ReservationController')
           ->only('store', 'update', 'destroy');
    Route::post('clases/type-select/', 'Clases\ClaseController@typeSelect')->name('clases.type');
    Route::get('get-clases', 'Clases\ClaseController@clases');
    Route::get('get-wods', 'Clases\ClaseController@wods');

    /**
     * BILLS Routes
     */
    Route::resource('payments', 'Bills\BillController')->middleware('role:1');

    /**
     * Plans Routes
     */
    Route::resource('plans', 'Plans\PlanController')->middleware('role:1');

    /**
     * Reports routes
     */
    Route::resource('reports', 'Reports\ReportController')->middleware('role:1')->only('index');

    /**
    * Users Routes (alumnos, profes, admins)
    */
    Route::resource('users', 'Users\UserController');
        Route::get('update-avatar', 'Users\UserController@updateAvatar')->name('user.update.avatar');
    Route::resource('users.plans', 'Plans\PlanUserController');
        Route::post('users/{user}/plans/{plan}/annul', 'Plans\PlanUserController@annul')->name('users.plans.annul');
    Route::resource('users.plans.payments', 'Plans\PlanUserPaymentController');


    /**
     * Messages Routes
     */
    Route::get('messages', 'Messages\MessageController@index')->middleware('role:1');
    Route::post('messages/send', 'Messages\MessageController@send')->middleware('role:1');

    Route::get('alerts', 'Messages\AlertController@index')->middleware('role:1');

    Route::get('notifications', 'Messages\NotificationController@index')->middleware('role:1');
});
