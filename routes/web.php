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


// Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/', 'HomeController@index');
// Route::resource('students', 'StudentController'); //CRUDS students

Auth::routes();

Route::get('/', function () {return view('home');})->middleware('auth');


//
// Route::get('/blocks', 'HomeController@blocks')->name('bills.validates'); //validar recibo
// Route::get('/blocks/config', 'Controller@blocksshow')->name('blocks.config'); //configurar horario

Route::get('/reports', function () {
    return view('reports');
});

Route::get('/messages', function () {
    return view('messages');
});

/**
 * Users Routes (alumnos)
 */
Route::middleware(['auth'])->prefix('/')->group(function () {
  Route::resource('plans', 'Plans\PlanController');
  Route::resource('users', 'Users\UserController');
  Route::resource('users.plans', 'Plans\PlanUserController');
  Route::resource('blocks', 'Clases\BlockController');
  Route::resource('clases', 'Clases\ClaseController');
  ///holas
  // Route::resource('users.plans.installments', 'Bills\InstallmentController');
});

/**
 * Plans Routes (alumnos)
 * @var [type]
 */
// Route::middleware(['auth'])->prefix('/')->group(function () {
//   Route::resource('plans', 'Plans\PlanController');
//
// });

Auth::routes();
