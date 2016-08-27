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
        'uses' => 'HomeController@welcome',
    ]);
    Route::get('register/verify/{confirmation_code}', [
        'as' => 'user.active',
        'uses' => 'Auth\AuthController@confirm'
    ]);
    Route::get('language/{lang}', [
        'as' => 'lang',
        'uses' => 'HomeController@chooseLanguage'
    ]);
    Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
        Route::group(['namespace' => 'Admin'], function() {
            Route::resource('teams', 'TeamController');
            Route::resource('players', 'PlayerController');
            Route::resource('news', 'NewsController');
            Route::resource('matches', 'MatchController');
            Route::resource('profile', 'AdminController');

        });

        Route::get('chart', [
            'as' => 'admin.chart',
            'uses' => 'HomeController@chart'
        ]);

    });
    Route::group(['prefix' => 'users', 'namespace' => 'User', 'middleware' => 'auth'], function () {
        Route::resource('profile', 'UserController');
        Route::post('/getTotalNotification', [
            'as' => 'getTotalNotification',
            'uses' => 'AdminController@getTotalNotification'
        ]);
        Route::post('/getListNotifications', [
            'as' => 'getListNotifications',
            'uses' => 'AdminController@getListNotifications'
        ]);

        Route::resource('news', 'NewsController');
        Route::resource('matches', 'MatchController');
    });

     Route::group(['prefix' => 'login'], function () {
        Route::get('social/{network}', [
            'as' => 'loginSocialNetwork',
            'uses' => 'SocialNetworkController@callback',
        ]);
        Route::get('{accountSocial}/redirect', [
            'as' => 'redirectSocialNetwork',
            'uses' => 'SocialNetworkController@redirect',
        ]);
    });
});

