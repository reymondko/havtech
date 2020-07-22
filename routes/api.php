<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* 
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/**
 * Authenticated/Authentication API Requests
 */
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'Auth\ApiAuthController@login');
    Route::post('login-first', 'Auth\ApiAuthController@loginFirstTime');
    Route::post('password/reset', 'Auth\ApiAuthController@sendResetEmail');
    Route::get('download/{filename}', 'ApiController@downloadFile');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'Auth\ApiAuthController@logout');
        Route::get('user', 'ApiController@user');
        Route::get('events/{type}', 'ApiController@events');
        Route::get('events', 'ApiController@events');
        Route::get('event/{id}', 'ApiController@event');
        Route::get('industries', 'ApiController@industries');
        Route::post('event/register/{id}', 'ApiController@registerEvent');
        Route::post('user/update', 'ApiController@updateUser');
        
        Route::get('user/notifications', 'ApiController@getUserNotifications');
        Route::get('user/notifications/mark/{user_notifiation_id}', 'ApiController@markUserNotificationsAsRead');
        Route::post('user/notifications/onesignal-player-id', 'ApiController@updateOrCreateOnesgnalPlayerId');

        Route::post('event/photos/upload', 'ApiController@eventPhotosUpload');

        Route::post('/event/custom-schedule/save', 'ApiController@saveCustomEventSchedule');
        Route::get('/event/custom-schedule/delete/{id}', 'ApiController@deleteCustomEventSchedule');
    });
});