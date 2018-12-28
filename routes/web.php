<?php

Auth::routes();

Route::get('/', 'HomeController@index');
Route::get('/success-reset-password', function () {
    return view('guest.success-reset-password');
});

Route::middleware(['auth'])->prefix('/')->group(function () {

    /**
     * Exercises Routes (exercises)
     */
    Route::resource('wods', 'Wods\WodController');
    Route::resource('exercises', 'Exercises\ExerciseController');

    /**
     * Clases routes (clases, clases-alumnos, bloques)
     */
    Route::resource('blocks', 'Clases\BlockController')->middleware('role:1');

    Route::resource('clases', 'Clases\ClaseController')
         ->except('create', 'edit', 'store', 'update');
        Route::post('clases/{clase}/confirm', 'Clases\ClaseController@confirm')->name('clase.confirm');
    // Route::resource('clases.users', 'Clases\ClaseUserController')
    //        ->only('store', 'update', 'destroy');

    Route::resource('reservation', 'Clases\ReservationController')
           ->only('store', 'update', 'destroy');

    Route::get('get-wods', 'Clases\ClaseController@wods');
    Route::get('get-clases', 'Clases\ClaseController@clases');
    Route::post('clases/type-select/', 'Clases\ClaseController@typeSelect')->name('clases.type');

    Route::get('/asistencia-modal/{id}', 'Clases\ClaseController@asistencia')->name('asistencia');

    /**
     * BILLS Routes
     */
    Route::resource('payments', 'Bills\BillController')->middleware('role:1');
    Route::get('/bills', 'Bills\BillController@bills')->name('bills');

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
    Route::get('alerts', 'Messages\AlertController@index')->middleware('role:1');
    Route::get('messages', 'Messages\MessageController@index')->middleware('role:1');
    Route::post('messages/send', 'Messages\MessageController@send')->middleware('role:1');
    Route::get('notifications', 'Messages\NotificationController@index')->middleware('role:1');
});
