<?php
Route::group(['prefix' => 'api', 'middleware' => 'api'], function(){
	Route::resource('activity-types', 	'NoiThatZip\ActivityType\Http\Controllers\ActivityTypesController');
	Route::get('activities-list', 'NoiThatZip\ActivityType\Http\Controllers\ActivityTypesController@activitiesList');
});