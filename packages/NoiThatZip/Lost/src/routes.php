<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('losts', 	'\NoiThatZip\Lost\Http\Controllers\LostsController');
});