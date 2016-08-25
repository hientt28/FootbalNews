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
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [
            'as' => 'admin.welcome',
            'uses' => 'HomeController@welcome'
        ]);
        Route::get('chart', [
            'as' => 'admin.chart',
            'uses' => 'HomeController@chart'
        ]);
        Route::resource('teams', 'Admin\TeamController');
        Route::resource('players', 'Admin\PlayerController');
    });
    Route::group(['prefix' => 'users', 'namespace' => 'User'], function () {
        Route::resource('profile', 'UserController');
        Route::get('/', [
            'as' => 'users.profile.welcome',
            'uses' => 'UserController@welcome'
        ]);
        Route::resource('news', 'NewController');
        Route::resource('matches', 'MatchController', ['only' => ['index', 'show']]);
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