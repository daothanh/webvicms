<?php

use Illuminate\Http\Request;
use Modules\Media\Repositories\MediaRepository;

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

Route::group(['prefix' => 'media', 'middleware' => ['api.auth']], function () {
    Route::bind('media', function ($id) {
        return app(MediaRepository::class)->find($id);
    });
    Route::bind('folder', function ($id) {
        return app(MediaRepository::class)->find($id);
    });
    Route::bind('file', function ($id) {
        return app(MediaRepository::class)->find($id);
    });

    Route::post('folder', [
        'uses' => 'FolderController@store',
        'as' => 'api.media.folders.store',
    ]);
    Route::post('folder/{folder}', [
        'uses' => 'FolderController@update',
        'as' => 'api.media.folders.update',
    ]);
    Route::delete('folder/{folder}', [
        'uses' => 'FolderController@destroy',
        'as' => 'api.media.folders.destroy',
    ]);

    Route::post('file', [
        'uses' => 'MediaController@store',
        'as' => 'api.media.store',
    ]);
    Route::post('file-dropzone', [
        'uses' => 'MediaController@storeDropzone',
        'as' => 'api.media.store-dropzone',
    ]);
    Route::post('media/link', [
        'uses' => 'MediaController@linkMedia',
        'as' => 'api.media.link',
    ]);
    Route::post('media/unlink', [
        'uses' => 'MediaController@unlinkMedia',
        'as' => 'api.media.unlink',
    ]);
    Route::post('media/move', [
        'uses' => 'MoveMediaController',
        'as' => 'api.media.media.move',
    ]);
    Route::get('media/index', [
        'uses' => 'MediaController@index',
        'as' => 'api.media.index',
    ]);
    Route::post('media/sort', [
        'uses' => 'MediaController@sortMedia',
        'as' => 'api.media.sort',
    ]);
    Route::get('media/find-first-by-zone-and-entity', [
        'uses' => 'MediaController@findFirstByZoneEntity',
        'as' => 'api.media.find-first-by-zone-and-entity',
    ]);

    Route::get('media/{media}', [
        'uses' => 'MediaController@find',
        'as' => 'api.media.media.find',
    ]);
    Route::put('file/{file}', [
        'uses' => 'MediaController@update',
        'as' => 'api.media.update',
    ]);
    Route::delete('file/{file}', [
        'uses' => 'MediaController@destroy',
        'as' => 'api.media.destroy',
    ]);
    Route::post('file/delete-multiple', [
        'uses' => 'MediaController@destroyMultiple',
        'as' => 'api.media.delete-multiple',
    ]);
});
