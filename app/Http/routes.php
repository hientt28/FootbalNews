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
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/', [
            'as' => 'admin.welcome',
            'uses' => 'HomeController@welcome'
        ]);
    });
    Route::group(['prefix' => 'users', 'namespace' => 'User'], function () {
        Route::get('/', [
            'as' => 'users.welcome',
            'uses' => 'UserController@welcome'
        ]);
        Route::resource('news', 'NewController');
        Route::resource('matches', 'MatchController', ['only' => ['index', 'show']]);
    });
    // Route::get('login/{social}', [
    //     'as' => 'login.{social}',
    //     'uses' => 'SocialNetworkController@redirect'
    // ]);
    // Route::get('login/{social}/callback', [
    //     'as' => 'login.{social}.callback',
    //     'uses' => 'SocialNetworkController@callback'
    // ]);
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