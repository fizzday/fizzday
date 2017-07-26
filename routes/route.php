<?php

Route::get('', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::any('test', 'HomeController@test');
Route::get('test2', 'HomeController@test2');
Route::get('nologin/login', 'NologinController@login');
//Route::get('test2', function () {
//    echo 333;
//});

