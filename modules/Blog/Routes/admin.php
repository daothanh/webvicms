<?php
Route::group(['prefix' => 'blog'], function () {
    Route::group(['prefix' => 'posts'], function () {
        Route::get('/', ['uses' => 'PostController@index', 'as' => 'admin.blog.post.index'])->middleware(['permission:blog.post.list posts']);
        Route::get('/create', ['uses' => 'PostController@create', 'as' => 'admin.blog.post.create'])->middleware(['permission:blog.post.create post']);
        Route::get('/edit/{id}', ['uses' => 'PostController@edit', 'as' => 'admin.blog.post.edit'])->where('id', '[0-9]+')->middleware(['permission:blog.post.edit post']);
        Route::get('/duplicate/{id}', ['uses' => 'PostController@duplicate', 'as' => 'admin.blog.post.duplicate'])->where('id', '[0-9]+')->middleware(['permission:blog.post.create post']);
        Route::post('/store', ['uses' => 'PostController@store', 'as' => 'admin.blog.post.store'])->middleware(['permission:blog.post.create post|blog.post.edit post']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', ['uses' => 'CategoryController@index', 'as' => 'admin.blog.category.index'])->middleware(['permission:blog.post_category.list categories']);
        Route::get('/create', ['uses' => 'CategoryController@create', 'as' => 'admin.blog.category.create'])->middleware(['permission:blog.post_category.create category']);
        Route::get('/edit/{id}', ['uses' => 'CategoryController@edit', 'as' => 'admin.blog.category.edit'])->where('id', '[0-9]+')->middleware(['permission:blog.post_category.edit category']);
        Route::post('/store', ['uses' => 'CategoryController@store', 'as' => 'admin.blog.category.store'])->middleware(['permission:blog.post_category.create category|blog.post_category.edit category']);
    });
});
