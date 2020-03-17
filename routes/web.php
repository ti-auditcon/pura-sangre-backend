<?php

Auth::routes();

Route::get('first-class-mail', function () {
    // dd('asdasds');
    $invoice = collect();
    $invoice->subject = '';
    $invoice->user = 'Raul';

    return new App\Mail\SendFirstClassEmail($invoice);
});

Route::get('gone-away-mail', function () {
    // dd('asdasds');
    $user = \App\Models\Users\User::find(15);

    return new App\Mail\GoneAwayUserEmail($user);
});
// Route::get('/fix-clases', 'HomeController@fixClases');

Route::get('/', 'HomeController@index');
Route::get('/withoutrenewal', 'HomeController@withoutrenewal');
Route::get('/genders', 'HomeController@genders');
Route::get('/incomes-summary', 'HomeController@incomessummary');

Route::get('/success-reset-password', function () {
    return view('guest.success-reset-password');
});

Route::post('expired-plans', 'HomeController@ExpiredPlan')->name('expiredplans');

Route::middleware(['auth'])->prefix('/')->group(function () {
    // Calibrate plan_income_summaries
    Route::post('plan-incomes-summaries/calibrate', 'Reports\ReportController@incomesCalibrate')
        ->name('incomes.calibrate');

    Route::get('update-reservations-plans', 'Users\UserController@putIdPlan')->middleware('role:1');

    /**
     * Clases routes (clases, clases-alumnos, bloques)
     */
    Route::resource('blocks', 'Clases\BlockController')->middleware('role:1');

    Route::resource('clases', 'Clases\ClaseController')->except('create', 'edit', 'update');

        Route::post('clases/{clase}/confirm', 'Clases\ClaseController@confirm')->name('clase.confirm');

    Route::resource('reservation', 'Clases\ReservationController')->only('store', 'update', 'destroy');

        Route::post('/reservation/{reservation}/confirm', 'Clases\ReservationController@confirm');

    Route::get('get-wods', 'Clases\ClaseController@wods');

    Route::get('get-clases', 'Clases\ClaseController@clases');

    Route::post('clases/type-select/', 'Clases\ClaseController@typeSelect')->name('clases.type');

    Route::get('/asistencia-modal/{id}', 'Clases\ClaseController@asistencia')->name('asistencia');

    /**
     * Clases types routes
     */
    Route::resource('clases-types', 'Clases\ClaseTypeController')->except('create', 'edit');

    // Route::post('clases-types/update', 'Clases\ClaseTypeController@updateClaseTypeStage')
    //      ->name('clases-types.update-all');

    /**
     * CALENDAR CLASES ROUTES
     */
    Route::post('calendar/clases/delete', 'Clases\CalendarClasesController@destroy')
         ->name('admin.calendar.clasesday.destroy');

    /**
     *  POSTPONE PLANS ROUTE
     */
    Route::get('postpones', 'Plans\PostponeController@index')->name('postpones.index');

    Route::post('postpones/all', 'Plans\PostponeController@postponeAll')->name('postpones.all');

    Route::resource('plan-user.postpones', 'Plans\PlanUserPostponesController')
         ->only('store', 'destroy')
         ->middleware('role:1');

    /**
     * BILLS Routes
     */
    Route::resource('payments', 'Bills\BillController')->middleware('role:1')->only('index', 'update');

    Route::post('payments/pagos', 'Bills\BillController@getPagos')->name('datapagos');

    Route::get('/bills', 'Bills\BillController@bills')->name('bills');

    Route::get('bills/export', 'Bills\BillController@export')->name('bills.export');

    /**
     * Plans Routes
     */
    Route::resource('plans', 'Plans\PlanController')->middleware('role:1');

    /**
     * Exercises Routes (exercises)
     */
    Route::resource('exercises', 'Exercises\ExerciseController');

    Route::get('stage-types/{stage_type}', 'Wods\StageTypeController@show');
    // Route::resource('stages-types', 'Exercises\ExerciseController');

    /**
     * Messages Routes
     */
    Route::resource('alerts', 'Messages\AlertController')->only(['index', 'store'])->middleware('role:1');

    Route::get('/alert-list', 'Messages\AlertController@alerts');

    Route::delete('/alert-list/{alert}', 'Messages\AlertController@destroy')->name('alerts.destroy')->middleware('role:1');

    Route::get('messages', 'Messages\MessageController@index')->middleware('role:1');

    Route::get('messages/users_Json', 'Messages\MessageController@usersJson')->middleware('role:1');

    Route::post('messages/send', 'Messages\MessageController@send')->middleware('role:1');

    Route::get('notifications', 'Messages\NotificationController@index')->middleware('role:1')->name('messages.notifications');

    Route::post('notifications', 'Messages\NotificationController@store')->middleware('role:1');

    Route::delete('/notifications/{notification}', 'Messages\NotificationController@destroy')
         ->middleware('role:1')
         ->name('notifications.destroy');

    /**
     *  Settings Routes
     */
    Route::get('json-density-parameters', 'Settings\DensityParameterController@clasesDensities');

    Route::resource('density-parameters', 'Settings\DensityParameterController')
         ->only('index', 'store', 'update', 'destroy');

    /**
     * Reports routes
     */
    Route::resource('reports', 'Reports\ReportController')->middleware('role:1')->only('index');

    Route::get('reports/firstchart', 'Reports\ReportController@firstchart');

    Route::get('reports/secondchart', 'Reports\ReportController@secondchart');

    Route::get('reports/thirdchart', 'Reports\ReportController@thirdchart');

    Route::get('reports/totalplans', 'Reports\ReportController@quantityTypePlansByMonth')->name('plansMonthType');

    Route::get('reports/totalplanssub', 'Reports\ReportController@totalplanssub')->name('totalplanssub');

    Route::get('reports/inactive_users', 'Reports\InactiveUserController@index');

    Route::post('reports/inactive_users_json', 'Reports\InactiveUserController@inactiveUsers')->name('inactiveusers');

    Route::get('reports/inactive_users/export', 'Reports\InactiveUserController@export')->name('inactive_users.export');

    Route::get('reports/heatmap', 'Reports\ReportController@heatMap');

    Route::get('reports/data-plans/', 'Reports\DataPlansController@index');

    Route::post('reports/data-plans/compare', 'Reports\DataPlansController@compare')->name('data-plans-compare');

    /**
     * Users Routes (ALUMNOS, PROFES, ADMINS, ALERTAS)
     */
    Route::get('users/{user}/plans/{plan}/info', 'Users\UserController@userinfo')->name('users.plans.info');

    Route::get('users/geolocations', 'Users\UserController@geolocations')->name('users.geolocations');

    Route::post('users/{user}/plans/{plan}/annul', 'Plans\PlanUserController@annul')->name('users.plans.annul');

    // Route::resource('users.plans.payments', 'Plans\PlanUserPaymentController');

    Route::resource('users', 'Users\UserController');

    Route::get('users-json', 'Users\UserController@usersJson')->name('users-json');

    Route::get('export', 'Users\UserController@export')->name('users.export');

    Route::get('update-avatar', 'Users\UserController@updateAvatar')->name('user.update.avatar');

    Route::resource('users.plans', 'Plans\PlanUserController');

    Route::post('users/{user}/plans/{plan}/annul', 'Plans\PlanUserController@annul')->name('users.plans.annul');

    // Route::resource('users.plans.payments', 'Plans\PlanUserPaymentController');

    Route::get('users/{user}/plans/{plan}/info', 'Users\UserController@userinfo')->name('users.plans.info');

    /**
     * ROLEUSER ROUTES
     */
    Route::resource('role-user', 'Users\RoleUserController')->only('edit', 'store', 'destroy');

    /**
     *  WODS Routes
     */
    Route::resource('wods', 'Wods\WodController')->except('index', 'show')->middleware('role:1');
});
