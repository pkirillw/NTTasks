<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/board/{id?}', 'BoardController@Board');
Route::get('/oldBoard/{id?}', 'BoardController@oldBoard');
Route::get('/expired/{id?}', 'BoardController@expired');
Route::get('/week/{id?}', 'BoardController@week');
Route::get('/today/{id?}', 'BoardController@today');
Route::get('/month/{id?}', 'BoardController@month');
Route::get('/search/{id?}/{text?}', 'BoardController@search');
Route::get('/ends/{id?}', 'BoardController@ends');
Route::get('/board2/{id?}', 'BoardController@Board2');
Route::get('/openBoard/{id?}', 'BoardController@OpenBoard');
Route::get('/board/getTasks/{id?}', 'BoardController@GetTask');

Route::get('/tasks/endTask/{taskId?}', 'TasksController@EndTask');
Route::post('/tasks/changePipeline', 'TasksController@ChangePipeline');
Route::post('/tasks/changeText', 'TasksController@ChangeText');
Route::post('/tasks/changeTimer', 'TasksController@ChangeTimer');
Route::post('/tasks/changeStatus', 'TasksController@ChangeStatus');
Route::post('/tasks/add', 'TasksController@Add');
Route::post('/tasks/edit', 'TasksController@Edit');

Route::post('/tasks/addAPI', 'TasksController@addTaskAPI');
Route::post('/tasks/editAPI', 'TasksController@editTaskAPI');
Route::get('/tasks/getTaskAPI/{id?}', 'TasksController@getTaskAPI');

Route::get('/tasks/getLeadTask/{leadId?}', 'TasksController@GetLeadTask');

Route::get('/import/users', 'ImportController@LoadUsers');

Route::get('/getLeads/{text?}', 'TasksController@GetLeads');
Route::get('/getLeadInfo/{amoId?}', 'TasksController@getLeadInfo');
Route::get('/getCounterTasks/{amoId?}', 'BoardController@getCounterTasks');