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

Route::group(['prefix' => 'blog'], function () {
    Route::group(['prefix' => '/posts', 'middleware' => ['api.auth']], function () {
        Route::bind('post', function ($id) {
            return app(\Modules\Blog\Repositories\PostRepository::class)->find($id);
        });
        Route::get('index', [
            'as' => 'api.blog.post.index',
            'uses' => 'PostController@index',
        ])->middleware(['permission:blog.post.list posts']);
        Route::post('delete/multiple', [
            'as' => 'api.blog.post.delete-multiple',
            'uses' => 'PostController@destroyMultiple',
        ])->middleware(['permission:blog.post.delete post']);
        Route::delete('delete/{id}', [
            'as' => 'api.blog.post.delete',
            'uses' => 'PostController@destroy',
        ])->where('id', '[0-9]+')->middleware(['permission:blog.post.delete post']);
        Route::post('force-delete/multiple', [
            'as' => 'api.blog.post.force-delete-multiple',
            'uses' => 'PostController@forceDestroyMultiple',
        ])->middleware(['permission:blog.post.delete post']);
        Route::delete('force-delete/{id}', [
            'as' => 'api.blog.post.force-delete',
            'uses' => 'PostController@forceDestroy',
        ])->where('id', '[0-9]+')->middleware(['permission:blog.post.delete post']);

        Route::post('store', [
            'as' => 'api.blog.post.store',
            'uses' => 'PostController@store',
        ])->middleware(['permission:blog.post.create post|blog.post.edit post']);
        Route::post('restore/multiple', [
            'as' => 'api.blog.post.restore-multiple',
            'uses' => 'PostController@restoreMultiple',
        ]);
        Route::post('restore/{id}', [
            'as' => 'api.blog.post.restore',
            'uses' => 'PostController@restore',
        ])->where('id', '[0-9]+');
        Route::post('toggle-status', [
            'as' => 'api.blog.post.toggle_status',
            'uses' => 'PostController@toggleStatus',
        ]);
    });
    Route::group(['prefix' => '/categories', 'middleware' => ['api.auth']], function () {
        Route::bind('category', function ($id) {
            return app(\Modules\Blog\Repositories\CategoryRepository::class)->find($id);
        });
        Route::get('index', [
            'as' => 'api.blog.category.index',
            'uses' => 'CategoryController@index',
        ])->middleware(['permission:blog.post_category.list categories']);
        Route::post('delete/multiple', [
            'as' => 'api.blog.category.delete-multiple',
            'uses' => 'CategoryController@destroyMultiple',
        ])->middleware(['middleware' => 'permission:blog.post_category.delete category']);
        Route::delete('delete/{id}', [
            'as' => 'api.blog.category.delete',
            'uses' => 'CategoryController@destroy',
        ])->where('id', '[0-9]+')->middleware(['middleware' => 'permission:blog.post_category.delete category']);
        Route::post('force-delete/multiple', [
            'as' => 'api.blog.category.force-delete-multiple',
            'uses' => 'CategoryController@forceDestroyMultiple',
        ])->middleware(['middleware' => 'permission:blog.post.delete category']);
        Route::delete('force-delete/{id}', [
            'as' => 'api.blog.category.force-delete',
            'uses' => 'CategoryController@forceDestroy',
        ])->where('id', '[0-9]+')->middleware(['middleware' => 'permission:blog.post_category.delete category']);

        Route::post('store', [
            'as' => 'api.blog.category.store',
            'uses' => 'CategoryController@store',
        ])->middleware(['middleware' => 'permission:blog.post_category.create category|blog.post_category.edit category']);
        Route::post('restore/multiple', [
            'as' => 'api.blog.category.restore-multiple',
            'uses' => 'CategoryController@restoreMultiple',
        ]);
        Route::post('restore/{id}', [
            'as' => 'api.blog.category.restore',
            'uses' => 'CategoryController@restore',
        ])->where('id', '[0-9]+');
        Route::post('toggle-status', [
            'as' => 'api.blog.category.toggle_status',
            'uses' => 'CategoryController@toggleStatus',
        ]);
    });
});
