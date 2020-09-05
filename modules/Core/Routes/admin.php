<?php

Route::get('/', ['uses' => 'DashboardController@index', 'as' => 'admin']);
Route::get('/lang/{locale}', ['uses' => 'DashboardController@changeLang', 'as' => 'admin.lang.change'])->where('locale', '[a-zA-Z-_]+');
Route::get('/set/cookie', ['uses' => 'DashboardController@setCookie', 'as' => 'admin.set.cookie']);

Route::group(['prefix' => 'settings', 'middleware' => 'role:admin'], function () {
    Route::get('/general', ['uses' => 'SettingsController@index', 'as' => 'admin.settings.index']);
    Route::post('/store', ['uses' => 'SettingsController@store', 'as' => 'admin.settings.store']);
    Route::match(['get', 'post'], '/mail-server', ['uses' => 'SettingsController@mailServer', 'as' => 'admin.settings.mail-server']);
    Route::match(['get', 'post'], '/account', ['uses' => 'SettingsController@account', 'as' => 'admin.settings.account']);
    Route::match(['get', 'post'], '/company', ['uses' => 'SettingsController@company', 'as' => 'admin.settings.company']);
    Route::get('languages', 'LanguageController@index')->name('admin.languages');
    Route::get('clear-cache', 'SettingsController@clearCache')->name('admin.settings.clear_cache');
});

