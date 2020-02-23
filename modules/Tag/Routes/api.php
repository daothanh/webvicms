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

Route::group(['prefix' => 'tags'], function () {
    Route::get('/', ['uses' => 'TagController@index', 'as' => 'api.tag.index']);
    Route::post('/create', ['uses' => 'TagController@create', 'as' => 'api.tag.create']);
    Route::delete('/delete/{id}', ['uses' => 'TagController@delete', 'as' => 'api.tag.delete']);
    Route::get('namespace', [
        'as' => 'api.tag.by-namespace',
        'uses' => 'TagByNamespaceController',
    ]);
});
