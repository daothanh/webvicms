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

Route::group(['prefix' => 'testimonials'], function () {
    Route::bind('testimonial', function ($id) {
        return app(\Modules\Testimonial\Repositories\TestimonialRepository::class)->find($id);
    });
    Route::get('/', ['uses' => 'TestimonialController@index', 'as' => 'api.testimonial.index'])->middleware(['api.permission:testimonial.list testimonials']);
    Route::post('storage', ['uses' => 'TestimonialController@storage', 'as' => 'api.testimonial.storage'])->middleware(['api.permission:testimonial.create testimonial|testimonial.edit testimonial']);
    Route::delete('delete/{testimonial}', ['uses' => 'TestimonialController@delete', 'as' => 'api.testimonial.delete'])->where('testimonial', '[0-9]+')->middleware(['api.permission:testimonial.delete testimonial']);
    Route::delete('force-delete/{testimonialId}', ['uses' => 'TestimonialController@forceDelete', 'as' => 'api.testimonial.force-delete'])->where('testimonialId', '[0-9]+')->middleware(['api.permission:testimonial.delete testimonial']);
    Route::post('restore/{testimonialId}', ['uses' => 'TestimonialController@restore', 'as' => 'api.testimonial.restore'])->where('testimonialId', '[0-9]+')->middleware(['api.permission:testimonial.create testimonial|testimonial.edit testimonial']);
});
