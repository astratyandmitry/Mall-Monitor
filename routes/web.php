<?php

Route::get('/', 'HomeController@redirect')->name('home');
Route::get('/dashboard', 'DashboardController')->name('dashboard');

Route::group(['middleware' => 'guest'], function (): void {
    Route::get('/auth/signin', 'AuthController@signin')->name('auth.signin');
    Route::post('/auth/signin', 'AuthController@login');
});

Route::group(['middleware' => 'loggined'], function (): void {
    Route::get('/auth/signout', 'AuthController@signout')->name('auth.signout');

    // ajax
    Route::post('/ajax/cashboxes', 'AjaxController@cashboxes')->name('ajax.cashboxes');

    Route::get('/stores', 'StoresController@index')->name('stores.index');
    Route::get('/store/{store}', 'StoresController@show')->name('stores.show');

    Route::prefix('reports')->namespace('Reports')->name('reports.')->group(function () {
        Route::get('/mall', 'ReportsMallController@index')->name('mall.index');
        Route::get('/mall/export/excel', 'ReportsMallController@exportExcel')->name('mall.export.excel');
        Route::get('/mall/export/pdf', 'ReportsMallController@exportPDF')->name('mall.export.pdf');

        Route::get('/store', 'ReportsStoreController@index')->name('store.index');
        Route::get('/store/export/excel', 'ReportsStoreController@exportExcel')->name('store.export.excel');
        Route::get('/store/export/pdf', 'ReportsStoreController@exportPDF')->name('store.export.pdf');

        Route::get('/detail', 'ReportsDetailController@index')->name('detail.index');
        Route::get('/detail/export/excel', 'ReportsDetailController@exportExcel')->name('detail.export.excel');
        Route::get('/detail/export/pdf', 'ReportsDetailController@exportPDF')->name('detail.export.pdf');
    });
});

