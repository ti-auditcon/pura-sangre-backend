<?php

use App\Models\Plans\PlanUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use App\Models\Invoicing\TaxDocument;
use Illuminate\Support\Facades\Route;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Auth Route lists
 */
Auth::routes();

include __DIR__ . '/super.php';

/**
 * General routes
 */
Route::get('/', 'HomeController@index');
Route::get('/withoutrenewal', 'HomeController@withoutrenewal');
Route::get('/genders', 'HomeController@genders');
Route::get('/incomes-summary', 'HomeController@incomessummary');

Route::get('/success-reset-password', function () {
    return view('guest.success-reset-password');
});

Route::post('expired-plans', 'HomeController@ExpiredPlan')->name('expiredplans');

Route::middleware(['auth'])->prefix('/')->group(function () {
    Route::get('update-plan-user-date', function () {
        $planUsers = \App\Models\Plans\PlanUser::where('finish_date', '>=' , now()->format('Y-m-d'))
            ->get();

            \App\Models\Plans\PlanUser::withoutEvents(function () use ($planUsers) {
                foreach ($planUsers as $planUser) {
                    $planUser->finish_date = Carbon\Carbon::parse($planUser->finish_date)->format('Y-m-d') 
                        . ' 23:59:59';

                    $planUser->save();
                }
            });
    });

    Route::get('update-clase-date', function () {
        $clases = \App\Models\Clases\Clase::where('date', '>=' , now()->format('Y-m-d'))
            ->get();

            \App\Models\Clases\Clase::withoutEvents(function () use ($clases) {
                foreach ($clases as $clase) {
                    $clase->date = Carbon\Carbon::parse($clase->date)->format('Y-m-d') 
                        . Carbon\Carbon::parse($clase->start_at)->format(' H:i:s');

                    $clase->save();
                }
            });
    });

    // update finisH_date in plan_user_flows
    Route::get('update-plan-user-flows', function () {
        $planUserFlows = \App\Models\Plans\PlanUserFlow::where('finish_date', '>=' , now()->format('Y-m-d'))
            ->get();

            \App\Models\Plans\PlanUserFlow::withoutEvents(function () use ($planUserFlows) {
                foreach ($planUserFlows as $planUserFlow) {
                    $planUserFlow->finish_date = Carbon\Carbon::parse($planUserFlow->finish_date)->format('Y-m-d') 
                        . ' 23:59:59';

                    $planUserFlow->save();
                }
            });
    });

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
    Route::get('/clases-types-all', 'Clases\ClaseTypeController@allClaseTypes')->name('tenant.admin.clases-types-all');
    Route::post('/clases-types/{clase_type}', 'Wods\StageTypeController@addStage');
    Route::get('/clases-types/{clase_type}/stages-types', 'Clases\ClaseTypeStageTypeController@index');
    Route::patch('/clases-types/{clases_type}/activation', 'Clases\ClaseTypeController@activation');
    Route::resource('clases-types', 'Clases\ClaseTypeController');
    Route::resource('stages-types', 'Wods\StageTypeController')->only('show', 'update', 'destroy');

    /**
     * CALENDAR CLASES ROUTES
     */
    Route::post('calendar/clases/delete', 'Clases\CalendarClasesController@destroy')
         ->name('admin.calendar.clasesday.destroy');

    /**
     * POSTPONE PLANS ROUTE
     */
    Route::get('postpones', 'Plans\PostponeController@index')->middleware('role:1')->name('postpones.index');
    
    Route::post('postpones/all', 'Plans\PostponeController@postponeAll')->name('postpones.all');
    Route::post('/plan-user/{plan_user}/postpones', 'Plans\PlanUserPostponesController@store')->name('plan-user.postpones.store');
    Route::resource('postpones', 'Plans\PlanUserPostponesController')->only('destroy')
            ->middleware('role:1');

    /**
     * BILLS Routes
     */
    Route::post('payments/pagos', 'Bills\BillController@getPagos')->name('datapagos');
    Route::resource('payments', 'Bills\BillController')->middleware('role:1')->only('index', 'store', 'update', 'destroy');
    Route::get('/bills', 'Bills\BillController@bills')->name('bills');
    Route::get('bills/export', 'Bills\BillController@export')->name('bills.export');

    Route::resource('payments', 'Bills\BillController')->middleware('role:1')->only('index', 'update');

    Route::get('invoices/recevied', 'Bills\InvoicingController@recevied');
    Route::get('invoices/issued', 'Bills\InvoicingController@issued');
    Route::get('invoices/received/json', 'Bills\InvoicingController@receivedJson');
    Route::get('invoices/issued/json', 'Bills\InvoicingController@issuedJson');
    Route::post('tax-documents/{token}/cancel', 'Bills\InvoicingController@cancel')->name('taxes.cancel');
    Route::post('dte/get-pdf', 'Bills\TaxDocumentController@show');
    Route::get('tax-documents/{token}/status', 'Bills\TaxDocumentController@status');
    Route::post('dte/get-issued-pdf', 'Bills\TaxDocumentController@getIssuedPDF');
    
    Route::post('dte/{plan_user_flow}/save-pdf', 'Bills\TaxDocumentController@savePDFThroughAPI');
    /*
     * Plans Routes
     */
    Route::resource('plans', 'Plans\PlanController')->middleware('role:1');

    /*
     * Exercises Routes (exercises)
     */
    Route::resource('exercises', 'Exercises\ExerciseController');
    Route::get('stage-types/{stage_type}', 'Wods\StageTypeController@show');

    /*
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

    /*
     * Settings Routes
     */
    Route::get('json-density-parameters', 'Settings\DensityParameterController@clasesDensities');
    Route::resource('density-parameters', 'Settings\DensityParameterController')
            ->only('index', 'store', 'update', 'destroy');
    Route::resource('settings', 'Settings\SettingsController')->only('index', 'update');
    

    /*
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

    /*
     * Users Routes (ALUMNOS, PROFES, ADMINS, ALERTAS)
     */
    Route::get('users/{user}/plans/{plan}/info', 'Users\UserController@userinfo')->name('users.plans.info');
    Route::get('users/geolocations', 'Users\UserController@geolocations')->name('users.geolocations');
    Route::post('users/{user}/plans/{plan}/annul', 'Plans\PlanUserController@annul')->name('users.plans.annul');
    Route::resource('users', 'Users\UserController');
    Route::get('users-json', 'Users\UserController@usersJson')->name('users-json');
    Route::get('export', 'Users\UserController@export')->name('users.export');
    Route::get('update-avatar', 'Users\UserController@updateAvatar')->name('user.update.avatar');
    Route::resource('users.plans', 'Plans\PlanUserController');
    Route::post('users/{user}/plans/{plan}/annul', 'Plans\PlanUserController@annul')->name('users.plans.annul');
    Route::get('users/{user}/plans/{plan}/info', 'Users\UserController@userinfo')->name('users.plans.info');

    Route::patch('users/{user}/reset-password', 'Users\UserController@resetPassword')->name('users.password-reset');

    /*
     * ROLEUSER ROUTES
     */
    Route::resource('role-user', 'Users\RoleUserController')->only('edit', 'store', 'destroy');

    /*
     * WODS Routes
     */
    Route::resource('wods', 'Wods\WodController')->except('index', 'show')->middleware('role:1');

    /*
     * Notifications TESTS
     */
    Route::get('notifications-send-push/{user_id}', 'Messages\NotificationController@sendOnePush')->middleware('role:1');
});

/** *****************************************************
 * *********       EXTERNAL ROUTES        ************
 * ************************************************** */
Route::post('new-user/request-instructions', 'Web\NewUserController@requestInstructions');
Route::get('/new-user/{plan}/create', 'Web\NewUserController@create');
Route::resource('/new-user', 'Web\NewUserController')->except('index', 'update', 'destroy', 'create', 'show');

 /** *****************************************************
  *  *******       EXTERNAL ROUTES        **************
  * ****************************************************** */
Route::post('/flow/return-from-payment', 'Web\NewUserController@finishFlowPayment');
Route::post('/flow/confirm-payment', 'Web\NewUserController@finishFlowPayment');

Route::get('get-pdf/{plan_user_flow}', 'Web\NewUserController@getPlanUserFlowTaxDocument');

Route::get('/flow/return', function () { return view('web.flow.return'); });
Route::get('/flow/error', function () { return view('web.flow.error'); });

Route::get('finish-registration', 'Web\NewUserController@finishing');

// Route::get('maila', function() {
//     return new App\Mail\SendNewUserEmail(App\Models\Users\User::find(1), 123);
//     $planuserFlow = App\Models\Plans\PlanUserFlow::find(2110);

//     return new App\Mail\NewPlanUserEmail($planuserFlow);
//     // return Mail::to('raulberrios8@gmail.com')->send(new App\Mail\NewPlanUserEmail($planuserFlow));
// });

Route::get('cancel-dte', function() {
    app(TaxDocument::class)->cancel(109444);
});