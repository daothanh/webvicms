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

Route::group(['prefix' => 'languages', 'middleware' => ['api.auth']], function () {
    Route::get('index', 'LanguageController@index')->name('api.language.index');
    Route::put('toggle/{id}', 'LanguageController@toggleStatus')->name('api.language.toggle')->where('id', '[0-9]+');
    Route::put('default/{id}', 'LanguageController@makeDefault')->name('api.language.default')->where('id', '[0-9]+');
});
