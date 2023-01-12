<?php
Route::group(['prefix' => 'api', 'middleware' => 'auth:api'], function(){
	Route::delete('attachments/{attachment}', 	'\NoiThatZip\Attachment\Http\Controllers\AttachmentController@destroy');
});