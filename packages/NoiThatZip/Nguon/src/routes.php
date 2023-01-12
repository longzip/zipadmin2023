<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('nguons', 	'\NoiThatZip\Nguon\Http\Controllers\NguonsController');
});