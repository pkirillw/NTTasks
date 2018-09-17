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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/
Route::group([
    'prefix' => 'v1',
    'namespace' => 'Api\\V1'
], function () {
    Route::group([
        'prefix' => 'tasks',
    ], function () {
        Route::get('get/{taskId?}', 'TaskController@get');
        Route::get('getUserTasks/{userId?}/{mode?}', 'TaskController@getUserTasks');
        Route::get('getLeadTasks/{leadId?}', 'TaskController@getLeadTasks');
        Route::get('getCountExpiredUserTasks/{userId?}', 'TaskController@getCountExpiredTasks');
        Route::get('delete/{taskId?}', 'TaskController@delete');

        Route::post('updatePipeline', 'TaskController@updatePipeline');
        Route::post('add', 'TaskController@add');
        Route::post('update', 'TaskController@update');
        Route::post('miniEdit', 'TaskController@miniEdit');
    });

    Route::group([
        'prefix' => 'amocrm',
    ], function () {
        Route::get('getLeadInfo/{amoId?}', 'AmoCRMController@getLeadInfo');
        Route::get('getLeads/{text?}', 'AmoCRMController@getLeads');
    });

    Route::group([
        'prefix' => 'comments',
    ], function () {
        Route::get('getTaskComments/{taskId?}', 'CommentsController@getTaskComments');

        Route::post('addComment', 'CommentsController@add');
    });

    Route::group([
        'prefix' => 'notifications'
    ], function (){
       Route::get('getUserNotifications/{userId?}', 'NotificationController@getUserNotifications');
       Route::get('getTaskNotifications/{taskId?}', 'NotificationController@getTaskNotifications');
       Route::get('getNotification/{notificationId?}', 'NotificationController@getNotification');
       Route::get('readNotification/{notificationId?}', 'NotificationController@readNotification');
    });
});