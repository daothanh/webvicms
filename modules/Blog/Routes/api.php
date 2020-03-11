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
        ]);
        Route::post('delete/multiple', [
            'as' => 'api.blog.post.delete-multiple',
            'uses' => 'PostController@destroyMultiple',
        ]);
        Route::delete('delete/{post}', [
            'as' => 'api.blog.post.delete',
            'uses' => 'PostController@destroy',
        ])->where('post', '[0-9]+');
        Route::post('force-delete/multiple', [
            'as' => 'api.blog.post.force-delete-multiple',
            'uses' => 'PostController@forceDestroyMultiple',
        ]);
        Route::delete('force-delete/{postId}', [
            'as' => 'api.blog.post.force-delete',
            'uses' => 'PostController@forceDestroy',
        ])->where('postId', '[0-9]+');

        Route::post('store', [
            'as' => 'api.blog.post.store',
            'uses' => 'PostController@store',
        ]);
        Route::post('restore/multiple', [
            'as' => 'api.blog.post.restore-multiple',
            'uses' => 'PostController@restoreMultiple',
        ]);
        Route::post('restore/{postId}', [
            'as' => 'api.blog.post.restore',
            'uses' => 'PostController@restore',
        ])->where('postId', '[0-9]+');
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
        ]);
        Route::post('delete/multiple', [
            'as' => 'api.blog.category.delete-multiple',
            'uses' => 'CategoryController@destroyMultiple',
        ]);
        Route::delete('delete/{category}', [
            'as' => 'api.blog.category.delete',
            'uses' => 'CategoryController@destroy',
        ])->where('category', '[0-9]+');
        Route::post('force-delete/multiple', [
            'as' => 'api.blog.category.force-delete-multiple',
            'uses' => 'CategoryController@forceDestroyMultiple',
        ]);
        Route::delete('force-delete/{categoryId}', [
            'as' => 'api.blog.category.force-delete',
            'uses' => 'CategoryController@forceDestroy',
        ])->where('categoryId', '[0-9]+');

        Route::post('store', [
            'as' => 'api.blog.category.store',
            'uses' => 'CategoryController@store',
        ]);
        Route::post('restore/multiple', [
            'as' => 'api.blog.category.restore-multiple',
            'uses' => 'CategoryController@restoreMultiple',
        ]);
        Route::post('restore/{categoryId}', [
            'as' => 'api.blog.category.restore',
            'uses' => 'CategoryController@restore',
        ])->where('categoryId', '[0-9]+');
        Route::post('toggle-status', [
            'as' => 'api.blog.category.toggle_status',
            'uses' => 'CategoryController@toggleStatus',
        ]);
    });
});