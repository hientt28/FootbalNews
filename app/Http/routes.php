<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');
    

Route::group(['middleware' => 'web'], function () {
    Route::get('/' , ['as' =>'home', 'uses' => 'HomeController@index']);
    Route::group(['middleware' => 'isUser'], function () {
        Route::group(['namespace' => 'User'], function () {
            Route::resource('news', 'NewController');
            Route::resource('matches', 'MatchController', ['only' => ['index', 'show']]); 
        });
        Route::resource('users', 'UserController');
    });
    
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [
            'as' => 'admin.welcome',
            'uses' => 'HomeController@welcome'
        ]);
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('home', [
            'as' => 'home',
            'uses' => 'HomeController@index'
        ]);
    });
});
