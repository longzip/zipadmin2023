<?php
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/updateaaa','API\AppController@updateaaa');
Route::get('/updateorderoff','API\AppController@updateorderoff');
Route::post('/loginapp','API\AppController@login');
Route::post('/dieukien','API\AppController@dieukien');
Route::post('/createkh15','API\AppController@createkh15');
Route::post('/createkhtn','API\AppController@createkhtn');
Route::post('/searchkh15','API\AppController@searchkh15');
Route::post('/searchdh','API\AppController@searchdh');
Route::post('/searchkhtn','API\AppController@searchkhtn');
Route::post('/addImage','API\AppController@addImage');
Route::get('/datakh15','API\AppController@datakh15');
Route::get('/diemroi','API\AppController@diemroi');
Route::get('/datasp','API\AppController@datasp');
Route::post('/taskkh15','API\AppController@taskkh15');
Route::get('/datasr','API\AppController@datasr');
Route::get('/datakv','API\AppController@datakv');
Route::get('/getUsers','API\AppController@getUsers');
Route::get('/showkh15','API\AppController@showkh15');
Route::get('/showkhtn','API\AppController@showkhtn');
Route::post('/editkh15','API\AppController@editkh15');
Route::post('/editkhtn','API\AppController@editkhtn');
Route::post('/ddkh15','API\AppController@ddkh15');
Route::post('/llkh15','API\AppController@llkh15');
Route::get('/deleteddkh15','API\AppController@deleteddkh15');
Route::get('/deletellkh15','API\AppController@deletellkh15');
Route::get('/datakhtn','API\AppController@datakhtn');
Route::get('/infobaogia','API\AppController@infobaogia');
Route::post('/addcommentkh15','API\AppController@addcommentkh15');
Route::get('/getdataproducts', 'API\AppController@getAll');
Route::get('/datadh', 'API\AppController@datadh');
Route::post('/taodonhang', 'API\AppController@taodonhang');
Route::get('/test',function ()
{
    echo '<a href="https://admin.noithatzip.com/api/test1">OK</a>';
});
Route::get('/test1','API\ZaloController@store');
Route::get('/test2','API\ZaloController@index');
Route::group(['middleware' => 'auth:api'], function(){
    Route::apiResources([
        'users' => 'API\UserController',
        'products' =>'API\ProductsController',
        'customers' =>'API\CustomersController',
        'orders' =>'API\OrdersController',
        'sales-targets' =>'API\SalesTargetsController',
        'costcenters-targets' =>'API\CostcentersTargetsController',
        // 'leads' =>'API\LeadsController',
        'order-lines' =>'API\OrderLinesController',
        'resources' => 'API\ResourcesController',
        // 'quotes' => 'API\QuotesController',
        'taskcontact' => 'API\TaskContactController',
        'costcenter' => 'API\CostcentersController',
        'activity-types1' => 'API\ActivityTypesController',
        'categories' => 'API\CategoriesController',
        'roles' => 'API\RolesController',
        'permission' => 'API\PermissionsController',
        'projects' =>'API\ProjectController',
        'target' => 'API\TargetShowController',
        'decision' => 'API\DecisionController',
        'bienban'   => 'API\BienBanController',
        'chetai'   => 'API\CheTaiController',
        'quytrinh'   => 'API\QuyTrinhController',
        'vipham' => 'API\ViPhamController',
        'nghiphep' => 'API\NghiPhepController',
        'nghipheps' => 'API\NghiPhepListController',
        'loaichiphi' => 'API\LoaiChiPhiController',
        'chiphi' => 'API\ChiPhiController',
        'chiphidetail'=> 'API\CPitemController',
        // 'detailquydinh'=>'API\NghiPhepListController',
        'detailnghiphep'=>'API\NghiPhepListController',
        'detailvipham'=>'API\NghiPhepListController',
        'detailTarget' => 'API\DetailTargetController',
        'thu' => 'API\ThuController',
        'data' => 'API\DataController',
        'khachdata' => 'API\KhachDataController',
        'online' => 'API\OnlineController',
        'onlinetarget' => 'API\OnlineTargetController',
        'admintarget' => 'API\AdminTargetController',
        'marketing' => 'API\MarketingController',
        'khachshowroom' => 'API\ShowroomController',
        'daily' => 'API\DaiLyController',
        'duchi' => 'API\DuChiController',
        'tuyendung' => 'API\TuyenDungController',
        'cv' => 'API\CVController',
        'thicong' => 'API\ThiCongController',
        'csvc' => 'API\CSVCController',
    ]);
    // Route::get()
    Route::get('sttcv','API\CVController@sttcv');
    Route::get('baocaocv','API\CVController@baocaocv');

    Route::get('phanquyen','API\TuyenDungController@phanquyen');
    Route::get('alltd','API\TuyenDungController@tuyendung');
    Route::get('adddx','API\TuyenDungController@adddx');

    Route::get('nhansuduyets/{id}','API\NghiPhepController@nhansuduyet');
    Route::get('khongduyets/{id}', 'API\NghiPhepController@khongduyet');
    Route::get('postduyet/{id}', 'API\NghiPhepController@postduyets');
    Route::get('xacnhanbg/{id}', 'API\NghiPhepController@xacnhanbg');
    Route::get('duyetgdnp/{id}', 'API\NghiPhepController@duyetgd');

    Route::get('comments-nghiphep','API\NghiPhepController@comments');
    Route::put('nghiphepcomment/{id}','API\NghiPhepController@comment');
    Route::get('phe-duyet','API\NghiPhepController@pheduyet');

    Route::get('guidexuat/{id}', 'API\ChiPhiController@guidexuat');
    Route::get('duyetkt/{id}', 'API\ChiPhiController@duyetkt');
    Route::get('duyetgd/{id}', 'API\ChiPhiController@duyetgd');
    Route::get('khongduyetcp/{id}', 'API\ChiPhiController@khongduyet');
    Route::get('comments-chiphi','API\ChiPhiController@comments');
    Route::put('chiphicomment/{id}','API\ChiPhiController@comment');

    Route::get('nhat-ky', 'API\ActivityLogsController@index');
    Route::delete('/medias/{media}', 'API\MediasController@destroy');

    Route::get('/nghipheps','API\NghiPhepListController@home');
    Route::get('/messages', 'API\MessagesController@index');
    Route::put('/customers/{customer}/order', 'API\CustomersController@storeOrder');
    Route::get('/users-list', 'API\UserController@usersList');
    Route::get('/products-list', 'API\ProductsController@productsList');
    Route::get('/products-list-tab', 'API\ProductsController@list');
    Route::get('/categories-list', 'API\CategoriesController@categoriesList');
    Route::get('/costcenters-list', 'API\CostcentersController@costcentersList');
    Route::get('/costcenters-merge-list', 'API\CostcentersController@costcentersmergeList');
    Route::get('/checkblock', 'API\CostcentersTargetsController@openclose');
    Route::post('/delete-role', 'API\CostcentersTargetsController@deleterole');
    Route::get('quotes/{quote}/product-lines', 'API\QuotesController@showProductLines');
    Route::get('costcenter/{costcenter}/contacts', 'API\CostcentersController@contacts');
    Route::get('getallcustomers', 'API\CustomersController@index2');
    Route::get('find-user-by-costcenter', 'API\UserController@findUserbyCostcenter');
    Route::get('findCustomer', 'API\CustomersController@findByPhone');
    Route::get('getallproducts', 'API\ProductsController@getAll');
    Route::get('getallproduct', 'API\ProductsController@getallproducts');
    Route::get('profile', 'API\ResourcesController@profile');
    Route::get('findUser', 'API\UserController@search');
    Route::get('user/contacts/{user}', 'API\UserController@searchContacts');
    Route::get('user/leads/{user}', 'API\UserController@searchLeads');
    Route::get('costcenters/contacts/{costcenter}', 'API\CostcentersController@searchContacts');
    Route::get('costcenters/leads/{costcenter}', 'API\CostcentersController@searchLeads');
    Route::get('getroles/{id}', 'API\UserController@getRoles');
    Route::get('getallroles', 'API\UserController@getAllRoles');
    Route::get('UserSR', 'API\UserController@UserSR');
    Route::post('moveContact/{id}/{iduser}', 'API\UserController@updateContact');
    Route::get('getallobjectroles', 'API\UserController@getAllObjectRoles');
    Route::put('profile', 'API\ResourcesController@updateProfile');
    Route::get('orders/details/{id}', 'API\OrdersController@details');
    Route::get('updateDate', 'API\OrdersController@update');
    Route::get('updateTrangThai', 'API\OrdersController@updateTrangThai');
    Route::get('findorder', 'API\OrdersController@findBySO');
    Route::post('searchorderat', 'API\OrdersController@searchbydate1');
    Route::post('searchorderat2', 'API\OrdersController@searchbydate2');
    Route::get('orders/getsum', 'API\OrdersController@getSum');
    Route::get('orders/status/{id}', 'API\OrdersController@status');
    Route::get('updateorder', 'API\OrdersController@updateorder');
    Route::get('bao-cao-lich-giao', 'API\ContactsController@baocaolichgiao');
    Route::get('searchorder', 'API\OrdersController@searchorder');
    Route::get('searchordernew', 'API\OrdersController@searchordernew');

    Route::get('getallcostcenter', 'API\CostcentersController@all');
//Search Contact at ContactINdex.vue
    Route::put('searchcontacts', 'API\ContactsController@searchContactbyShowroom');
    Route::get('bao-cao-khtn', 'API\ContactsController@baocaokhtn');
    // Route::post('tasks/completed/{task}', 'API\TasksController@completed');
    Route::get('block-target','API\CostcentersTargetsController@block');
    Route::get('open-target','API\CostcentersTargetsController@open');
    Route::get('leads/{lead}/user', 'API\ContactsController@user');
    Route::get('sumamount', 'API\ContactsController@sum');
    Route::get('updatestatuskhtn', 'API\ContactsController@updatestatus');
    Route::get('updategiaidoan', 'API\ContactsController@updategiaidoan');
    Route::get('showstatuskhtn', 'API\ContactsController@showstatus');
    Route::get('showinfocustum', 'API\ContactsController@showinfocustum');
    Route::get('showinfonv', 'API\ContactsController@showinfonv');
    Route::get('diem-roi', 'API\ContactsController@calendar');
    Route::get('loaikh', 'API\ContactsController@loaikh');
    Route::get('addkh', 'API\ContactsController@addkh');
    Route::get('loadloai', 'API\ContactsController@loadloai');
//Costcenter API
    Route::get('costcenters/all', 'API\CostcentersController@getSelect');
    Route::get('getallcategories', 'API\CategoriesController@index1');
    Route::get('picklists/activity-types', 'API\PicklistsController@activityTypes');
    Route::get('picklists/users', 'API\PicklistsController@users');
    Route::get('picklists/asm', 'API\PicklistsController@asm');
    Route::post('picklists', 'API\PicklistsController@store');
    Route::get('picklists/login', 'API\PicklistsController@login');
    Route::post('picklists/p', 'API\PicklistsController@p');
    Route::get('picklists/calendarp', 'API\PicklistsController@calendarp');
    Route::get('picklists/contact-picklists', 'API\PicklistsController@contactPicklists');
    Route::get('picklists/resource-picklists', 'API\PicklistsController@resourcePicklists');
    Route::get('picklists/costcenter-picklists', 'API\PicklistsController@costcenterPicklists');
    Route::get('picklists/costcenter-picklistss', 'API\PicklistsController@costcenterPicklistss');
    Route::get('picklists/dashboard-picklists', 'API\PicklistsController@dashboardList');
    Route::get('picklists/areasPicklists', 'API\PicklistsController@areasPicklists');
//Lead
    // Route::put('leads/activities/{contact}', 'API\LeadsController@storeactivity');
    // Route::get('leads/activities/{contact}', 'API\LeadsController@activities');
    // Route::put('leads/tasks/{lead}', 'API\LeadsController@storetask');
    // Route::get('leads/tasks/{lead}', 'API\LeadsController@tasks');
    // Route::put('leads/comments/{lead}', 'API\LeadsController@storecomment');
    // Route::get('leads/comments/{lead}', 'API\LeadsController@comments');
    // Route::put('leads/tasks/{lead}', 'API\LeadsController@storetask');
    // Route::get('leads/tasks/{lead}', 'API\LeadsController@tasks');
    Route::post('adddk', 'API\LeadsController@adddk');
    Route::get('updateProgramLead', 'API\LeadsController@updateProgramLead');
    Route::get('blackfriday', 'API\LeadsController@blackfriday');
    
    Route::get('find-dinhmuc', 'API\DinhMucController@dinhmuc');
    Route::get('add-dinhmuc', 'API\DinhMucController@store');
    Route::get('showproductsorder', 'API\DinhMucController@showproductsorder');
    Route::get('showdinhmuc', 'API\DinhMucController@index');
    Route::get('delete-sx', 'API\DinhMucController@destroy');
    Route::get('show-sx', 'API\DinhMucController@show');
    Route::get('show-hs', 'API\DinhMucController@showhs');

//Categories
    // Route::get('exsport', 'API\ExController@export')->name('exsport');
    // Route::get('view', 'ExController@export')->name('views');

    Route::get('categories/contacts/{category}', 'API\CategoriesController@contactsList');
    Route::get('categories/leads/{category}', 'API\CategoriesController@leadsList');
    Route::get('/categories/session/{category}', 'API\CategoriesController@setCategory');
    Route::get('users-costcenters', 'API\TargetShowController@costcenters');
    Route::get('users-target', 'API\TargetShowController@targetSRcontact');
    Route::get('users-target-sr', 'API\TargetShowController@targetContacts');
    Route::get('users-target-leads', 'API\TargetShowController@targetLeads');
    Route::get('users-target-order', 'API\TargetShowController@targetOrder');
    Route::get('users-target-amount', 'API\TargetShowController@targetAmount');
    Route::get('showroom-target-amount-new', 'API\TargetShowController@targetSRAmountnew');
    Route::get('inputtarget', 'API\TargetShowController@inputtarget');
    Route::get('showroom-target-lead', 'API\TargetShowController@targetSRlead');
    Route::get('showroom-target-order', 'API\TargetShowController@targetSROrder');
    Route::get('showroom-target-amount', 'API\TargetShowController@targetSRAmount');
    Route::get('detailKH15', 'API\DetailTargetController@targetKH15');
    Route::get('detailDonHang', 'API\DetailTargetController@targetDonHang');
    Route::get('detailDoanhSo', 'API\DetailTargetController@targetDoanhSo');
    Route::get('amount-year', 'API\TargetShowController@yearAmount');
    Route::post('showdataProduct', 'API\TargetShowController@showtotalproduct');
    Route::get('users-group-costcenters', 'API\UserController@groupCostcenters');
    Route::get('users-group-costcenters-new', 'API\UserController@groupCostcentersNew');
    Route::get('users/{user}/friends', 'API\UserController@findFriends');
    Route::get('orders-count-by-date', 'API\OrdersController@countByDate');
    Route::get('infosum', 'API\OrdersController@sum');
    Route::get('orders-amount-by-date', 'API\OrdersController@amountByDate');
    Route::resource('news', '\NoiThatZip\News\Http\Controllers\NewsController');
    Route::post('/updateFile' ,"\NoiThatZip\News\Http\Controllers\NewsController@add");
    //Project
    Route::resource('project','API\ProjectController');
    Route::put('project/{project}/comment', 'API\ProjectController@storecomment');
    Route::put('project/{project}/tasks', 'API\ProjectController@storetask');
    Route::put('project/{project}/losts', 'API\ProjectController@storeLost');
    Route::put('project/{project}/kh15s', 'API\ProjectController@storeKh15');
    Route::put('project/{project}/videos', 'API\ProjectController@storeVideo');
    Route::post('project/{project}/attachmens', 'API\ProjectController@storeattachmens');
    Route::post('project/{project}/image', 'API\ProjectController@storeImage');
    Route::put('project/{project}/quotes', 'API\ProjectController@storeQuote');
    Route::get('read/{id}' ,"API\ProjectController@read");
    Route::get('listOrders', 'API\OrdersController@listOrders');
    Route::get('listblackfriday', 'API\OrdersController@listblackfriday');
    Route::get('listblackfridaytype', 'API\OrdersController@listblackfridaytype');
    Route::get('checkAdmin', 'API\OrdersController@checkAdmin');
    Route::get('updateDelivery', 'API\OrdersController@updateDelivery');
    Route::get('updateall', 'API\OrdersController@updateall');
    Route::get('find-products-order', 'API\OrdersController@findProducts');
    Route::get('show-products-order', 'API\OrdersController@showProducts');
    Route::get('orders-count-by-sale', 'API\OrdersController@countBySale');
    Route::get('orders-amount-by-sale', 'API\OrdersController@amountBySale');
    Route::get('orders-count-by-costcenter', 'API\OrdersController@countByCostcenter');
    Route::get('orders-amount-by-costcenter', 'API\OrdersController@amountByCostcenter');
    Route::get('orders-targets', 'API\OrdersController@amountTarget');
    Route::get('orders-targets-2', 'API\OrdersController@amountTarget2');
    Route::get('orders-costcenters-targets', 'API\OrdersController@amountCostcentersTarget');
    Route::get('orders-costcenters-targets-2', 'API\OrdersController@amountCostcentersTarget2');
    Route::get('quydinhchung', 'API\DecisionController@loadChung');
    Route::get('quydinhrieng', 'API\DecisionController@loadRieng');
    Route::get('list-quy-dinh', 'API\DecisionController@QuyDinhList');
    Route::get('getallgroup', 'API\DecisionController@getGroup');
    Route::get('getallchettai', 'API\DecisionController@getCheTai');
    Route::get('getallquytrinh', 'API\DecisionController@getQuyTrinh');
    Route::get('postSendMail/{id}', 'API\BienBanController@postSendMail');
    Route::get('duyetTin/{id}', 'API\BienBanController@duyetTin');
    Route::get('getmaquydinh', 'API\DecisionController@getmaquydinh');
    Route::get('getSapoQuyDinh', 'API\DecisionController@getSapoQuyDinh');
    Route::get('getAllUsers', 'API\UserController@getAllUsers');
    Route::get('getThiCongUsers', 'API\UserController@getThiCongUsers');
    Route::get('getQuyDinh', 'API\DecisionController@getQuyDinh');
    Route::get('getGroupRoles', 'API\DecisionController@getGroupRoles');
    Route::post('updateViPham', 'API\ViPhamController@update');
    Route::get('getViPhambyID/{id}', 'API\ViPhamController@getViPhambyID');
    Route::get('sendEmail/{id}', 'API\ViPhamController@sendMail');
    Route::get('downloadPdf/{file}', 'API\ViPhamController@downloadPdf');
    Route::get('duyet/{id}', 'API\ViPhamController@duyet');
    Route::get('khongduyet/{id}', 'API\ViPhamController@khongduyet');
    Route::get('getUserById/{id}', 'API\UserController@getUserById');
    Route::get('getAllLoaiChiPhi', 'API\LoaiChiPhiController@getAllLoaiChiPhi');
    Route::get('CphiController', 'API\ChiPhiController@edit');
    Route::post('CphiItem', 'API\ChiPhiController@addItem');
    Route::get('CphiItem', 'API\ChiPhiController@deleteItem');
    Route::post('updatedChiPhi', 'API\ChiPhiController@updateChiPhi');
    Route::get('deleteModalDetail', 'API\ChiPhiController@deleteModalDetail');
    Route::post('updatedChiPhiThuc', 'API\ChiPhiController@updateChiPhiThuc');
    Route::get('detailQuyDinh', 'API\NghiPhepListController@loadDetailQD');
    Route::get('detailViPham', 'API\NghiPhepListController@loadDetailVP');
    Route::get('detailNghiPhep', 'API\NghiPhepListController@loadDetailNP');
    Route::get('delThongBao', 'API\NghiPhepListController@delThongBao');

    Route::get('bao-cao-so-luong', 'API\OnlineController@soluong');
    Route::get('bao-cao-tien', 'API\OnlineController@tien');
    Route::get('total-online', 'API\OnlineController@total');
    Route::get('load-sp-cd', 'API\OnlineController@load');

    Route::get('/products-list-target', 'API\OnlineTargetController@productsList');
    Route::get('/online-target', 'API\OnlineTargetController@target');
    Route::get('/show-product-target', 'API\OnlineTargetController@allproduct');

    Route::get('/re-product', 'API\ReportProductController@index');
    Route::get('/loadmore-product', 'API\ReportProductController@loadone');
    Route::get('/loadmore-productdetail', 'API\ReportProductController@load');
    Route::get('/loadmore-key', 'API\ReportProductController@loadkey');
    Route::get('/loadmore-key-target', 'API\ReportProductController@loadkeytarget');

    Route::get('/showDuyetadmin', 'API\AdminTargetController@showduyet');
    Route::get('/updateDuyetadmin', 'API\AdminTargetController@updateduyet');

    Route::get('/shownew', 'API\DashboardController@show');
    Route::get('/shownewt', 'API\DashboardController@showt');
    Route::post('/chamcong', 'API\DashboardController@chamcong');
    Route::post('/chamcongout', 'API\DashboardController@chamcongout');
    Route::get('/chamcong', 'API\DashboardController@getchamcong');
    Route::get('/baocaochamcong', 'API\DashboardController@baocao');
    Route::get('/find-nhanvien', 'API\DashboardController@find');
    Route::get('/users-chamcong', 'API\DashboardController@fill');
    Route::get('/bophan-chamcong', 'API\DashboardController@bophan');
    Route::get('/loadgiaidoan', 'API\MarketingController@loadgiaidoan');
    Route::get('/loadgiaidoankhtn', 'API\MarketingController@loadgiaidoankhtn');
    Route::get('/sum-khach', 'API\MarketingController@sumkhach');

    Route::get('/ketoanbaocao', 'API\AdminTargetController@ketoan');
    Route::get('/load-w', 'API\AdminTargetController@loadmore');
    Route::get('/listt', 'API\AdminTargetController@listcalender');
    Route::get('/danhgiagiao', 'API\AdminTargetController@danhgiagiao');
    Route::post('/chuagiao', 'API\AdminTargetController@chuagiao');
    Route::get('/allproduct', 'API\CostcentersTargetsController@allproduct');
    Route::get('/addtargetproduct', 'API\CostcentersTargetsController@addtargetproduct');

    Route::get('/removelistsr', 'API\ShowroomController@removelistsr');
    Route::get('/updatelistsr', 'API\ShowroomController@updatelistsr');
    Route::get('/duyetlistsr', 'API\ShowroomController@duyetlistsr');
    Route::get('/sum-showroom-baogia', 'API\ShowroomController@sumbaogia');

    Route::get('/telepro', 'API\TeleproController@index');
    Route::get('/telepro-push', 'API\TeleproController@store');

    Route::get('/fil-tailieuphong', 'API\TaiLieuController@groupTaiLieuPhong');
    Route::get('/fil-tailieusanpham', 'API\TaiLieuController@groupTaiLieuSanPham');
    Route::get('/fil-tailieuloai', 'API\TaiLieuController@groupTaiLieuLoai');
    Route::post('/addTaiLieu', 'API\TaiLieuController@addTaiLieu');
    Route::post('/addthietke', 'API\TaiLieuController@addthietke');
    Route::get('/showdatatailieu', 'API\TaiLieuController@showdatatailieu');
    Route::post('/addroomTaiLieu', 'API\TaiLieuController@addroomTaiLieu');
    Route::get('/searchtailieu', 'API\TaiLieuController@index');
    
    Route::get('/addtargetsaleser', 'API\SalesTargetsController@addsales');

    Route::get('/select-week', 'API\WeekController@selectweek');
    Route::get('/bao-cao-ngay-showroom', 'API\WeekController@showroom');
    Route::get('/bao-cao-ngay-online', 'API\WeekController@online');

    Route::get('/checkasm', 'API\UserController@asm');
    Route::get('/checksm', 'API\UserController@sm');
    Route::get('/checkqa', 'API\UserController@qa');
    Route::get('/checkhr', 'API\UserController@hr');
    Route::get('/listcongsale', 'API\UserController@listcongsale');
    Route::get('/listcongvp', 'API\UserController@listcongvp');
    Route::get('/updatecong', 'API\UserController@updatecong');
    Route::get('/luongsale', 'API\UserController@luongsale');
    Route::get('/deletesr', 'API\UserController@deletesr');
    Route::get('/duyetcong', 'API\UserController@duyetcong');
    Route::get('/timdon', 'API\UserController@timdon');
    Route::get('/timdonhuy', 'API\UserController@timdonhuy');
    Route::get('/updatedon', 'API\UserController@updatedon');
    Route::get('/dayds', 'API\UserController@dayds');
    Route::get('/sm-chot', 'API\UserController@chot');
    Route::get('/asm-chot', 'API\UserController@asmchot');
    Route::get('/addluongkhac', 'API\UserController@addluongkhac');
    Route::get('/addluongphu', 'API\UserController@addluongphu');
    Route::get('/showluongkhac', 'API\UserController@showluongkhac');
    Route::get('/thuctrang', 'API\UserController@thuctrang');


    // Route::get('daily/{contact}/edit', 'API\DaiLyController@edit');
    Route::put('daily/{contact}/quotes', 'API\DaiLyController@storeQuote');
    Route::put('daily/{contact}/comment', 'API\DaiLyController@storecomment');
    Route::put('daily/{contact}/activities', 'API\DaiLyController@storeactivity');
    Route::put('daily/{contact}/tasks', 'API\DaiLyController@storetask');
    Route::put('daily/{contact}/videos', 'API\DaiLyController@storeVideo');
    Route::put('daily/{contact}/kh15s', 'API\DaiLyController@storeKh15');
    Route::put('daily/{contact}/losts', 'API\DaiLyController@storeLost');
    Route::post('daily/{contact}/attachmens', 'API\DaiLyController@storeattachmens');
    Route::post('daily/{contact}/image', 'API\DaiLyController@storeImage');
    Route::get('daily/{contact}/publish', 'API\DaiLyController@publish');
    Route::get('daily/{contact}/unpublish', 'API\DaiLyController@unPublish');

    Route::post('dc', 'API\DuChiController@update');
    Route::post('duyetcp', 'API\DuChiController@edit');
    Route::get('baocaochi', 'API\DuChiController@baocao');
    Route::get('duyetchi', 'API\DuChiController@duyetchi');

    Route::get('dinhbien', 'API\CostcentersController@dinhbien');
    Route::get('adddinhbien', 'API\CostcentersController@adddinhbien');
    Route::get('dexuat', 'API\UserController@dexuat');

    Route::get('duyetthicong', 'API\ThiCongController@duyetthicong');
    Route::post('thicongedit', 'API\ThiCongController@up');
    Route::post('thicongadd', 'API\ThiCongController@add');
    Route::get('deletethicong', 'API\ThiCongController@delete');
    Route::get('getdonhang', 'API\ThiCongController@getdonhang');
    Route::post('bdedit', 'API\ThiCongController@bdedit');
    Route::post('addbctc', 'API\ThiCongController@addbctc');
    Route::post('editbctc', 'API\ThiCongController@editbctc');
    Route::get('bctc', 'API\ThiCongController@bctc');
    Route::get('deletebctc', 'API\ThiCongController@deletebctc');

    Route::post('addmacsvc', 'API\CSVCController@addmacsvc');

});