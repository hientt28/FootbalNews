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
        
        Route::group(['namespace' => "Admin"], function () {
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
        });
    });
    
    Route::group(['prefix' => 'users', 'namespace' => 'User'], function () {
        Route::get('/', [
            'as' => 'users.welcome',
            'uses' => 'UserController@welcome'
        ]);
        Route::resource('news', 'NewController');
        Route::resource('matches', 'MatchController', ['only' => ['index', 'show']]);
    });
});
