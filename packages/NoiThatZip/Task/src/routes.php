<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('tasks', 	'\NoiThatZip\Task\Http\Controllers\TasksController');
	Route::post('tasks/{task}/completed', '\NoiThatZip\Task\Http\Controllers\TasksController@completed');
	Route::put('tasks/{task}/comment', '\NoiThatZip\Task\Http\Controllers\TasksController@commentStore');
	Route::get('tasks/{task}/comments', '\NoiThatZip\Task\Http\Controllers\TasksController@comments');
});