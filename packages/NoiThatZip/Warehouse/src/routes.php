<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('warehouses', 	'\NoiThatZip\Warehouse\Http\Controllers\WarehousesController');
	Route::get('cities-list', 	'\NoiThatZip\Warehouse\Http\Controllers\WarehousesController@citiesList');
	Route::get('cities', 	'\NoiThatZip\Warehouse\Http\Controllers\WarehousesController@citiesAll');
});