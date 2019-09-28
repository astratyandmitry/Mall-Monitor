<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'API'], function (): void {
    Route::post('/visits/import/excel', 'VisitsImportExcelController');
});

Route::group(['middleware' => 'auth.basic:api,username', 'namespace' => 'API'], function (): void {
    Route::post('/cheques/import/xml', 'ChequesImportXMLController');
    Route::post('/cheques/import/excel', 'ChequesImportExcelController');
});
