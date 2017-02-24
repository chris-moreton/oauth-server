<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:api', 'scope:admin']], function () {
    Route::get('/users/{email}', 'UserController@show');
    Route::post('users', 'UserController@store');
    Route::put('/users/{id}', 'UserController@update');
    Route::post('users/{id}/passwordcheck', 'UserController@passwordcheck');
});
