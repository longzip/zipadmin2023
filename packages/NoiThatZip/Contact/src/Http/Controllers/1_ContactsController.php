<?php
namespace NoiThatZip\Contact\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Quocte\Models\Quocte;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use BrianFaust\Categories\Models\Category;
use App\Http\Resources\Contact as ContactResource;
use App\Product;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;
use App\GiaiDoan;
use App\LogGiaiDoan;
use App\Customer;
use App\DiemRoi;
use App\OrderLine;

class ContactsController extends Controller
{
/**
* Create a new controller instance.
*
* @return void
*/
public function __construct()
{
    $this->middleware('auth:api');
}
/**
* Display a listing of the resource.
*
* @return \Illuminate\Http\Response
*/
public function index(Request $request)
{
    $user = auth('api')->user();
    // if ($user->id == 1) {
    //     Participant::all()->each(function($participant){
    //         $participant->last_read = new Carbon();
    //         $participant->save();
    //     });
    // }
    // if ($user->id == 1) {
    //         Contact::all()->each(function($contact){
    //         $thread = Thread::where('subject','like', 'KHTN#' . $contact->id . '%')->first();
    //         if ($thread) {
    //             Message::create([
    //                 'thread_id' => $thread->id,
    //                 'user_id' => $contact->user_id,
    //                 'body' => $contact->user_id . ' tao KHTN' . $contact->name,
    //             ]);
    //         }
    //     });
    // }


    if ($user->id != 269 && $user->id != 9318 && $user->id != 9320) {
        if (!$user->can('import')) {
            if (!$request->has('users')) {
                $request['users'] = [$user->id];
            }
        }
        if ($user->id == 9335) {
            $request['costcenter'] = [1,2,3];
        }


        $contacts = Contact::filter($request->all())->latest()->paginateFilter();
        return ContactResource::collection($contacts);
    }else{
        return redirect('/home');
    }
}
/**
* Show the form for creating a new resource.
*
* @return \Illuminate\Http\Response
*/
public function create()
{
//
}
/**
* Store a newly created resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function store(Request $request)
{

    // dd($request->all());
    $sr = array();
    $c = Contact::where('phone',$request->phone)->get();
    if ($c->isNotEmpty()) {
        foreach ($c as $key => $value) {
            $a = count($value->costcentersList()) > 0 ? $value->costcentersList()[0]['name'] : '';
            if ($a !== '') {
                $sr[] = $a;     
            }
        }
    }
    $request->validate([
        'last_name' => ['required', 'string', 'max:255'],
        'phone' => 'required|min:10|max:10|unique:contacts',
        'address' => 'required',
        'costcenters' => 'required'
    ],
    [
        'last_name.required'=>'Bạn chưa nhập tên khách hàng',
        'phone.required'=>'Bạn chưa nhập số điện thoại',
        'address.required'=>'Bạn chưa nhập địa chỉ',
        'phone.unique' => 'Số Đã Trùng! Liên Hệ IT hoặc '.implode(',',$sr).' để thương lượng',
        'phone.min'=>'Số Điện Thoại Phải Có 10 Số',
        'phone.max'=>'Số Điện Thoại Phải Có 10 Số',
        'costcenters.required'=>'Bạn chưa chọn showroom',
    ]);
    $user = auth('api')->user();
    $contact = new Contact([
        'title' => $request['title'],
        'first_name' => $request['first_name'],
        'last_name' => $request['last_name'],
        'email' => $request['email'],
        'zalo' => $request['zalo'],
        'phone' => $request['phone'],
        'company' => $request['company'],
        'address' => $request['address'],
        'city' => $request['city'],
        'note' => $request['note'],
        'description' => $request['description'],
        'start_date' => $request['start_date'],
        'start_time' => $request['start_time'],
        'end_time' => $request['end_time'],
        'type' => $request['type'],
        'chiendich' => empty($request['chiendich']) ? null : $request['chiendich']['id'],
        'status'   => 1,
        'user_id' => $user->id
    ]);
    foreach ($request['salesarea'] as $key => $value) {
        $id_salesarea[] = $value['id'];
    }
    if ($request->has('salesarea')) {
        $contact->salesarea_id = json_encode($id_salesarea);
    }
    $contact->save();
    //gán trạng thái mới tạo cho khách hàng tiềm năng
    $thread = Thread::create(
        [
            'subject' => 'KHTN#' . $contact->id . '#' . $contact->last_name . ' được tạo bởi' . $user->name,
        ]
    );
    $contact->thread_id = $thread->id;
    $contact->save();
    $contact->syncCostcenters(collect($request['costcenters'])->pluck('id'));
    collect($request['products'])->pluck('id')
    ->each(function($id) use ($user, $contact)
    {
        $newProduct = Product::find($id);
        $contact->productLine([
            'product_id' => $newProduct->id,
            'quantity'   => 1,
            'price'      => $newProduct->price,
            'amount'     => $newProduct->price
        ], $user);
    });
    // Message
            Message::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'body' => $user->name . 'tao KHTN' . $contact->name,
            ]);
    // Sender
    Participant::create(
        [
            'thread_id' => $thread->id,
            'user_id'   => $user->id,
            'last_read' => new Carbon,
        ]
    );
    collect($request['costcenters'])->pluck('id')->each(function($id) use ($thread){
        $costcenter = \NoiThatZip\Costcenter\Models\Costcenter::find($id);
        $thread->addParticipant($costcenter->entries('\App\User')->pluck('id')->toArray());
    });
    activity()
    ->performedOn($contact)
    ->causedBy($user)
    ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
    ->log('Đã tạo KHTN :subject.last_name, bởi :causer.name');

    $gd = GiaiDoan::where('id_kh15',$request['id'])->first();
    if(!empty($gd)){
        $gd->id_khtn = $contact->id;
        $gd->save();
        $log = new LogGiaiDoan;
        $log->id_tt = $request['giaidoan'];
        $log->id_giaidoan = $gd['id'];
        $log->save();
    }

    if(!empty($request->diemroi)){
        $dr = new DiemRoi;
        $dr->id_khtn = $contact->id;
        $dr->id_calendar_t = $request->diemroi['id_table'];
        $dr->save();
    }
    return $contact;
}
/**
* Display the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function show($id)
{
    $contact = Contact::findOrFail($id);
    $user = auth('api')->user();
    Thread::find($contact->thread_id)->markAsRead($user->id);
    return new ContactResource($contact);
}
/**
* Show the form for editing the specified resource.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function edit($id)
{
    $contact = Contact::find($id);
    return $contact->with('costcenters');
}
protected function checkasm($id) {
    $id = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',4)->get();
    $check = ($id->isEmpty()) ? 0 : 1;
    return $check;
}
protected function checksm($id) {
    $id = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',6)->get();
    $check = ($id->isEmpty()) ? 0 : 1;
    return $check;
}
/**
* Update the specified resource in storage.
*
* @param  \Illuminate\Http\Request  $request
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function update(Request $request, $id)
{
    $dt = Carbon::now('Asia/Ho_Chi_Minh');

    $request->validate([
        'last_name' => ['required', 'string', 'max:255'],
        'phone' => 'required|min:10|max:10',
        'address' => 'required'
    ]);
    $user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    if($this->checkasm($user->id) == 1) {
        $contact->update([
            'status'   => $request['status'],
        ]);
        activity()
        ->performedOn($contact)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
        ->log('Cập nhật trạng thái KHTN :subject.last_name, bởi :causer.name');
        // return new ContactResource($contact);
    }
    // dd($request->all());
    if(!empty($request->giaidoan)){
        $gd = GiaiDoan::where('id_khtn',$request['id'])->first();
        if(!empty($gd)){
            $log = new LogGiaiDoan;
            $log->id_tt = $request['giaidoan'];
            $log->id_giaidoan = $gd['id'];
            $log->save();
        }else{
            $gd = new GiaiDoan;
            $gd->id_khtn = $id;
            $gd->save();
            $log = new LogGiaiDoan;
            $log->id_tt = $request['giaidoan'];
            $log->id_giaidoan = $gd['id'];
            $log->save();
        }
    }
    if($this->checksm($user->id) == 1) {
        $contact->update([
            'status'   => $request['status'],
        ]);
        if(!empty($request->diemroi)){
            $get_t = \DB::table('calendar_t')->where('start_t','<=',$dt->toDateString())->where('end_t','>=',$dt->toDateString())->first();
            $where_check = DiemRoi::where('id_khtn',$id)->where('id_calendar_t',$get_t->id_table)->get();
            if($where_check->isEmpty()){
                if(!empty($get_t)){
                    $delete = DiemRoi::where('id_calendar_t','>',$get_t->id_table)->delete();
                }
                $dr = new DiemRoi;
                $dr->id_khtn = $id;
                $dr->id_calendar_t = $request->diemroi['id_table'];
                $dr->save();
            }else{
                return response(array(
                    'success' => false,
                    'data' => [],
                    'message' => 'Điểm Rơi Đã Chọn'
                ),400,[]);
            }
        }
        return response(array(
            'success' => false,
            'data' => [],
            'message' => 'Bạn chỉ có quyền sửa Trạng Thái và Điểm Rơi nếu là SM. Nếu bạn là SM thì cập nhật đã hoàn tất'
        ),400,[]);
        activity()
        ->performedOn($contact)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
        ->log('Cập nhật trạng thái KHTN :subject.last_name, bởi :causer.name');
        // return new ContactResource($contact);
    }
    if ($user->id != $contact->user_id) {
        return response(array(
            'success' => false,
            'data' => [],
            'message' => 'Bạn không có quyền sửa'
        ),400,[]);
    }
    $contact->update([
        'title' => $request['title'],
        'first_name' => $request['first_name'],
        'last_name' => $request['last_name'],
        'email' => $request['email'],
        'zalo' => $request['zalo'],
        'phone' => $request['phone'],
        'company' => $request['company'],
        'address' => $request['address'],
        'city' => $request['city'],
        'note' => $request['note'],
        'chiendich' => empty($request['chiendich']) ? null : $request['chiendich']['id'],
        // 'status'   => $request['status'],
        'description' => $request['description'],
        'start_date' => $request['start_date'],
        'start_time' => $request['start_time'],
        'end_time' => $request['end_time']
    ]);
    foreach ($request['salesarea'] as $key => $value) {
        $id_salesarea[] = $value['id'];
    }
    if ($request->has('salesarea')) {
        $contact->salesarea_id = json_encode($id_salesarea);
        $contact->save();
    } 
    $contact->syncCostcenters(collect($request['costcenters'])->pluck('id'));
    //Gán sản phẩm quan tâm mới
    $contact->productLines()->delete();
    collect($request['products'])->pluck('id')
    ->each(function($id) use ($user, $contact)
    {
        $newProduct = Product::find($id);
        $contact->productLine([
            'product_id' => $newProduct->id,
            'quantity'   => 1,
            'price'      => $newProduct->price,
            'amount'     => $newProduct->price
        ], $user);
    });
    activity()
    ->performedOn($contact)
    ->causedBy($user)
    ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
    ->log('Cập nhật KHTN :subject.last_name, bởi :causer.name');

    
    if(!empty($request->diemroi)){
        // $check_diemroi = DiemRoi::where('id_khtn',$id)->where('id_calendar_t',$request->diemroi['id_table'])->get();
        // if ($check_diemroi->isEmpty()) {
            $get_t = \DB::table('calendar_t')->where('start_t','<=',$dt->toDateString())->where('end_t','>=',$dt->toDateString())->first();
            $where_check = DiemRoi::where('id_khtn',$id)->where('id_calendar_t',$get_t->id_table)->get();
            if($where_check->isEmpty()){
                if(!empty($get_t)){
                    $delete = DiemRoi::where('id_calendar_t','>',$get_t->id_table)->delete();
                }
                $dr = new DiemRoi;
                $dr->id_khtn = $id;
                $dr->id_calendar_t = $request->diemroi['id_table'];
                $dr->save();
            }else{
                return response(array(
                    'success' => false,
                    'data' => [],
                    'message' => 'Điểm Rơi Đã Chọn'
                ),400,[]);
            }
    }
    return new ContactResource($contact);
    return ['message' => 'Updated the Contact info'];
}
/**
* Remove the specified resource from storage.
*
* @param  int  $id
* @return \Illuminate\Http\Response
*/
public function destroy($id)
{
    $user = auth('api')->user();
    if($user->can('delete contacts')){
        $contact = Contact::find($id);
        $contact->delete();
        activity()
        ->performedOn($contact)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
        ->log('Xóa KHTN :subject.last_name, bởi :causer.name');
        return ['message' => 'Contact Deleted'];
    }
    else{
        return response()->toJson([
            'message' => 'Không thể xóa khách hàng tiềm năng!',
        ], 404);
    }
}
public function storeQuote(Request $request, $id)
{
    $request->validate([
        'productLines' => ['required'],
    ]);
    $user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    $quote = $contact->quote([
        'subject' => $request['subject'],
        'subtotal' => $request['subtotal'],
        'total' => $request['total'],
        'qgg' => $request['qgg'],
        'fee_vc' => $request['fee_vc'],
        'fee_ld' => $request['fee_ld'],
        'vat' => $request['vat']
    ], $user);
    collect($request['productLines'])->each(function($item) use(&$quote,$user,&$test)
    {
        $quote->productLine([
            'product_id' => $item['id'],
            'quantity'   => $item['quantity'],
            'price'      => $item['price'],
            'discount'   => $item['discount'],
            'point'      => Product::find($item['id'])->point,
            'amount'     => $item['thanh_tien']
        ], $user);
    });
    return $quote;
    activity()
    ->performedOn($quote)
    ->causedBy($user)
    ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
    ->log(':subject.subject, bởi :causer.name');
    return ['message' => "Thành công!"];
}
public function storecomment(Request $request, $id){
    $user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    $comment = $contact->comment([
        'title' => $user->name,
        'body' => $request['body'],
    ], $user);
    activity()
    ->performedOn($comment)
    ->causedBy($user)
    ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
    ->log('Bình luận :subject.body, bởi :causer.name');
    try {
        $thread = Thread::find($contact->thread_id);
        if ($thread) {
            // Message
            Message::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'body' => $user->name . ' bình luận ' . $comment->body,
            ]);
        }
    } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
        // do nothing
    }
    
    return $comment;
}
public function storeactivity(Request $request, $id){
    $$user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    $activity = $contact->activity([
        'subject' => $request['subject'],
        'date_start' => $request['date_start'],
        'due_date' => $request['due_date'],
        'time_start' => $request['time_start'],
        'time_end' => $request['time_end'],
        'status' => $request['status'],
        'note' => $request['note']
    ], $user);
    return $activity;
}
public function storeattachmens(Request $request,$id)
{
    $user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    // return $request->file('file');

    $media = $contact->addMedia($request->file('file'))
    ->withCustomProperties(['uploadBy' => $user->name . ' - ' . $user->id])
    ->toMediaCollection();
    activity()
    ->performedOn($contact)
    ->causedBy($user)
    ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
    ->log('Gửi tài liệu :subject.last_name, bởi :causer.name');
    return $media->getUrl();
}
public function storetask(Request $request, $id){
    $user = User::find($request['user_id']);
    $contact = Contact::findOrFail($id);
    $task = $contact->task([
        'title' => $request['title'],
        'task' => $request['task'],
        'priority' => $request['priority'],
        'duedate' => $request['duedate']
    ], $user);
    activity()
    ->performedOn($task)
    ->causedBy($user)
    ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
    ->log('Kế hoạch :subject.title, bởi :causer.name');
    return new TaskResource($task);
}
public function storeVideo(Request $request, $id){
    $user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    $video = $contact->video([
        'title' => $request['title'],
    ], $user);
    activity()
    ->performedOn($contact)
    ->causedBy($user)
    ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
    ->log('Video :subject.title, bởi :causer.name');
     try {
        $thread = Thread::find($contact->thread_id);
        if ($thread) {
            // Message
            Message::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'body' => $user->name . ' đăng video',
            ]);
        }
    } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
        // do nothing
    }
    return $video;
}
public function storeImage(Request $request,$id)
{
    // dd($request->file('files'));
    $user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    foreach ($request->file('files') as $key => $value) {
        $media = $contact->addMedia($value)
        ->toMediaCollection('images','public');
        $image = $contact->attach([
            'src' => $media->getUrl(),
        ], $user);
        // $tailieu = AttachmentResource($image);
    }
    return ['message' => 'Thành công!'];
}
public function storeKh15(Request $request, $id){
    $user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    $kh15 = $contact->kh15([
        'name' => $request['name'],
        'description' => $request['description'],
    ], $user);
    return $kh15;
}
public function storeLost(Request $request, $id){
    $user = auth('api')->user();
    $contact = Contact::findOrFail($id);
    $lost = $contact->lost([
        'name' => $request['name'],
        'description' => $request['description'],
    ], $user);
    return $lost;
}
public function publish($id){
    $user = auth('api')->user();
    if ($user->can('publish contact')) {
        $contact = Contact::findOrFail($id);
        $contact->publish();
        return ['message' => 'Thành công!'];
    }
    else{
        return response('Loi', 402);
    }
}
public function unPublish($id){
    $user = auth('api')->user();
    if ($user->can('publish contact')) {
        $contact = Contact::findOrFail($id);
        $contact->unpublish();
        return ['message' => 'Thành công!'];
    }
    return response('Loi', 402);
}
    public function countByDate(Request $request){
        $contacts = Contact::filter($request->all())->get();
        $counted = $contacts->countBy(function($contact){
           return $contact->start_date;
        });
        $sum = $counted->sum();
        return [$sum,$counted,'KHTN'];
        // return $counted->sortKeys()->all();
    }
    public function countByCostcenter(Request $request){
        $contacts = Contact::filter($request->all())->get(); // lấy thông tin từ bảng contac // khách hàng tiềm năng
        // dd($contacts);
        // Product::select('id','code as name')->whereIn('id',$this->productLines()->pluck('product_id'))->get()
        // $id_pd = $request->p;
// dd($pg_gr)
        $counted = $contacts->countBy(function($contact){ // đếm những thằng id trùng từ biến contact
            $costcenter = $contact->costcenters()->first();
        // dd($contact);
            if (!$costcenter) {
                return 'Khac'; // khi không có id
            }
            return $costcenter->id; // có id -> lấy id ra
        });

        $namesp = $request->p;
        $costcenter = Costcenter::where('closed','>',$request->sdates[0])->get();
        $costcenters = $costcenter->map(function($costcenter) use ($counted,$namesp,$contacts){ //
        //     $a = array();
        //     $c= array();
        //     $mang= array();


        // $kh = Contact::all();
        // foreach ($kh as $value) {
        //     $kh_sr = $value->costcentersList();
        //     foreach ($kh_sr as $val) {
        //         $b=$val->name;

        //         $check[] = Product::select('id','code as name')->whereIn('id',$value->productLines()->pluck('product_id'))->get();
        //         if($namesp != null){
        //             foreach ($check as $va) {

        //                 if ($va->id == $namesp) {
        //                     $a[$b][]=$va->name;
        //                 }
        //             }
        //         }
        //         else{ $a = null;}
        //             // $a[$b]["productLines"] = $va->name;
        //         }
        //     }
        //         return ($a);

        // if($a != null){
        //     foreach ($a as $key => $value) {
        //         $arr_key[]=$key;
        //     }
        //     foreach ($a as $key => $value) {
        //         $count_mang[$key][] =array_count_values($value);
        //     }
        //     $dem= array_search($costcenter->name,$arr_key);
        // if(is_numeric($dem)){
        //     // dd($count_mang);
        //         return [
        //             'name'=> $costcenter->name, //chi nhánh
        //             'count' => isset($counted[$costcenter->id])? $counted[$costcenter->id] : 0 ,//đếm id chi nhánh
        //             'product' =>$count_mang[$costcenter->name]
        //             ];
        //     }
        //     else {
        //         return [
        //             'name'=> $costcenter->name, //chi nhánh
        //             'count' => isset($counted[$costcenter->id])? $counted[$costcenter->id] : 0 ,//đếm id chi nhánh
        //             'product' =>0,
        //             ];
        //     }
        // }
            // $r = Product::select('id')->whereIn('id',$value->productLines()->pluck('product_id'))->get();
            // dd($r);
            
        if($namesp != null){
            foreach ($contacts as $value) {
                $kh_sr = $value->costcentersList();
                foreach ($kh_sr as $val) {
                // dd($val->name);
                    // $b=$val->name;

                    $check = $value->productLines()->pluck('product_id')->toArray();
                    $pg_gr = Product::where('groups',$namesp)->whereIn('id',$check)->count();
                    $count[$val->name][]=isset($pg_gr) ? $pg_gr : 0;
                    // $tet = $check;
                    // dd($check);
                }
            }

            return [
                    'name'=> $costcenter->name, //chi nhánh
                    'count' => isset($counted[$costcenter->id])? $counted[$costcenter->id] : 0 ,//đếm id chi nhánh
                    'product' =>isset($count[$costcenter->name]) ? array_sum($count[$costcenter->name]) : 0,
                    ];
        }
        else{
            return [
                'name'=> $costcenter->name, //chi nhánh
                'count' => isset($counted[$costcenter->id])? $counted[$costcenter->id] : 0 
            ];
        }

    });

        // $a = $costcenter->id;
        return $costcenters->sortByDesc('count')->values()->take(150);
    }
    // public function nameProduct(Request $request){
    //     $start = $request->sdates[0];
    //     $end = $request->sdates[1];
    //     $a = array();
    //     $c= array();
    //     $kh = Contact::whereBetween('start_date',[$start,$end])->get();
    //     foreach ($kh as $value) {
    //         // $kh_sr = $value->costcentersList(); // lấy thông tin SR theo từng khách hàng
    //         // foreach ($kh_sr as $val) {
    //         //     $b=$val->name; // lấy tên sr

    //             $check = Product::select('id','code as name')->whereIn('id',$value->productLines()->pluck('product_id'))->get();
    //              // lấy tên sp của khách hàng
    //             foreach ($check as $va) {
    //                 $a[]=$va->name;
    //                 // $a[$b]["productLines"] = $va->name;
    //             }

    //         // }
    //     }
    //            $c = array_unique($a);
    //             sort($c);
    //             // $myobject = arrayToObject($c);
    //             // dd($myobject);
    //     return $c;
    //     // foreach ($a as $key => $value) {
    //     //     $arr_key[]=$key;
    //     // }
    //     //  foreach ($a as $key => $value) {
    //     //     $count_mang[$key][] =array_count_values($value);
    //     // }
    // }
    public function array_to_obj($array, &$obj)
    {
        foreach ($array as $key => $value)
        {
          if (is_array($value))
          {
          $obj->$key = new stdClass();
          array_to_obj($value, $obj->$key);
          }
          else
          {
            $obj->$key = $value;
          }
        }
      return $obj;
      }

    public function arrayToObject($array)
    {
     $object= new stdClass();
     return array_to_obj($array,$object);
    }
    public function countBySale(Request $request){
        $contacts = Contact::filter($request->all())->get(); // lấy all tt khách hàng bảng contact
        $counted = $contacts->countBy(function($contact){ // đếm mỗi thằng có bao nhiêu kh
           return $contact->user_id;
        });
        $namesp = $request->p;
        $user = $this->sales($request->sdates)->map(function($user) use ($counted,$contacts,$namesp){
            $sr = $user->costcentersList();
            if($namesp != null){
            foreach ($contacts as $value) {
                    $check = $value->productLines()->pluck('product_id')->toArray();
                    $pg_gr = Product::where('groups',$namesp)->whereIn('id',$check)->count();
                    $count[$value->user_id][]=isset($pg_gr) ? $pg_gr : 0;
                    }
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'manv' => $user->username,
                        'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
                        'count' => isset($counted[$user->id]) ? $counted[$user->id] : 0,
                        'product'=>isset($count[$user->id]) ? array_sum($count[$user->id]) : 0,
                        ];
                }
        else {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'manv' => $user->username,
                'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
                'count' => isset($counted[$user->id]) ? $counted[$user->id] : 0
                    ];
            }
        });
        return $user->sortByDesc('count')->values()->take(150);
    }

    public function sales($r){
        $sales = User::whereDate('date_off','>',$r)->get();
        $sale = collect($sales)->filter(function($user){
            return $user->hasRole('sales');
        });
        return $sale;
    }

    public function targets(Request $request){
        $users = User::filter($request->all())->get();
        $sales = $users->filter(function($user){
            return $user->hasRole('sales');
        });

        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        $weeks = collect(range($start_week, $end_week ));
        // return $weeks;
        $contacts = $this->contacts2Target(['dates' => $request['dates']]);
        // return $contacts;

        $salesTargets = $sales->map(function($sale) use($dates,$contacts,$weeks){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            $findContacts = isset($contacts[$sale->id]) ? $contacts[$sale->id] : [];
            $name = $sale->name;
            $targets = $this->groupByWeek2($findTargets->get());

            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'costcenter' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->name : 'Khac',
                'targets' => $targets,
                't0' => isset($targets[$weeks[0]]) ? (int)$targets[$weeks[0]]->sum('number') : 0,
                't1' => isset($targets[$weeks[1]]) ? (int)$targets[$weeks[1]]->sum('number') : 0,
                't2' => isset($targets[$weeks[2]]) ? (int)$targets[$weeks[2]]->sum('number') : 0,
                't3' => isset($targets[$weeks[3]]) ? (int)$targets[$weeks[3]]->sum('number') : 0,
                'contacts' => $findContacts,
                'c0' => isset($findContacts[$weeks[0]]) ? $findContacts[$weeks[0]]->values()->sum() : 0,
                'c1' => isset($findContacts[$weeks[1]]) ? $findContacts[$weeks[1]]->values()->sum() : 0,
                'c2' => isset($findContacts[$weeks[2]]) ? $findContacts[$weeks[2]]->values()->sum() : 0,
                'c3' => isset($findContacts[$weeks[3]]) ? $findContacts[$weeks[3]]->values()->sum() : 0,
            ];
        });
        // return $salesTargets->values();
        $showroom = $salesTargets->values()->groupBy(function($salet){
            return $salet->costcenter;
        });

        return $showroom;
    }

    public function costcentersTarget(Request $request){
        $sales = Costcenter::filter($request->all())->get();

        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        $weeks = collect(range($start_week, $end_week ));
        // return $weeks;
        $contacts = $this->contacts2Target2(['dates' => $request['dates']]);
        // return $contacts;

        $salesTargets = $sales->map(function($sale) use($dates,$contacts,$weeks){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            $findContacts = isset($contacts[$sale->name]) ? $contacts[$sale->name] : [];
            $name = $sale->name;
            $targets = $this->groupByWeek2($findTargets->get());

            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'warehouse' => $sale->warehouse,
                'targets' => $targets,
                't0' => isset($targets[$weeks[0]]) ? (int)$targets[$weeks[0]]->sum('number') : 0,
                't1' => isset($targets[$weeks[1]]) ? (int)$targets[$weeks[1]]->sum('number') : 0,
                't2' => isset($targets[$weeks[2]]) ? (int)$targets[$weeks[2]]->sum('number') : 0,
                't3' => isset($targets[$weeks[3]]) ? (int)$targets[$weeks[3]]->sum('number') : 0,
                'contacts' => $findContacts,
                'c0' => isset($findContacts[$weeks[0]]) ? $findContacts[$weeks[0]]->values()->sum() : 0,
                'c1' => isset($findContacts[$weeks[1]]) ? $findContacts[$weeks[1]]->values()->sum() : 0,
                'c2' => isset($findContacts[$weeks[2]]) ? $findContacts[$weeks[2]]->values()->sum() : 0,
                'c3' => isset($findContacts[$weeks[3]]) ? $findContacts[$weeks[3]]->values()->sum() : 0,
            ];
        });
        // return $salesTargets->values();
        $showroom = $salesTargets->values()->groupBy(function($salet){
            return $salet->warehouse;
        });

        return $showroom;
    }

    public function zipTarget(Request $request){
        $sales = Costcenter::filter($request->all())->get();

        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        $weeks = collect(range($start_week, $end_week ));
        // return $weeks;
        $contacts = $this->contacts2Target3(['dates' => $request['dates']]);
        // return $contacts;

        $salesTargets = $sales->map(function($sale) use($dates,$contacts,$weeks){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            $findContacts = isset($contacts[$sale->name]) ? $contacts[$sale->name] : [];
            $name = $sale->name;
            $targets = $this->groupByWeek2($findTargets->get());

            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'warehouse' => $sale->warehouse,
                'targets' => $targets,
                't0' => isset($targets[$weeks[0]]) ? (int)$targets[$weeks[0]]->sum('number') : 0,
                't1' => isset($targets[$weeks[1]]) ? (int)$targets[$weeks[1]]->sum('number') : 0,
                't2' => isset($targets[$weeks[2]]) ? (int)$targets[$weeks[2]]->sum('number') : 0,
                't3' => isset($targets[$weeks[3]]) ? (int)$targets[$weeks[3]]->sum('number') : 0,
                'contacts' => $findContacts,
                'c0' => isset($findContacts[$weeks[0]]) ? $findContacts[$weeks[0]]->values()->sum() : 0,
                'c1' => isset($findContacts[$weeks[1]]) ? $findContacts[$weeks[1]]->values()->sum() : 0,
                'c2' => isset($findContacts[$weeks[2]]) ? $findContacts[$weeks[2]]->values()->sum() : 0,
                'c3' => isset($findContacts[$weeks[3]]) ? $findContacts[$weeks[3]]->values()->sum() : 0,
            ];
        });
        // return $salesTargets->values();
        $showroom = $salesTargets->values()->groupBy(function($salet){
            return $salet->warehouse;
        });

        return $showroom;
    }

    protected function contacts2Target2($dates){
        $contacts = Contact::filter($dates)->get();

        $contacts = $this->groupByCostcenter($contacts);

        $contacts = $contacts->map(function($contacts){
            return $this->groupByWeek($contacts)->map(function($contacts){
                return $this->countByDay($contacts);
            });
        });
        return $contacts;
    }

    protected function contacts2Target3($dates){
        $contacts = Contact::filter($dates)->get();

        $contacts = $this->groupByCostcenter($contacts);

        $contacts = $contacts->map(function($contacts){
            return $this->groupByWeek($contacts)->map(function($contacts){
                return $this->countByDay($contacts);
            });
        });
        return $contacts;
    }

    protected function contacts2Target($dates){
        $contacts = Contact::filter($dates)->get();
        $contacts = $this->groupBySale($contacts);
        $contacts = $contacts->map(function($contacts){
            return $this->groupByWeek($contacts)->map(function($contacts){
                return $this->countByDay($contacts);
            });
        });
        return $contacts;
    }

    protected function groupBySale($contacts){
        return $contacts->groupBy(function($contact){
            return $contact->user_id;
        });
    }

    protected function groupByCostcenter($contacts){
        return $contacts->groupBy(function($contact){
            $sr = $contact->costcentersList();
            return $sr->isNotEmpty() ? $sr->first()->name : 'Khac';
        });
    }

    protected function groupByCostcenter2($contacts){
        return $contacts->groupBy(function($contact){
            $sr = $contact->costcentersList();
            return $sr->isNotEmpty() ? $sr->first()->warehouse : 'Khac';
        });
    }

    protected function groupByWeek($contacts){
        return $contacts->groupBy(function($contact){
            $date = new Carbon($contact->created_at);
            return $date->weekOfYear;
        });
    }

    protected function groupByWeek2($contacts){
        return $contacts->groupBy(function($contact){
            $date = new Carbon($contact->close);
            return $date->weekOfYear;
        });
    }

    protected function countByDay($contacts){
        return $contacts->countBy(function($contact){
            $date = new Carbon($contact->created_at);
            return $date->dayOfWeek;
        });
    }

    protected function countByWeek($contacts){
        return $contacts->countBy(function($contact){
            $date = new Carbon($contact->created_at);
            return $date->dayOfWeek;
        });
    }
}
