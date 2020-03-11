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

Route::group(['prefix' => 'commerce'], function () {
    Route::group(['prefix' => '/products', 'middleware' => ['api.auth']], function () {
        Route::bind('post', function ($id) {
            return app(\Modules\Commerce\Repositories\ProductRepository::class)->find($id);
        });
        Route::get('index', [
            'as' => 'api.commerce.product.index',
            'uses' => 'ProductController@index',
        ]);
        Route::post('delete/multiple', [
            'as' => 'api.commerce.product.delete-multiple',
            'uses' => 'ProductController@destroyMultiple',
        ]);
        Route::delete('delete/{product}', [
            'as' => 'api.commerce.product.delete',
            'uses' => 'ProductController@destroy',
        ])->where('post', '[0-9]+');
        Route::post('force-delete/multiple', [
            'as' => 'api.commerce.product.force-delete-multiple',
            'uses' => 'ProductController@forceDestroyMultiple',
        ]);
        Route::delete('force-delete/{productId}', [
            'as' => 'api.commerce.product.force-delete',
            'uses' => 'ProductController@forceDestroy',
        ])->where('productId', '[0-9]+');

        Route::post('store', [
            'as' => 'api.commerce.product.store',
            'uses' => 'ProductController@store',
        ]);
        Route::post('restore/multiple', [
            'as' => 'api.commerce.product.restore-multiple',
            'uses' => 'ProductController@restoreMultiple',
        ]);
        Route::post('restore/{productId}', [
            'as' => 'api.commerce.product.restore',
            'uses' => 'ProductController@restore',
        ])->where('productId', '[0-9]+');
        Route::post('toggle-status', [
            'as' => 'api.commerce.product.toggle_status',
            'uses' => 'ProductController@toggleStatus',
        ]);
    });
    Route::group(['prefix' => '/categories', 'middleware' => ['api.auth']], function () {
        Route::bind('category', function ($id) {
            return app(\Modules\Commerce\Repositories\CategoryRepository::class)->find($id);
        });
        Route::get('index', [
            'as' => 'api.commerce.category.index',
            'uses' => 'CategoryController@index',
        ]);
        Route::post('delete/multiple', [
            'as' => 'api.commerce.category.delete-multiple',
            'uses' => 'CategoryController@destroyMultiple',
        ]);
        Route::delete('delete/{category}', [
            'as' => 'api.commerce.category.delete',
            'uses' => 'CategoryController@destroy',
        ])->where('category', '[0-9]+');
        Route::post('force-delete/multiple', [
            'as' => 'api.commerce.category.force-delete-multiple',
            'uses' => 'CategoryController@forceDestroyMultiple',
        ]);
        Route::delete('force-delete/{categoryId}', [
            'as' => 'api.commerce.category.force-delete',
            'uses' => 'CategoryController@forceDestroy',
        ])->where('categoryId', '[0-9]+');

        Route::post('store', [
            'as' => 'api.commerce.category.store',
            'uses' => 'CategoryController@store',
        ]);
        Route::post('restore/multiple', [
            'as' => 'api.commerce.category.restore-multiple',
            'uses' => 'CategoryController@restoreMultiple',
        ]);
        Route::post('restore/{categoryId}', [
            'as' => 'api.commerce.category.restore',
            'uses' => 'CategoryController@restore',
        ])->where('categoryId', '[0-9]+');
        Route::post('toggle-status', [
            'as' => 'api.commerce.category.toggle_status',
            'uses' => 'CategoryController@toggleStatus',
        ]);
    });
});