<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);//->middleware('auth.basic')
Route::get('change/language/{newLocale}', ['uses' => 'LanguageController@changeLocale', 'as' => 'change.language'])->where('newLocale', '[a-zA-Z_-]+');
Route::get('contact', ['uses' => 'HomeController@contact', 'as' => 'contact']);
Route::post('contact/send', ['uses' => 'HomeController@contactSend', 'as' => 'contact.send']);
Route::match(['post', 'get'], 'terminal', ['uses' => 'TerminalController@index', 'as' => 'terminal'])->middleware(['auth', 'role:admin']);
Route::group(['prefix' => 'install'], function () {
    Route::match(['get', 'post'], '/', 'InstallController@index')->name('install.app');
});