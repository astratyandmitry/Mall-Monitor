<?php

Route::get('/', 'HomeController@redirect')->name('home');

Route::group(['middleware' => 'guest'], function (): void {
    Route::get('/auth/signin', 'AuthController@signin')->name('auth.signin');
    Route::post('/auth/signin', 'AuthController@login');
    Route::get('/auth/activate', 'AuthController@activate')->name('auth.activate');
});

Route::group(['middleware' => 'loggined'], function (): void {
    Route::get('/dashboard', 'DashboardController')->name('dashboard');
    Route::get('/auth/signout', 'AuthController@signout')->name('auth.signout');

    // ajax
    Route::post('/ajax/cashboxes', 'AjaxController@cashboxes')->name('ajax.cashboxes');
    Route::post('/ajax/stores', 'AjaxController@stores')->name('ajax.stores');

    Route::get('/malls', 'MallsController@index')->name('malls.index');
    Route::get('/mall/{mall}', 'MallsController@show')->name('malls.show');

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

    Route::prefix('manage')->namespace('Manage')->name('manage.')->middleware('manage')->group(function () {
        Route::resource('/malls', 'ManageMallsController')->except(['destory']);
        Route::resource('/stores', 'ManageStoresController')->except(['destory']);
        Route::get('/stores/{anyStore}/toggle', 'ManageStoresController@toggle')->name('stores.toggle');
        Route::resource('/cashboxes', 'ManageCashboxesController')->except(['destory']);
        Route::get('/cashboxes/{anyCashbox}/toggle', 'ManageCashboxesController@toggle')->name('cashboxes.toggle');

        Route::resource('/users', 'ManageUsersController')->except(['destory']);
        Route::get('/users/{anyUser}/toggle', 'ManageUsersController@toggle')->name('users.toggle');

        Route::resource('/cities', 'ManageCitiesController')->except(['destory']);
        Route::resource('/store_types', 'ManageStoreTypesController')->except(['destory']);
    });
});

