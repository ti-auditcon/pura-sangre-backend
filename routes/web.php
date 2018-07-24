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

Route::get('/', function () {
    return view('home');
});

Auth::routes();
Route::resource('students', 'StudentController'); //CRUDS students


Route::get('/blocks', 'HomeController@blocks')->name('bills.validates'); //validar recibo
Route::get('/blocks/1', 'HomeController@blocksshow')->name('blocks.show'); //validar recibo

Route::get('/reports', function () {
    return view('reports');
});

Route::get('/messages', function () {
    return view('messages');
});
