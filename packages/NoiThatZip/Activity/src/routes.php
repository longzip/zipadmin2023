<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('activities', 	'\NoiThatZip\Activity\Http\Controllers\ActivitiesController');
});