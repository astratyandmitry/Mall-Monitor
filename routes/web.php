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

    Route::get('/report_mall', 'ReportMallController@index')->name('report_mall.index');
    Route::get('/report_mall/export', 'ReportMallController@export')->name('report_mall.export');

    Route::get('/report_store', 'ReportStoreController@index')->name('report_store.index');
    Route::get('/report_store/export', 'ReportStoreController@export')->name('report_store.export');

    Route::get('/report_detail', 'ReportDetailController@index')->name('report_detail.index');
    Route::get('/report_detail/export', 'ReportDetailController@export')->name('report_detail.export');
});

