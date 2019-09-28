<?php

Route::group(['middleware' => 'auth.basic:api,username', 'namespace' => 'API'], function (): void {
    Route::post('/cheques/import/xml', 'ChequesImportXMLController');
    Route::post('/cheques/import/excel', 'ChequesImportExcelController');
    Route::post('/visits/import/excel', 'VisitsImportExcelController');
});
