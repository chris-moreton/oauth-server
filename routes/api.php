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

Route::group(['middleware' => ['client_credentials_all:admin-create']], function () {
    Route::post('users', 'UserController@store');
});

Route::group(['middleware' => ['auth:api', 'user_scopes_all:user-update']], function () {
    Route::put('/users/{id}', 'UserController@update');
});

Route::group(['middleware' => ['client_credentials_all:verify-password']], function () {
    Route::post('/users/{id}/passwordcheck', 'UserController@passwordcheck');
});

Route::group(['middleware' => ['auth:api', 'user_scopes_any:user-read']], function () {
    Route::get('/users/{email}', 'UserController@show');
});
    
// Route::group(['middleware' => ['client_credentials_all:admin-read']], function () {
//     Route::get('/admin-token-details', 'TokenController@adminTokenDetails');
// });
    
Route::group(['middleware' => ['auth:api', 'user_scopes_all:user-read']], function () {
    Route::get('/user-token-details', 'TokenController@userTokenDetails');
});
    