<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::delete('videos/{video}', 	'\NoiThatZip\Video\Http\Controllers\VideosController@destroy');
});