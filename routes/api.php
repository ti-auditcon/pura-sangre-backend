<?php

use Illuminate\Support\Facades\Route;

/** *****************************************************
*  *******       EXTERNAL ROUTES        ************** 
* ****************************************************** */
Route::post('/external/contact-form', 'Web\ContactEmailController@sendEmail');

Route::get('/planes/contractables', 'Web\PlanController@index');

Route::post('users/{user}/image', 'Users\UserController@image');
