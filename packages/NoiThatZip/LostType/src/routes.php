<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('lost-types', 	'\NoiThatZip\LostType\Http\Controllers\LostTypesController');
});