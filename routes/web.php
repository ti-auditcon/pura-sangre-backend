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

// Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/', 'HomeController@index');
// Route::resource('students', 'StudentController'); //CRUDS students

Route::get('/', function () {return view('home');})->middleware('auth');

// Route::get('/blocks', 'HomeController@blocks')->name('bills.validates'); //validar recibo
// Route::get('/blocks/config', 'Controller@blocksshow')->name('blocks.config'); //configurar horario

Route::get('/reports', function () {
    return view('reports');
});

Route::get('/messages', function () {
  Article::where('sd');
    return view('messages');
});

Route::middleware(['auth'])->prefix('/')->group(function () {
    Route::resource('plans', 'Plans\PlanController');

    /**
     * Exercises Routes (exercises)
     */
    Route::resource('exercises', 'Exercises\ExerciseController');
    Route::resource('stages', 'Exercises\StageController');

    /**
    * Users Routes (alumnos, profes, admins)
    */
    Route::resource('users', 'Users\UserController');
    Route::resource('users.plans', 'Plans\PlanUserController');
  
    /**
     * Clases routes (clases, clases-alumnos, bloques)
     */
    Route::resource('blocks', 'Clases\BlockController');
      //Tal vez mas adelante se necesite el store de clases
    Route::resource('clases', 'Clases\ClaseController')->except('create', 'edit', 'store', 'update');
    Route::resource('clases.users', 'Clases\ClaseUserController')->only('store', 'update', 'destroy');
    // Route::resource('users.plans.installments', 'Bills\InstallmentController');
});