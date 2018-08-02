<?php

Route::group(['middleware' => 'auth:api', 'namespace' => 'API'], function (): void {
    Route::post('/cheques/test_post', 'ChecuesController@test_post');
    Route::get('/cheques/test_get', 'ChecuesController@test_get');
});
