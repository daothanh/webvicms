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

Route::group(['prefix' => 'sliders'], function () {
    Route::bind('slider', function ($slider) {
        return app(\Modules\Slider\Repositories\SliderRepository::class)->find($slider) ?? abort(404);
    });
    Route::get('/', ['uses' => 'SliderController@index', 'as' => 'api.slider.index']);
    Route::post('/storage', ['uses' => 'SliderController@storage', 'as' => 'api.slider.storage']);
    Route::delete('/delete/{slider}', ['uses' => 'SliderController@delete', 'as' => 'api.slider.delete']);


    Route::group(['prefix' => '{slider}/items'], function () {

        Route::bind('slide', function ($slide) {
            return app(\Modules\Slider\Repositories\SliderItemRepository::class)->find($slide) ?? abort(404);
        });
        Route::get('/', ['uses' => 'SliderItemController@index', 'as' => 'api.slider.item.index']);
        Route::post('/storage', ['uses' => 'SliderItemController@storage', 'as' => 'api.slider.item.storage']);
        Route::delete('/delete/{slide}', ['uses' => 'SliderItemController@delete', 'as' => 'api.slider.item.delete']);
    });
});
