<?php

use Carbon\Carbon;
use App\Mail\NewPlanUserEmail;
use App\Models\Plans\PlanStatus;
use App\Models\Plans\PlanUserFlow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/**
 *  Auth Route lists
 */
Auth::routes();

/**
 *  General routes
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
    Route::get('freeze-plans/fix', function() {
        // $postpone = App\Models\Plans\PostponePlan::join('plan_user', 'plan_user.id', '=', 'freeze_plans.plan_user_id')
        //                                 ->join('plans', 'plan_user.plan_id', '=', 'plans.id')
        //                                 ->find(1526, [
        //                                     'freeze_plans.id', 'freeze_plans.plan_user_id',
        //                                     'freeze_plans.start_date', 'freeze_plans.finish_date',
        //                                     'freeze_plans.revoked', 'freeze_plans.days',
        //                                     'plan_user.id as planUserId', 'plan_user.start_date as planUserStartDate',
        //                                     'plan_user.finish_date as planUserFinishDate',
        //                                     'plans.id as planId', 'plans.plan'
        //                                 ]);

        // $days_freezed = Carbon::parse($postpone->start_date)->diffInDays(Carbon::parse($postpone->finish_date));
        // // remove days freezed to actual planUserFinishDate
        // $realFinishDatePlan = Carbon::parse($postpone->planUserFinishDate)->subDays($days_freezed);
        // // calculate resting days with the difference start freeze and PlanUser finishDate
        // $restingDays = Carbon::parse($postpone->start_date)->diffInDays($realFinishDatePlan);

        $freezed_plans = App\Models\Plans\PostponePlan::where('revoked', false)
                                                        ->join('plan_user', 'plan_user.id', '=', 'freeze_plans.plan_user_id')
                                                        ->join('plans', 'plans.id', '=', 'plan_user.plan_id')
                                                        ->join('plan_periods', 'plan_periods.id', '=', 'plans.plan_period_id')
                                                        ->leftJoin('bills', 'plan_user.id', '=', 'bills.plan_user_id')
                                                        ->whereNull('days')
                                                        ->get([
                                                            'freeze_plans.id', 'freeze_plans.plan_user_id',
                                                            'freeze_plans.start_date as startFreeze', 'freeze_plans.finish_date as finishFreeze',
                                                            'freeze_plans.revoked', 'freeze_plans.days',
                                                            'plan_user.id as planUserId', 'plan_user.start_date as planUserStartDate',
                                                            'plan_user.finish_date as planUserFinishDate', 'plan_user.plan_status_id',
                                                            'bills.id as billId', 'bills.start_date as billStartDate', 'bills.finish_date as billFinishDate',
                                                            'plans.id as planId', 'plan_periods.period_number'
                                                        ]);

        // calculo los dias en que el plan estuvo congelado
        foreach ($freezed_plans as $freeze_plan) {
            $planStartDate = Carbon::parse($freeze_plan->planUserStartDate);
            $startFreezing = Carbon::parse($freeze_plan->startFreeze);
            $finishFreezing = Carbon::parse($freeze_plan->finishFreeze);
            $billStart = Carbon::parse($freeze_plan->billStartDate);
            $billFinish = Carbon::parse($freeze_plan->billFinishDate);

            if ($startFreezing > $planStartDate && $finishFreezing < today()) {
                $days_of_freezing = $startFreezing->diffInDays($finishFreezing);
                // remove days freezed to actual planUserFinishDate
                $removedFinishDatePlan = Carbon::parse($freeze_plan->planUserFinishDate)->subDays($days_of_freezing + 1);
                // calculate resting days with the difference start freeze and PlanUser finishDate
                $restingDays = $startFreezing->diffInDays($removedFinishDatePlan);


                    // dias que fueron consumidos del plan
                    // dd(
                    //     $freeze_plan,
                    //     $planStartDate,
                    //     Carbon::parse($freeze_plan->startFreeze),
                    //     $planStartDate->diffInDays(Carbon::parse($freeze_plan->startFreeze))
                    // );
                    // $consumed_days = $planStartDate->diffInDays(Carbon::parse($freeze_plan->startFreeze));

                    // dias que le quedan (/formula)  dia de inicio - dia de termino real del plan - consumed days
                    // para calcular el dia de termino real del plan tomamos el dia de inicio del plan y calculamos en base al plan (mensual, anual, etc)
                    // $total_plan_days = $planStartDate->diffInDays($planStartDate->copy()->addMonths($freeze_plan->period_number));
                    // dd('dias que le quedan al plan', $);
                    // $restingDays = $total_plan_days - $consumed_days;

                $differenceDaysBillStartAgainstBillEnd = $billStart->diffInDays($billFinish);
                //  si los dias entre el inicio y termino del plan segun la boleta, es menor a los restringDays, algo anda mal

                if ($differenceDaysBillStartAgainstBillEnd < $restingDays) {
                    $freeze_plan->update(['days' => 15]);
                } else {
                    $freeze_plan->update(['days' => $restingDays]);
                }

                continue;
            }
               
            // arreglar planes que la fecha de inicio es mayor a la fecha de inicio de su congelacionamiento
            if ($freeze_plan->billId &&
                $startFreezing < $planStartDate && 
                $billStart < $startFreezing &&
                $billFinish > $finishFreezing) {
                // if the plan associated to this freezing is currently not freezed, this record should be revoked
                if ($freeze_plan->plan_status_id !== PlanStatus::FREEZED) {
                    $freeze_plan->update(['revoked' => true]);
                } else {
                    $restingDays = Carbon::parse($freeze_plan->startFreeze)->diffInDays(Carbon::parse($freeze_plan->billFinishDate));

                    $differenceDaysBillStartAgainstBillEnd = Carbon::parse($freeze_plan->billStartDate)->diffInDays(Carbon::parse($freeze_plan->billFinishDate));
                    
                    if ($differenceDaysBillStartAgainstBillEnd < $restingDays) {
                        $freeze_plan->update(['days' => 15]);
                    } else {
                        $freeze_plan->update(['days' => $restingDays]);
                    }
                }
            }
        }
    });

    Route::get('freeze-plans/revoked-activateds', function() {

        $freezed_plans = App\Models\Plans\PostponePlan::where('revoked', false)
                                                        ->join('plan_user', 'plan_user.id', '=', 'freeze_plans.plan_user_id')
                                                        ->get([
                                                            'freeze_plans.id', 'freeze_plans.plan_user_id', 'freeze_plans.revoked',
                                                            'plan_user.id as planUserId', 'plan_user.plan_status_id',
                                                        ]);
        
        foreach ($freezed_plans as $freezed) {
            if ($freezed->plan_status_id !== PlanStatus::FREEZED) {
                $freezed->update(['revoked' => true]);
            }
        }
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
    Route::resource('clases-types', 'Clases\ClaseTypeController')->except('create', 'edit');

    /**
     * CALENDAR CLASES ROUTES
     */
    Route::post('calendar/clases/delete', 'Clases\CalendarClasesController@destroy')
         ->name('admin.calendar.clasesday.destroy');

    /**
     *  POSTPONE PLANS ROUTE
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
    Route::post('dte/get-pdf', 'Bills\DTEController@show');
    Route::post('dte/get-issued-pdf', 'Bills\DTEController@getIssuedPDF');
    
    Route::post('dte/{plan_user_flow}/save-pdf', 'Bills\DTEController@savePDFThroughAPI');
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
     *  Settings Routes
     */
    Route::get('json-density-parameters', 'Settings\DensityParameterController@clasesDensities');
    Route::resource('density-parameters', 'Settings\DensityParameterController')
            ->only('index', 'store', 'update', 'destroy');

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
     *  WODS Routes
     */
    Route::resource('wods', 'Wods\WodController')->except('index', 'show')->middleware('role:1');

    /*
     *  Notifications TESTS
     */
    Route::get('notifications-send-push/{user_id}', 'Messages\NotificationController@sendOnePush')->middleware('role:1');
});

/*  *****************************************************

 *  *********         EXTERNAL ROUTES        ************

 *  ************************************************** */
Route::post('new-user/request-instructions', 'Web\NewUserController@requestInstructions');
Route::get('/new-user/{plan}/create', 'Web\NewUserController@create');
Route::resource('/new-user', 'Web\NewUserController')->except('index', 'update', 'destroy', 'create', 'show');

 /**   *****************************************************
  *    *******         EXTERNAL ROUTES        **************
  *   ******************************************************   */
Route::post('/flow/return-from-payment', 'Web\NewUserController@finishFlowPayment');
Route::post('/flow/confirm-payment', 'Web\NewUserController@finishFlowPayment');

Route::get('get-pdf/{plan_user_flow}', 'Web\NewUserController@getPlanUserFlowDTE');

Route::get('/flow/return', function () { return view('web.flow.return'); });
Route::get('/flow/error', function () { return view('web.flow.error'); });

Route::get('finish-registration', 'Web\NewUserController@finishing');

Route::get('maila', function() {
    $planuserFlow = App\Models\Plans\PlanUserFlow::find(2110);

    return Mail::to('raulberrios8@gmail.com')->send(new App\Mail\NewPlanUserEmail($planuserFlow));
});


