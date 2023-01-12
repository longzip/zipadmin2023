<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Quocte\Models\Quocte;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\GiaiDoan;
use App\LogGiaiDoan;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use BrianFaust\Categories\Models\Category;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use App\Http\Resources\Contact as ContactResource;
use App\Product;
use NoiThatZip\Lead\Models\Lead;
use App\LogStatus;
use App\ActivityLogs;
use App\LogContact;
use Spatie\Activitylog\Models\Activity;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;
use App\Http\Resources\Order as OrderResource;
use App\Order;

class ContactsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $user = auth('api')->user();
        if (!$user->can('import')) {
            if (!$request->has('users')) {
                $request['users'] = [$user->id];
            }
        }
        $contacts = Contact::filter($request->all())->latest()->paginateFilter();
        return ContactResource::collection($contacts);
    }

    public function searchContactbyShowroom(Request $request)
    {
        return $request;
        $user = auth('api')->user();
        $contacts = null;
        if ($user->hasRole('qa')) {
            $contacts = Contact::latest()->paginate(3);
        } else {
            $contacts = Contact::latest()->where('user_id',$user->id)->paginate(25);
        }
        $contacts->map(function($contact){
            $contact->note = $contact->status;
            $contact->user_id = User::find($contact->user_id)->name;
        });
        return $contacts;
    }

    public function create()
    {
    //
    }

    public function store(Request $request)
    {
        
    }

    public function convertFromKh15(Request $request)
    {
    //return $request;
        $this->validate($request,[
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => 'required|min:10|max:10',
            'address' => 'required'
        ]);
        $user = auth('api')->user();
        $contact = Contact::Create([
            'title' => $request['title'],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'company' => $request['company'],
            'address' => $request['address'],
            'city' => $request['city'],
            'description' => $request['description'],
            'note' => $request['note'],
            'start_date' => $request['start_date'],
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
            'user_id' => $user->id
        ]);
    //$contact->syncCostcenters([$user->resource->costcenter]);
        return $contact;
    }

    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return new ContactResource($contact);
    }

    public function edit($id)
    {
        $contact = Contact::find($id);
        return $contact->with('costcenters');
    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {
        
    }

    public function categoriesList($id)
    {
        $contact = Contact::find($id);
        return $contact->quotes();
    }

    public function quoteList($id)
    {
        $contact = Contact::find($id);
        return $contact->categoriesList();
    }

    public function storeQuote(Request $request, $id)
    {
    //return $request;
        $this->validate($request,[
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
    //return $quote;
        activity()
        ->performedOn($quote)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
        ->log(':subject.subject, bởi :causer.name');
        return ['message' => "Thành công!"];
    }

    public function quotes($id){
        $contact = Contact::find($id);
        return $contact->quotes;
    }

    public function storecomment(Request $request, $id){
        $user = auth('api')->user();
        $contact = Contact::findOrFail($id);
        $comment = $contact->comment([
            'title' => $user->name,
            'body' => $request['body'],
        ], $user);
        activity()
        ->performedOn($contact)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
        ->log('Bình luận :subject.body, bởi :causer.name');
        return $comment;
    }

    public function comments($id){
        $contact = Contact::findOrFail($id);
        return $contact->comments;
    }

    public function storeactivity(Request $request, $id){
        $user = auth('api')->user();
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

    public function storetask(Request $request, $id){
    //return $request;
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
        return $video;
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

    public function videos($id){
        $contact = Contact::findOrFail($id);
        return $contact->videos;
    }

    public function activities($id){
        $items = [];
        $contact = Contact::findOrFail($id);
        $contact->activities->each(function($item) use (&$items){
            $item['user_name'] = User::find($item->creator_id)->name;
            $items[] = $item;
        });
        return $items;
    }

    public function tasks($id)
    {
        $items = [];
        $contact = Contact::findOrFail($id);
        $contact->tasks->each(function($item) use (&$items){
            $item['user_name'] = User::find($item->creator_id)->name;
            $items[] = $item;
        });
        return $items;
    }

    public function images($id)
    {
        $contact = Contact::findOrFail($id);
        return $contact->getMedia()->map(function($file){
            return $file->getFullUrl();
        });
    }

    public function attachmens($id)
    {
        $items = [];
        $contact = Contact::findOrFail($id);
        return $contact->getMedia();
    }

    public function storeKh15(Request $request, $id){
        $user = auth('api')->user();
        $contact = Contact::findOrFail($id);
        $kh15 = $contact->kh15([
            'name' => $request['name'],
            'description' => $request['description'],
        ], $user);
        activity()
        ->performedOn($kh15)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
        ->log('Thêm đặc điểm KHTN :subject.name :subject.description, bởi :causer.name');
        return $kh15;
    }

    public function kh15s($id){
        $items = [];
        $contact = Contact::findOrFail($id);
        $contact->kh15s->each(function($item) use (&$items){
            $item['user_name'] = User::find($item->creator_id)->name;
            $items[] = $item;
        });
        return $items;
    }

    public function storeLost(Request $request, $id){
        $user = auth('api')->user();
        $contact = Contact::findOrFail($id);
        $lost = $contact->lost([
            'name' => $request['name'],
            'description' => $request['description'],
        ], $user);
        activity()
        ->performedOn($lost)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
        ->log('Thêm lo lắng :subject.name :subject.description, bởi :causer.name');
        return $lost;
    }

    public function losts($id){
        $items = [];
        $contact = Contact::findOrFail($id);
        $contact->losts->each(function($item) use (&$items){
            $item['user_name'] = User::find($item->creator_id)->name;
            $items[] = $item;
        });
        return $items;
    }

    public function findByCurrentUser(){
        $user = auth('api')->user();
        $resource = $user->resource;
    }

    public function user($id){
        $user_id = Contact::find($id)->user_id;
        return User::select('id','name')->where('id',$user_id)->get()->toArray();
    }

    public function countByDate(Request $request){
        $contacts = Contact::filter($request->all())->get();
        $counted = $contacts->countBy(function($contact){
             return $contact->created_at->toDateString();
         });
        return $counted->sortKeys()->all();
    }

    public function sum(Request $request){
        $sum_amount = [];
        $sum_amount_pages = [];
        $amount = Contact::filter($request->all())->latest()->get();
        foreach ($amount as $key => $value) {
            $sum_amount[] = $value->quotes->max('total');
        }
        $amount_pages = Contact::filter($request->all())->latest()->paginateFilter();
        foreach ($amount_pages as $key => $value) {
            $sum_amount_pages[] = $value->quotes->max('total');
        }
        $sum = ['sum_amount' => array_sum($sum_amount), 'sum_amount_pages' => array_sum($sum_amount_pages)];
        return $sum;
    }

    public function updatestatus(Request $request){
        $new = new LogStatus;
        $new->id_khtn = $request->id;
        $new->id_stt = $request->status;
        if ($request->status == 1) {
            $status = 'Đang chăm sóc' ;
        }
        if ($request->status == 2) {
            $status = 'Tạm dừng' ;
        }
        if ($request->status == 3) {
            $status = 'Gần đơn hàng' ;
        }
        if ($request->status == 4) {
            $status = 'ĐH chờ' ;
        }
        if ($request->status == 5) {
            $status = 'Đơn hàng' ;
        }
        if ($request->status == 6) {
            $status = 'Gọi Điện';
        }
        if ($request->status == 7) {
            $status = 'Có Cuộc Hẹn Tại Nhà';
        }
        if ($request->status == 8) {
            $status = 'Tiềm Năng Xa';
        }
        if ($request->status == 9) {
            $status = 'Có Cuộc Hẹn Tại Showroom';
        }
        if ($request->status == 10) {
            $status = 'Cuộc Hẹn Tại Nhà';
        }
        if ($request->status == 11) {
            $status = 'Cuộc Hẹn Tại Showroom';
        }
        $new->name = $status;
        $new->date = $request->date;
        $new->time = $request->time;
        $new->save();

        $contact = Contact::findOrFail($request->id);
        $contact->status =  $request->status;
        $contact->save();

        return LogStatus::all();
    }

    public function showstatus(Request $request){
        return LogStatus::where('id_khtn',$request->id)->get();
    }

    public function showinfocustum(Request $request){
        $lastActivity = Activity::all()->last(); //returns the last logged activity
        $lastActivity->getExtraProperty('id'); //returns 'value' 
        $lastActivity->where('properties->id', $request->idContact)->get();
        $kh = $lastActivity->where('properties->id', $request->id)->orWhere('properties->id', $request->idContact)->get();
        $lead = Lead::find($request->id);
        return ['lead'=>$lead,'kh'=>$kh];
    }

    public function showinfonv(Request $request){
        $kh = ActivityLogs::where('causer_id', $request->user)->orderBy('id','desc')->get();
        $lead = User::find($request->user);
        return ['lead'=>$lead,'kh'=>$kh, 'showroom' =>  $request->costcenters];
    }

    public function calendar(Request $request){
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        return \DB::table('calendar_t')->whereDate('start_t','>=','2020-06-08')->get();
    }

    public function updategiaidoan(Request $request){
        $gd = GiaiDoan::where('id_khtn',$request['id'])->first();
        // dd($gd['id']);
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

    public function loaikh(Request $request){
        $contacts = Contact::findOrFail($request->id);
        $contacts->loai = 1;
        $contacts->save();

        $log = new LogContact;
        $log->stt = 1;
        $log->user_id = \Auth::user()->id;
        $log->id_contact = $request->id;
        $log->save();
    }

    public function addkh(Request $request){
        $contact = Contact::findOrFail($request->id);
        $contact->loai = 0;
        $contact->save();

        $log = new LogContact;
        $log->stt = 0;
        $log->user_id = \Auth::user()->id;
        $log->id_contact = $request->id;
        $log->save();
    }

    public function loadloai(Request $request)
    {
        return LogContact::where('id_contact',$request['id'])->get();
    }

    public function baocaokhtn(Request $request)
    {
        // dd();
        if (empty($request->costcenter)) {
            $costcenter = range(0,45);
        }else{
            $costcenter = $request->costcenter;
        }
        $date = $request->da;
        $type = $request->type;
        // return $id;
        if (is_numeric(array_search(0, $request->p))) {
            $contacts = collect();
            $range = range(1,13);
            if (count($request->p) > 1) {
                if ($request->p > 0) {
                    foreach ($request->p as $key => $value) {
                        unset($range[$value - 1]);
                    }
                }
            }
            $calendar = \DB::table('calendar')->whereIn('p',$range)->where('z',$request->year)->get();
            foreach ($calendar as $key => $value) {
                if ($type == 0) {
                    $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->whereIn('costcenter_id',$costcenter)->pluck('model_id');
                    $data = Contact::whereBetween('start_date',[$value->start,$value->end])->whereIn('id',$id)->get();
                }else{
                    $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->whereIn('costcenter_id',$costcenter)->pluck('model_id');
                    $checkkhtn = Lead::whereBetween('start_date',[$value->start,$value->end])->whereIn('id',$id)->get();
                    $loai = Contact::whereIn('phone',$checkkhtn->pluck('phone'))->get();
                    $data = Lead::whereBetween('start_date',[$value->start,$value->end])->whereIn('id',$id)->whereNotIn('phone',$loai->pluck('phone'))->get();

                }
                $contacts = $contacts->merge($data);
            }
        }else{
            if (count($request->p) > 1) {
                $contacts = collect();
                foreach ($request->p as $key => $value) {
                    $calendar = \DB::table('calendar')->where('p',$value)->where('z',$request->year)->first();
                    if ($type == 0) {
                        $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->whereIn('costcenter_id',$costcenter)->pluck('model_id');
                        $data = Contact::whereBetween('start_date',[$calendar->start,$calendar->end])->whereIn('id',$id)->get();
                    }else{
                        $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->whereIn('costcenter_id',$costcenter)->pluck('model_id');
                        $checkkhtn = Lead::whereBetween('start_date',[$calendar->start,$calendar->end])->whereIn('id',$id)->get();
                        $loai = Contact::whereIn('phone',$checkkhtn->pluck('phone'))->get();
                        $data = Lead::whereBetween('start_date',[$calendar->start,$calendar->end])->whereIn('id',$id)->whereNotIn('phone',$loai->pluck('phone'))->get();
                    }
                    $contacts = $contacts->merge($data);
                }
            }else{
                $calendar = \DB::table('calendar')->where('p',$request->p)->where('z',$request->year)->first();
                if ($type == 0) {
                    $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->whereIn('costcenter_id',$costcenter)->pluck('model_id');
                    $contacts = Contact::whereBetween('start_date',[$calendar->start,$calendar->end])->whereIn('id',$id)->get();
                }else{
                    $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->whereIn('costcenter_id',$costcenter)->pluck('model_id');
                    $checkkhtn = Lead::whereBetween('start_date',[$calendar->start,$calendar->end])->whereIn('id',$id)->get();
                    $loai = Contact::whereIn('phone',$checkkhtn->pluck('phone'))->get();
                    $contacts = Lead::whereBetween('start_date',[$calendar->start,$calendar->end])->whereIn('id',$id)->whereNotIn('phone',$loai->pluck('phone'))->get();
                }
            }
        }
        
        $contact = $contacts->map(function ($value) use ($date,$type){
            $start = $date[0];
            $end = $date[1];
            // $image = AttachmentResource::collection($value->attachs)->whereBetween('created_at',['2020-02-24 00:00:00','2020-02-24 23:59:59'])->count();
            $image = AttachmentResource::collection($value->attachs)->whereBetween('created_at',[$start." 00:00:00", $end." 23:59:59"])->values();
            $tasks = TaskResource::collection($value->tasks)->whereBetween('created_at',[$start." 00:00:00", $end." 23:59:59"])->values();
            $image = $image->map(function ($value){
                return [
                    'created_at' => $value->created_at->format("Y-m-d"),
                ];
            })->pluck('created_at');
            $tasks = $tasks->map(function ($value){
                return [
                    'created_at' => $value->created_at->format("Y-m-d"),
                ];
            })->pluck('created_at');
            // $check = 1;
            // if ($image == 0 && $tasks == 0) {
            //     $check = 0;
            // }
            $merger = $image->merge($tasks);
            $array = array_unique($merger->toArray());
            $check = array_search($value->created_at->format('Y-m-d'), $array);
            $check = is_numeric($check);
            if ($check) {
                $stt = count($array) - 1;
                $array = array_unique($merger->toArray());
                $check = array_search($value->created_at->format('Y-m-d'), $array);
                unset($array[$check]);
            }else{
                $stt = count($array);
            }
            $gop = array();$datatask = array();$dataimage = array();
            foreach ($array as $k => $v) {
                $i = AttachmentResource::collection($value->attachs)->whereBetween('created_at',[$v." 00:00:00", $v." 23:59:59"])->values();
                $t = TaskResource::collection($value->tasks)->whereBetween('created_at',[$v." 00:00:00", $v." 23:59:59"]);
                if ($i->isNotEmpty()) {
                    array_push($gop,$i);
                }
                if ($t->isNotEmpty()) {
                    array_push($gop,$t);
                }
                $datatask[] = $t;
                $dataimage[] = $i;
            }
            $cost = count($value->costcentersList()) > 0 ? $value->costcentersList()[0]['name'] : '';
            $idShowromm = count($value->costcentersList()) > 0 ? $value->costcentersList()[0]['id'] : 0;
            
            return [
                'id' => $value->id,
                'name' => $value->last_name,
                'created_at' => $value->created_at->format('Y-m-d'),
                'status' => $type == 0 ? (integer)$value->status : (integer)$value->statuskh,
                'image' => $dataimage,
                'tasks' => $datatask,
                'array' => $array,
                'user' => User::findOrFail($value->user_id)['name'],
                'check' => $check,
                'count' => $stt,
                'gop' => $gop,
                'costcenters' => $cost,
                'idShowromm' => $idShowromm,
            ];
        }); 
        

        if(count($costcenter) > 1){
            $contact = $contact->groupBy('idShowromm');
            
        }else{
            $contact = $contact->groupBy('idShowromm')->merge($contact->groupBy('user'));
        }  
        //return $contact;
        $array = array();  
        foreach ($contact as $key => $v) {
            
            $array[$key]['tamdung'] = 0; $array[$key]['tamdunghd'] = 0; $array[$key]['tamdungcc'] = 0; 
            $array[$key]['dcs'] = 0; $array[$key]['dcshd'] = 0; $array[$key]['dcscc'] = 0; 
            $array[$key]['dh'] = 0; $array[$key]['dhhd'] = 0; $array[$key]['dhcc'] = 0; 
            $array[$key]['dhc'] = 0; $array[$key]['dhchd'] = 0; $array[$key]['dhccc'] = 0; 
            $array[$key]['cchtn'] = 0; $array[$key]['cchtnhd'] = 0; $array[$key]['cchtncc'] = 0; 
            $array[$key]['gdh'] = 0; $array[$key]['gdhhd'] = 0; $array[$key]['gdhcc'] = 0; 
            $array[$key]['chtn'] = 0; $array[$key]['chtnhd'] = 0; $array[$key]['chtncc'] = 0; 
            $array[$key]['chts'] = 0; $array[$key]['chtshd'] = 0; $array[$key]['chtscc'] = 0; 
            $array[$key]['gd'] = 0; $array[$key]['gdhd'] = 0; $array[$key]['gdcc'] = 0; 
            $array[$key]['cchts'] = 0; $array[$key]['cchtshd'] = 0; $array[$key]['cchtscc'] = 0; 
            $array[$key]['tnx'] = 0; $array[$key]['tnxhd'] = 0; $array[$key]['tnxcc'] = 0; 
            foreach ($v as $value) {
                // dd($value['id']);
                $array[$key]['id'] = $value['id']; 
                $array[$key]['showroom'] = is_numeric($key) ? $value['costcenters'] : $key; 

                if ($value['status'] == 2) {
                    $array[$key]['tamdung'] += 1; 
                    $array[$key]['tamdunghd'] += $value['count']; 
                    if ($value['count'] > 0) {
                        $array[$key]['infotamdung'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infotamdung'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['tamdungcc'] += 1;
                        $array[$key]['cctamdung'][$value['id']]['name'] = $value['name'];
                    }
                }
                if ($value['status'] == 1) {
                    $array[$key]['dcs'] += 1; 
                    $array[$key]['dcshd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infodcs'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infodcs'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['dcscc'] += 1;
                        $array[$key]['ccdcs'][$value['id']]['name'] = $value['name'];
                    } 
                }
                if ($value['status'] == 3) {
                    $array[$key]['gdh'] += 1; 
                    $array[$key]['gdhhd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infogdh'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infogdh'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['gdhcc'] += 1;
                        $array[$key]['ccgdh'][$value['id']]['name'] = $value['name'];
                    }  
                }
                if ($value['status'] == 4) {
                    $array[$key]['dhc'] += 1;
                    $array[$key]['dhchd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infodhc'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infodhc'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['dhccc'] += 1;
                        $array[$key]['ccdhc'][$value['id']]['name'] = $value['name'];
                    }  
                }
                if ($value['status'] == 5) {
                    $array[$key]['dh'] += 1;
                    $array[$key]['dhhd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infodh'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infodh'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['dhcc'] += 1;
                        $array[$key]['ccdh'][$value['id']]['name'] = $value['name'];
                    }   
                }
                if ($value['status'] == 6) {
                    $array[$key]['gd'] += 1;
                    $array[$key]['gdhd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infogd'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infogd'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['gdcc'] += 1;
                        $array[$key]['ccgd'][$value['id']]['name'] = $value['name'];
                    }  
                }
                if ($value['status'] == 10) {
                    $array[$key]['chts'] += 1;
                    $array[$key]['chtshd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infochts'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infochts'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['chtscc'] += 1;
                        $array[$key]['ccchts'][$value['id']]['name'] = $value['name'];
                    } 
                }
                if ($value['status'] == 11) {
                    $array[$key]['chtn'] += 1;
                    $array[$key]['chtnhd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infochtn'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infochtn'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['chtncc'] += 1;
                        $array[$key]['ccchtn'][$value['id']]['name'] = $value['name'];
                    }  
                }
                if ($value['status'] == 9) {
                    $array[$key]['cchts'] += 1;
                    $array[$key]['cchtshd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infocchts'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infocchts'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['cchtscc'] += 1;
                        $array[$key]['cccchts'][$value['id']]['name'] = $value['name'];
                    }  
                }
                if ($value['status'] == 7) {
                    $array[$key]['cchtn'] += 1;
                    $array[$key]['cchtnhd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infocchtn'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infocchtn'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['cchtncc'] += 1;
                        $array[$key]['cccchtn'][$value['id']]['name'] = $value['name'];
                    } 
                }
                if ($value['status'] == 8) {
                    $array[$key]['tnx'] += 1;
                    $array[$key]['tnxhd'] += $value['count'];
                    if ($value['count'] > 0) {
                        $array[$key]['infotnx'][$value['id']]['name'] = $value['name'];
                        $array[$key]['infotnx'][$value['id']]['data'] = $value['gop'];
                    }else{
                        $array[$key]['tnxcc'] += 1;
                        $array[$key]['cctnx'][$value['id']]['name'] = $value['name'];
                    }  
                }
                $array[$key]['tong'] = $array[$key]['tamdung'] + $array[$key]['dcs'] + $array[$key]['dh'] + $array[$key]['dhc'] + $array[$key]['cchtn'] + $array[$key]['gdh'] + $array[$key]['chtn'] + $array[$key]['chts'] + $array[$key]['gd'] + $array[$key]['cchts'] + $array[$key]['tnx'];
            }
        }
        $user = \Auth::user()->costcentersList();
        return $array;
        // if (\Auth::user()->hasRole('asm') || \Auth::user()->hasRole('sm') || \Auth::user()->hasRole('sales') || \Auth::user()->hasRole('Marketing')) {
        //     $a = array();
        //     if (\Auth::user()->hasRole('asm') || \Auth::user()->hasRole('sm') || \Auth::user()->hasRole('Marketing')) {
                
        //         foreach ($user as $key => $value) {
        //             if (isset($array[$value['id']])) {
        //                 array_push($a,$array[$value['id']]);
        //             }
        //         }
        //         return $a;
        //     }else{
        //         return $a;
        //     }
        // }else{
        //     return $array;
        // }
    }
    public function baocaolichgiao(Request $request){
        // dd($request->all());
        if (isset($request->t)) {
            $lich = \DB::table('calendar_t')->whereIn('id_table',$request->t)->get();
            $array = array();$a = array();$list = array();$data = array();
            foreach ($lich as $value) {
                $donhang = Order::whereBetween('start_date',[$value->start_t,$value->end_t])->orderBy('delivery_date','asc')->get();
                foreach ($donhang as $v) {
                    $l = \DB::table('calendar_t')->whereDate('start_t','<=',$v->delivery_date)->whereDate('end_t','>=',$v->delivery_date)->first();
                    if (!empty($l)) {
                        $a[$l->t]['tien'][] = $v->amount - $v->pre_pay;
                        $orders = Order::where('so_number',$v->so_number)->get();
                        $a[$l->t]['order'][] = OrderResource::collection($orders);
                        $array[$value->t][$l->t]['orders'] = $a[$l->t]['order'];
                        $array[$value->t][$l->t]['t'] = $l->t;
                        $array[$value->t][$l->t]['v'] = array_sum($a[$l->t]['tien']);
                        $list = array_values($array[$value->t]);
                        $data[$value->t] = $list;
                    }
                }
            }
        }else{
            $lich = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->get();
            $array = array();$a = array();$list = array();$data = array();
            foreach ($lich as $value) {
                $donhang = Order::whereBetween('start_date',[$value->start,$value->end])->orderBy('delivery_date','asc')->get();
                foreach ($donhang as $v) {
                    $l = \DB::table('calendar_t')->whereDate('start_t','<=',$v->delivery_date)->whereDate('end_t','>=',$v->delivery_date)->first();
                    if (!empty($l)) {
                        $a[$l->t]['thu'][] = $v->amount - $v->pre_pay;
                        $a[$l->t]['tong'][] = $v->amount;
                        $orders = Order::where('so_number',$v->so_number)->get();
                        $a[$l->t]['order'][] = OrderResource::collection($orders);
                        $array['P'.$value->p.'/Z'.$value->z][$l->t]['orders'] = $a[$l->t]['order'];
                        $array['P'.$value->p.'/Z'.$value->z][$l->t]['t'] = $l->t;
                        $array['P'.$value->p.'/Z'.$value->z][$l->t]['v'] = array_sum($a[$l->t]['thu']);
                        $array['P'.$value->p.'/Z'.$value->z][$l->t]['tong'] = array_sum($a[$l->t]['tong']);
                        $list = array_values($array['P'.$value->p.'/Z'.$value->z]);
                        $data['P'.$value->p.'/Z'.$value->z] = $list;
                    }
                }
            }
        }
        return $data;
    }
}