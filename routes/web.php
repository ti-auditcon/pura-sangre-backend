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


Route::get('/', function () {return view('home');})->middleware('auth');


Route::get('/messages', function () {
  Article::where('sd');
    return view('messages');
});

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
    Route::resource('clases.users', 'Clases\ClaseUserController')
           ->only('store', 'update', 'destroy');

    Route::resource('reservation', 'Clases\ReservationController')
           ->only('store', 'update', 'destroy');
    Route::post('clases/type-select/', 'Clases\ClaseController@typeSelect')->name('clases.type');
    Route::get('get-clases', 'Clases\ClaseController@clases');
    Route::get('get-wods', 'Clases\ClaseController@wods');

    /**
     * Payments Routes
     */
    // Route::resource('payments', 'Plans\PlanUserController')->middleware('role:1');

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
    Route::resource('users.plans', 'Plans\PlanUserController');
    Route::resource('users.plans.payments', 'Plans\PlanUserPaymentController');

});
