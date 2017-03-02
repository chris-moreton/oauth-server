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

Route::group(['middleware' => ['auth:api', 'scope:create-users']], function () {
    Route::post('users', 'UserController@store');
});

Route::group(['middleware' => ['auth:api', 'scope:update-users']], function () {
    Route::put('/users/{id}', 'UserController@update');
});

Route::group(['middleware' => ['client_credentials:verify-password']], function () {
    Route::post('/users/{id}/passwordcheck', 'UserController@passwordcheck');
});

Route::group(['middleware' => ['client_credentials:get-user-details-from-email']], function () {
    Route::get('/users/{email}', 'UserController@show');
});
    
Route::get('/token-details', 'TokenController@tokenDetails');
    