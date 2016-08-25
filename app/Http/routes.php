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
Route::group(['middleware' => 'web'], function () {
    Route::get('/', [
        'as' => '/',
        'uses' => 'HomeController@index',
    ]);
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin',
        'middleware' => 'auth'], function () {
        /*ajax request*/    
        Route::get('/', [
            'as' => 'admin.welcome',
            'uses' => 'AdminController@welcome'
        ]);
        Route::post('/getTotalNotification', [
            'as' => 'getTotalNotification',
            'uses' => 'AdminController@getTotalNotification'
        ]);
        Route::post('/getListNotifications', [
            'as' => 'getListNotifications',
            'uses' => 'AdminController@getListNotifications'
        ]);
        Route::post('/addComment', [
            'as' => 'addComment',
            'uses' => 'NewsController@addComment'
        ]);
        /*************/

        Route::resource('news', 'NewsController');
        Route::resource('matches', 'MatchController');
    });
    Route::group(['prefix' => 'users', 'namespace' => 'User', 
        'middleware' => 'auth'], function () {
        /*ajax request*/    
        Route::get('/', [
            'as' => 'users.welcome',
            'uses' => 'UserController@welcome'
        ]);
        Route::post('/getTotalNotification', [
            'as' => 'getTotalNotification',
            'uses' => 'UserController@getTotalNotification'
        ]);
        Route::post('/getListNotifications', [
            'as' => 'getListNotifications',
            'uses' => 'UserController@getListNotifications'
        ]);
        Route::post('/addComment', [
            'as' => 'addComment',
            'uses' => 'NewsController@addComment'
        ]);
        /**************/
        Route::resource('news', 'NewsController');
        Route::resource('matches', 'MatchController', ['only' => ['index', 'show']]);
    });
});
