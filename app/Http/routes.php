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
            Route::get('/', [
                'as' => '/',
                'uses' => 'AdminController@index',
            ]);
            Route::resource('teams', 'TeamController');
            Route::resource('players', 'PlayerController');
            Route::resource('news', 'NewsController');
            Route::resource('matches', 'MatchController');
            Route::resource('profile', 'AdminController');
            Route::resource('awards', 'AwardController');
            Route::post('awards/delete_multi', [
                'as' => 'awards/delete_multi',
                'uses' => 'AwardController@deleteMulti'
            ]);

            Route::resource('seasons', 'SeasonController');

            Route::post('seasons/delete_multi', [
                'as' => 'seasons/delete_multi',
                'uses' => 'SeasonController@deleteMulti'
            ]);

            Route::resource('leagues', 'LeagueController');

            Route::post('leagues/delete_multi', [
                'as' => 'leagues/delete_multi',
                'uses' => 'LeagueController@deleteMulti'
            ]);
            Route::post('/getTotalNotification', [
                'as' => 'getTotalNotification',
                'uses' => 'AdminController@getTotalNotification'
            ]);
            Route::post('/getListNotifications', [
                'as' => 'getListNotifications',
                'uses' => 'AdminController@getListNotifications'
            ]);

        });

        Route::get('chart', [
            'as' => 'admin.chart',
            'uses' => 'HomeController@chart'
        ]);

    });
    Route::group(['prefix' => 'users', 'namespace' => 'User', 'middleware' => 'auth'], function () {
        Route::resource('profile', 'UserController');
        Route::get('/', [
            'as' => '/',
            'uses' => 'UserController@index',
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
