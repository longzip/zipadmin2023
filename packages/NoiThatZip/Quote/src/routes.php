<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('quotes', 	'\NoiThatZip\Quote\Http\Controllers\QuotesController');
	Route::resource('quotesDA', 	'\NoiThatZip\Quote\Http\Controllers\QuotesdaController');
});