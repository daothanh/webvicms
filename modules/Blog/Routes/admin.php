<?php
Route::group(['prefix' => 'blog'], function () {
    Route::group(['prefix' => 'posts'], function () {

        Route::bind('post', function ($id) {
            return app(\Modules\Blog\Repositories\PostRepository::class)->find($id);
        });

        Route::get('/', ['uses' => 'PostController@index', 'as' => 'admin.blog.post.index'])->middleware(['permission:list posts']);
        Route::get('/create', ['uses' => 'PostController@create', 'as' => 'admin.blog.post.create'])->middleware(['permission:create post']);
        Route::get('/edit/{post}', ['uses' => 'PostController@edit', 'as' => 'admin.blog.post.edit'])->where('post', '[0-9]+')->middleware(['permission:edit post']);
        Route::get('/duplicate/{post}', ['uses' => 'PostController@duplicate', 'as' => 'admin.blog.post.duplicate'])->where('post', '[0-9]+')->middleware(['permission:create post']);
        Route::post('/store', ['uses' => 'PostController@store', 'as' => 'admin.blog.post.store'])->middleware(['permission:create post|edit post']);
    });

    Route::group(['prefix' => 'categories'], function () {

        Route::bind('category', function ($id) {
            return app(\Modules\Blog\Repositories\CategoryRepository::class)->find($id);
        });

        Route::get('/', ['uses' => 'CategoryController@index', 'as' => 'admin.blog.category.index'])->middleware(['permission:list categories']);
        Route::get('/create', ['uses' => 'CategoryController@create', 'as' => 'admin.blog.category.create'])->middleware(['permission:create category']);
        Route::get('/edit/{category}', ['uses' => 'CategoryController@edit', 'as' => 'admin.blog.category.edit'])->where('category', '[0-9]+')->middleware(['permission:edit category']);
        Route::post('/store', ['uses' => 'CategoryController@store', 'as' => 'admin.blog.category.store'])->middleware(['permission:create category|edit category']);
    });
});