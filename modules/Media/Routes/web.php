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

Route::prefix('media')->group(function() {
    Route::get('grid/index', [
        'uses' => 'MediaController@grid',
        'as' => 'media.public.grid.select',
    ]);

    Route::get('grid/ckIndex', [
        'uses' => 'MediaController@ckIndex',
        'as' => 'media.public.grid.ckeditor',
    ]);

    Route::post('store', [
        'uses' => 'MediaController@store',
        'as' => 'media.public.store',
    ]);

    Route::post('store-dropzone', [
        'uses' => 'MediaController@storeDropzone',
        'as' => 'media.public.store-dropzone',
    ]);
});
