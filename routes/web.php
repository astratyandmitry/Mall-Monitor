<?php

Route::get('/', 'DashboardController')->name('dashboard');

Route::group(['middleware' => 'guest'], function (): void {
    Route::get('/auth/signin', 'AuthController@signin')->name('auth.signin');
    Route::post('/auth/signin', 'AuthController@login');
});

Route::group(['middleware' => 'loggined'], function (): void {
    Route::get('/auth/signout', 'AuthController@signout')->name('auth.signout');

    Route::get('/stores', 'StoresController@index')->name('stores.index');
    Route::get('/store/{store}', 'StoresController@show')->name('stores.show');

    Route::get('/report', 'ReportController@index')->name('report.index');
    Route::get('/report/export', 'ReportController@export')->name('report.export');

    Route::get('/daily_report', 'DailyReportController@index')->name('daily_report.index');
    Route::get('/daily_report/export', 'DailyReportController@export')->name('daily_report.export');
});

