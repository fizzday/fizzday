<?php
use Fizzday\Routing\FizzRoute as Route;

/**
 * get 请求地址:
 * @api: /test
 */
Route::get('test', function(){
    echo "fizzday's route test success";
});

/**
 * get 请求地址:
 * @api: /admin/test
 */
Route::group('admin', function(){
    Route::get('test', function(){
        echo "fizzday's group route test success";
    });
});


Route::dispatch('View@process');
