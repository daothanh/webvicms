<?php

Route::group(['prefix' => 'tags'], function () {
    Route::bind('tag', function ($id) {
        return app(\Modules\Tag\Repositories\TagRepository::class)->find($id);
    });
    Route::get('/', [
        'as' => 'admin.tag.index',
        'uses' => 'TagController@index'
    ])->middleware('permission:list tags');
    Route::get('create', [
        'as' => 'admin.tag.create',
        'uses' => 'TagController@create'
    ])->middleware('permission:create tag');
    Route::post('store', [
        'as' => 'admin.tag.store',
        'uses' => 'TagController@store'
    ])->middleware('permission:create tag');
    Route::get('{tag}/edit', [
        'as' => 'admin.tag.edit',
        'uses' => 'TagController@edit'
    ])->middleware('permission:edit tag');
    Route::post('update', [
        'as' => 'admin.tag.update',
        'uses' => 'TagController@update',
    ])->middleware('permission:edit tag');
    Route::delete('delete/{tag}', [
        'as' => 'admin.tag.destroy',
        'uses' => 'TagController@destroy',
    ])->middleware('permission:delete tag');
});
