<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('product-lines', 	'\NoiThatZip\ProductLine\Http\Controllers\ProductLinesController');
});