<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('orders', 	'\NoiThatZip\Order\Http\Controllers\OrdersController');
});