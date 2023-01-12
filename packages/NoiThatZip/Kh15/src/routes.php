<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('kh15s', 	'\NoiThatZip\Kh15\Http\Controllers\Kh15sController');
});