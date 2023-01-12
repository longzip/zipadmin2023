<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('costcenters', 	'\NoiThatZip\Costcenter\Http\Controllers\CostcentersController');
});