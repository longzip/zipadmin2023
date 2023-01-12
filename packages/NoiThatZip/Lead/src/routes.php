<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::resource('leads', 	'\NoiThatZip\Lead\Http\Controllers\LeadsController');
	Route::get('leads-count-by-date', 	'\NoiThatZip\Lead\Http\Controllers\LeadsController@countByDate');
    Route::get('leads-count-by-costcenter',   '\NoiThatZip\Lead\Http\Controllers\LeadsController@countByCoscenter');
	Route::put('leads/activities/{contact}', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storeactivity');
    Route::get('leads/activities/{contact}', '\NoiThatZip\Lead\Http\Controllers\LeadsController@activities');
    Route::put('leads/tasks/{lead}', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storetask');
    Route::get('leads/tasks/{lead}', '\NoiThatZip\Lead\Http\Controllers\LeadsController@tasks');
    Route::put('leads/comments/{lead}', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storecomment');
    Route::get('leads/comments/{lead}', '\NoiThatZip\Lead\Http\Controllers\LeadsController@comments');
    Route::put('leads/tasks/{lead}', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storetask');
    Route::get('leads/tasks/{lead}', '\NoiThatZip\Lead\Http\Controllers\LeadsController@tasks');
    Route::post('leads/{Lead}/attachmens', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storeattachmens');
    Route::get('leads-count-by-sale', '\NoiThatZip\Lead\Http\Controllers\LeadsController@countBySale');
    Route::get('leads-targets', '\NoiThatZip\Lead\Http\Controllers\LeadsController@targets');
    Route::put('leads/{lead}/kh15s', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storeKh15');
    Route::put('leads/{lead}/losts', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storeLost');
    Route::put('leads/{lead}/videos', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storeVideo');
    Route::post('leads/{lead}/image', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storeImage');
    Route::put('leads/{lead}/quotes', '\NoiThatZip\Lead\Http\Controllers\LeadsController@storeQuote');
    Route::get('leads-costcenters-targets', '\NoiThatZip\Lead\Http\Controllers\LeadsController@costcentersTarget');
});