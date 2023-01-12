<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('salesareas', 	'\NoiThatZip\SalesArea\Http\Controllers\SalesAreasController');
	Route::get('salesareas-list', 	'\NoiThatZip\SalesArea\Http\Controllers\SalesAreasController@salesAreasList');

	
	Route::resource('news', '\NoiThatZip\News\Http\Controllers\NewsController');
	Route::get('/tintonghop' ,"\NoiThatZip\News\Http\Controllers\NewsController@tintonghop");
	Route::get('/khuyenmai' ,"\NoiThatZip\News\Http\Controllers\NewsController@khuyenmai");
	Route::get('/thanhtich' ,"\NoiThatZip\News\Http\Controllers\NewsController@thanhtich");
	Route::get('/duan' ,"\NoiThatZip\News\Http\Controllers\NewsController@duan");
	Route::get('/danhgia' ,"\NoiThatZip\News\Http\Controllers\NewsController@danhgia");
	Route::get('/team' ,"\NoiThatZip\News\Http\Controllers\NewsController@team");

	Route::get('/thong-bao/{id}' ,"\NoiThatZip\News\Http\Controllers\NewsController@getthongbao");

	Route::post('/updateFile' ,"\NoiThatZip\News\Http\Controllers\NewsController@add");
	Route::post('/updateImage' ,"\NoiThatZip\News\Http\Controllers\NewsController@addImage");
});