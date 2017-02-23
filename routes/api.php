<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', 'UserController');//->middleware('scope:access-admin-information');

Route::post('users', 'UserController@store');//->middleware('scope:access-admin-information');

Route::post('users/{id}/passwordcheck', 'UserController@passwordcheck');//->middleware('scope:access-admin-information');

Route::group(['middleware' => []], function () {
    Route::get('users', 'UserController@index');
});
