<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/',
    [
        'as' => 'zip',
        'middleware' => ['auth'],
        'uses' => 'DashboardController@getIndex' ]
    );

// Auth::routes();
Auth::routes(['register' => false]);
Route::post('/reset-pass', 'DashboardController@reset');
Route::get('home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['permission:import']], function () {
    Route::post('orders/import', 'OrdersController@import');
    Route::post('order-line/import', 'OrderLineController@import');
    Route::post('customers/import', 'CustomersController@import');
    Route::post('products/import', 'ProductsController@import');
    Route::post('products/importkh', 'ProductsController@importkh');
    Route::post('cashiers/import', 'CashiersController@import');
    Route::post('resources/import', 'ResourcesController@import');
});
Route::group(['middleware' => ['role:imports']], function () {
    Route::get('/import', function () {
        return view('welcome');
    });
});
Route::get('/push-1office', 'API\AppController@office');

Route::put('quotes/keep/{id}', 'API\QuotesController@flashQuote');
Route::get('quotes/keep', 'API\QuotesController@flushQuote');
Route::get('orders/xml', 'OrdersController@export');

Route::get('orders/excel', 'OrdersController@exportcsv');
Route::get('orders/export_excel', 'OrdersController@excel')->name('export_excel.excel');
Route::get('orders1/export_excel', 'OrdersController@fillOrder')->name('export_excel.fillOrder');

Route::get('orders2/excel', 'OrdersController@exportcsv2');
Route::get('orders2/export_excel2', 'OrdersController@excelDT');
Route::get('orders2/export_excel', 'OrdersController@fillOrderDoanhThu')->name('export_excel.fillOrderDoanhThu');

Route::get('orders/pdf/{id}', 'OrdersController@exportPdf');
Route::get('orders/print/{id}', 'OrdersController@print');
Route::get('quotes/{id}/print', 'QuotesController@print');
Route::get('customers/xml', 'CustomersController@export');
Route::get('gl-entries/xml', 'ExportsController@export');
Route::get('cart','CartController@index')->name('cart.index');
Route::post('cart','CartController@add')->name('cart.add');
Route::post('cart/conditions','CartController@addCondition')->name('cart.addCondition');
Route::delete('cart/conditions','CartController@clearCartConditions')->name('cart.clearCartConditions');
Route::get('cart/details','CartController@details')->name('cart.details');
Route::post('cart/checkout','CartController@checkout')->name('cart.checkout');
Route::post('cart/taobaogia','CartController@storeQuote');
Route::post('cart/load','CartController@loadcart')->name('cart.load');
Route::get('cart/customer','CartController@getcustomer')->name('cart.customer');
Route::get('cart/order','CartController@getorder')->name('cart.order');
Route::get('cart/quote/{id}','CartController@loadFromQuote')->name('cart.quote');
Route::get('cart/clear','CartController@clear')->name('cart.clear');
Route::get('cart/clear','CartController@clear')->name('cart.clear');
Route::get('cart/loadorder/{id}','CartController@loadFromOrder')->name('cart.loadorder');
Route::delete('cart/{id}','CartController@delete')->name('cart.delete');
Route::post('setcontact', 'ContactsController@setContact');
Route::get('getcontact', 'ContactsController@getContact');
Route::get('quotebycontact', 'ContactsController@getquote');
Route::get('clearcontact', 'ContactsController@clearContact');
Route::get('quoteitems/{id}', 'QuotesController@quoteitems');
Route::post('contacts/luutailieu', 'FilesController@luutailieu');
Route::get('/attachmens/{id}', 'DownloadMediaController@show');
//Lead
Route::put('/leads/session/{lead}', 'LeadsController@setLead');
Route::get('/leads/session', 'LeadsController@getLead');
//Contact
Route::put('/contacts/session/{contact}', 'ContactsController@setContact');
Route::get('/contacts/session', 'ContactsController@getContact');
Route::get('/exports/contacts', 'ContactsController@export');
Route::get('/exports/lead', 'ContactsController@exportlead');
//project
Route::get('/exports/project', 'ContactsController@exportProject');

//Quote
Route::put('/quotes/session/{quote}', 'QuotesController@quoteStart');
Route::get('/quotes/session', 'QuotesController@quoteDestroy');
//Category
Route::get('/categories/session/{category}', 'CategoriesController@setCategory');
Route::get('/category/session', 'CategoriesController@getCategory');
//Nhansu
Route::get('/exportpdf/{id}', 'ExportBienBanController@index');
Route::get('/exportpdfnp/{id}', 'ExportBienBanController@indexs');
Route::get('/exportpdfnptp/{id}', 'ExportBienBanController@NSDuyet');
Route::get('/downloadPdf/{file}', 'ExportBienBanController@downloadPdf');

Route::get('/previewpdf', function() {
    return view('PreviewBienBan');
});

Route::get('/export' ,"ExportController@giao");
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

Route::get('/movedata' ,"CategoriesController@movedata");

Route::group(['middleware' => 'auth'], function () {

    Route::resource('docs', 'DocsController', [

        'parameters' => ['doc' => 'doc_id']

    ]);

    Route::resource('customers', 'CustomersController', [

        'parameters' => ['customer' => 'customers_id']

    ]);

    Route::resource('products', 'ProductsController', [

        'parameters' => ['product' => 'products_id']

    ]);

    Route::resource('orders', 'OrdersController', [

        'parameters' => ['order' => 'orders_id']

    ]);

    Route::resource('order-line', 'OrderLineController', [

        'parameters' => ['orderline' => 'orderline_id']

    ]);

    Route::resource('resources', 'ResourcesController', [

        'parameters' => ['resource' => 'resource_id']

    ]);

    Route::get('khachhang', 'ProductsController@getim');
});

Route::get('{path}',"HomeController@index");
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('permission:cache-reset');
    return "Cache is cleared";
});
// Route::get('export', 'ExController@export')->name('exsport');
// Route::get('view', 'ExController@index')->name('views');
// Route::get('view',function(){
//     return view('/view',compact('orders.xlsx'));
// });
// Route::get('/previewpdf', function() {
//     $dataBinding = [];
//     $dataBinding['user_name'] = 'a';  
//     $dataBinding['role_name'] = 'fd';  
//     $dataBinding['phone'] =  'hf'; 
//     $dataBinding['cv_ban_giao'] =  'hg';
//     $dataBinding['id'] = 'hfdhf';
//     $dataBinding['so_ngay_nghi'] = 'intdate';
//     $dataBinding['ly_do'] = 'jhk';
//     $dataBinding['ngay_gui_don'] = 'hjgh';
//     $dataBinding['end_date'] = 'datas';
//     $dataBinding['loai_nghi'] = 0;
//     return view('PreviewNghiPhep',compact('dataBinding'));
// });
// Route::get('/export',function(){
// return view('test');
// });