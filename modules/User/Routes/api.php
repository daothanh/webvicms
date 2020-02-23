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

Route::get('login', ['uses' => 'AuthController@login', 'as' => 'api.login']);
Route::group(['prefix' => 'profile', 'middleware' => ['api.auth']], function () {
    Route::get('/', ['uses' => 'ProfileController@index', 'as' => 'api.profile']);
    Route::post('settings', ['uses' => 'ProfileController@settings', 'as' => 'api.profile.settings']);
});

Route::group(['prefix' => 'users', 'middleware' => ['api.auth']], function () {
    Route::get('/', ['uses' => 'UserController@index', 'as' => 'api.user.index'])->middleware(['api.permission:list users']);
    Route::post('/storage', ['uses' => 'UserController@storage', 'as' => 'api.user.storage'])->middleware(['api.permission:create user|edit user']);
    Route::post('/delete-multiple', ['uses' => 'UserController@deleteMultiple', 'as' => 'api.user.delete-multiple'])->middleware(['api.permission:delete user']);
    Route::delete('/delete/{id}', ['uses' => 'UserController@delete', 'as' => 'api.user.delete'])->middleware(['api.permission:delete user']);
});

Route::group(['prefix' => 'roles', 'middleware' => ['api.auth']], function () {
    Route::get('/', ['uses' => 'RoleController@index', 'as' => 'api.role.index'])->middleware(['api.permission:list roles']);
    Route::post('/storage', ['uses' => 'RoleController@storage', 'as' => 'api.role.storage'])->middleware(['api.permission:create role|edit role']);
    Route::delete('/delete/{id}', ['uses' => 'RoleController@delete', 'as' => 'api.role.delete'])->middleware(['api.permission:delete role']);
});

