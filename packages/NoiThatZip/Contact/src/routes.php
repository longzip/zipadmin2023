<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('contacts', 	'\NoiThatZip\Contact\Http\Controllers\ContactsController');
	Route::get('contacts/{contact}/edit', '\NoiThatZip\Contact\Http\Controllers\ContactsController@edit');
	Route::put('contacts/{contact}/quotes', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storeQuote');
	Route::put('contacts/{contact}/comment', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storecomment');
	Route::put('contacts/{contact}/activities', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storeactivity');
	Route::put('contacts/{contact}/tasks', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storetask');
	Route::put('contacts/{contact}/videos', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storeVideo');
	Route::put('contacts/{contact}/kh15s', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storeKh15');
	Route::put('contacts/{contact}/losts', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storeLost');
	Route::post('contacts/{contact}/attachmens', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storeattachmens');
	Route::post('contacts/{contact}/image', '\NoiThatZip\Contact\Http\Controllers\ContactsController@storeImage');
	Route::get('contacts/{contact}/publish', '\NoiThatZip\Contact\Http\Controllers\ContactsController@publish');
	Route::get('contacts/{contact}/unpublish', '\NoiThatZip\Contact\Http\Controllers\ContactsController@unPublish');
	Route::get('contacts-count-by-date', '\NoiThatZip\Contact\Http\Controllers\ContactsController@countByDate');
	Route::get('contacts-count-by-costcenter', '\NoiThatZip\Contact\Http\Controllers\ContactsController@countByCostcenter');
	Route::get('contacts-count-by-sale', '\NoiThatZip\Contact\Http\Controllers\ContactsController@countBySale');
	Route::get('contacts-targets', '\NoiThatZip\Contact\Http\Controllers\ContactsController@targets');
	Route::get('contacts-costcenters-targets', '\NoiThatZip\Contact\Http\Controllers\ContactsController@costcentersTarget');
	Route::get('contacts-zip-targets', '\NoiThatZip\Contact\Http\Controllers\ContactsController@zipTarget');
});