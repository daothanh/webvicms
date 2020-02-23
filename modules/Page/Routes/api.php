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

Route::group(['prefix' => '/pages', 'middleware' => ['api.auth']], function () {
    Route::bind('page', function ($id) {
        return app(\Modules\Page\Repositories\PageRepository::class)->find($id);
    });
    Route::get('index', [
        'as' => 'api.page.index',
        'uses' => 'PageController@index',
    ]);
    Route::post('delete/multiple', [
        'as' => 'api.page.delete-multiple',
        'uses' => 'PageController@destroyMultiple',
    ]);
    Route::delete('delete/{page}', [
        'as' => 'api.page.delete',
        'uses' => 'PageController@destroy',
    ])->where('page', '[0-9]+');
    Route::post('force-delete/multiple', [
        'as' => 'api.page.force-delete-multiple',
        'uses' => 'PageController@forceDestroyMultiple',
    ]);
    Route::delete('force-delete/{pageId}', [
        'as' => 'api.page.force-delete',
        'uses' => 'PageController@forceDestroy',
    ])->where('pageId', '[0-9]+');

    Route::post('store', [
        'as' => 'api.page.store',
        'uses' => 'PageController@store',
    ]);
    Route::post('restore/multiple', [
        'as' => 'api.page.restore-multiple',
        'uses' => 'PageController@restoreMultiple',
    ]);
    Route::post('restore/{pageId}', [
        'as' => 'api.page.restore',
        'uses' => 'PageController@restore',
    ])->where('pageId', '[0-9]+');
    Route::post('toggle-status', [
        'as' => 'api.page.toggle_status',
        'uses' => 'PageController@toggleStatus',
    ]);
});
