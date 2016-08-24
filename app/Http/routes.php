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
Route::auth();
Route::get('/', function () {
    return view('welcome');
});
Route::group(['middleware' => 'web'], function () {
    Route::get('/', [
        'as' => '/',
        'uses' => 'HomeController@index',
    ]);
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [
            'as' => 'admin.welcome',
            'uses' => 'HomeController@welcome'
        ]);
    });
    Route::group(['namespace' => 'User'], function () {
        Route::resource('users', 'UserController');
        Route::get('/', [
            'as' => 'users.index',
            'uses' => 'UserController@index'
        ]);
        Route::resource('news', 'NewController');
        Route::resource('matches', 'MatchController', ['only' => ['index', 'show']]);
    });
    Route::group(['prefix' => 'users'], function () {
        Route::get('home', [
            'as' => 'home',
            'uses' => 'HomeController@index'
        ]);
    });
});