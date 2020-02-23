<?php
Route::group(['prefix' => 'media'], function () {

    Route::bind('media', function ($id) {
        return app(\Modules\Media\Repositories\MediaRepository::class)->find($id);
    });

    Route::get('/', [
        'as' => 'admin.media.index',
        'uses' => 'MediaController@index',
    ])->middleware('permission:list media');

    Route::post('post', [
        'as' => 'admin.media.store',
        'uses' => 'MediaController@store',
    ])->middleware('permission:create media');
    Route::get('{media}/edit', [
        'as' => 'admin.media.edit',
        'uses' => 'MediaController@edit',
    ])->middleware('permission:edit media');
    Route::post('update', [
        'as' => 'admin.media.update',
        'uses' => 'MediaController@update',
    ])->middleware('permission:edit media');
    Route::delete('{media}', [
        'as' => 'admin.media.destroy',
        'uses' => 'MediaController@destroy',
    ])->middleware('permission:delete media');

    Route::get('grid/index', [
        'uses' => 'MediaGridController@index',
        'as' => 'media.grid.select',
    ]);
    Route::get('grid/ckIndex', [
        'uses' => 'MediaGridController@ckIndex',
        'as' => 'media.grid.ckeditor',
    ]);
});
