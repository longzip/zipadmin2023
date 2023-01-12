<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('news', '\NoiThatZip\News\Http\Controllers\NewsController');

	Route::get('/thongbao' ,"\NoiThatZip\News\Http\Controllers\NewsController@thongbao");
	Route::get('/khenthuong' ,"\NoiThatZip\News\Http\Controllers\NewsController@khenthuong");
	Route::get('/phat' ,"\NoiThatZip\News\Http\Controllers\NewsController@phat");

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
