<?php
use Fizzday\Routing\FizzRoute as Route;
use App\Controllers;

/**
 * get 请求地址:
 * @api: /test
 */
Route::get('test', function(){
    echo "fizzday's route test success";
});

Route::get('', 'HomeController@index');

Route::get('home/test', 'HomeController@test');

/**
 * get 请求地址:
 * @api: /admin/test
 */
Route::group('admin', function(){
    Route::get('index', 'Admin\AdminController@index');
    Route::get('test', function(){
        echo "fizzday's group route test success";
    });
});


Route::dispatch();
