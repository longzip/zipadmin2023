<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Http\Controllers\Controller;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use NoiThatZip\Lead\Models\Lead;
use App\Product;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\Http\Resources\Lead as LeadResource;
use NoiThatZip\SalesArea\Models\SalesArea;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Carbon\Carbon;
use App\GiaiDoan;
use App\LogGiaiDoan;
use NoiThatZip\Kh15\Models\Kh15;
use NoiThatZip\Contact\Models\Contact;
use App\Http\Resources\khtn as ContactResource;
use NoiThatZip\Lost\Models\Lost;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;
use App\DieuKien;
use App\Customer;
use App\Order;
use App\OrderLine;
use App\Helpers\Helper;
use App\Http\Resources\OrderNew as OrderResource;
use App\Http\Resources\Order as OrderResourceNew;

class AppController extends Controller
{

    public function office(Request $request){
        $datas = Order::filter($request->all())->latest()->paginate(25);
// dd(OrderResourceNew::collection($datas));
        return view('office')->with('data', json_encode(OrderResourceNew::collection($datas)));
    }
    
    public function updateorderoff(Request $request){
     
        $data = Order::where('so_number',$request->so)->first();
    
        $data->office = 1;
        $data->save();

    }

    public function searchdh(Request $request){
        $search = [];
        $content = $request->getContent();
        $obj = json_decode($content,true);
        if($obj['sdates'] ){
            $search['sdates'][0] = $obj['sdates']['startDate'];
            $search['sdates'][1] = $obj['sdates']['endDate'];
        }else{
            $search['sdates'][0] = '2021-01-01';
            $search['sdates'][1] = '2021-12-30';
        }
        if($request->ngaygiao){
            $search['sdates'][2] = $obj['ngaygiao'];
        }else{
            $search['sdates'][2] = 0;
        }
        
        if(count($request->Sale) > 0){
            foreach($request->Sale as $id){
                $search['users'][] = $id['id']; 
            }
        }
        
        if(count($request->SR) > 0){
            foreach($request->SR as $id){
                $search['costcenter'][] = $id['id']; 
            }
        }
        
        if(count($request->KV) > 0){
            foreach($request->KV as $id){
                $search['khuvucs'][] = $id['id']; 
            }
        }
        
        if($request->SP > 0){
           $search['p'] = $obj['SP']; 
        }
        
        $leads = Order::filter($search)->latest()->paginate(1000);
        return json_encode(OrderResource::collection($leads));
     
    }
    
    public function datadh(Request $request){
        $sales = \DB::table('model_has_roles')->whereIn('role_id',[2,9])->pluck('model_id')->toArray();
        $key = array_search($request->id, $sales); 
        if(is_numeric($key)){
            $leads = Order::where('user_id',$request->id)->filter($request->all())->latest()->paginateFilter();
        }else{
            $leads = Order::filter($request->all())->latest()->paginateFilter();
        }

        return json_encode(OrderResource::collection($leads));
    }
    
    public function updateaaa(Request $request){
        // dd('ok');
        // $id = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->whereIn('costcenter_id',[8])->pluck('model_id');
// return Contact::whereBetween('start_date',['2020-08-10','2020-12-27'])->whereIn('status',[1,2,3,4,5,6,7,8,9,10,11])->whereIn('id',$id)->count();
    //   $lead = Contact::All();
    //   foreach($lead as $data){
    //       if($data['id'] > 0 && $data['id'] < 5000){
    //             $l = Lead::find($data['id']);
    //             $l->phone = substr($data['phone'], 1);
    //             $l->save();  
    //       }
          
    //   }
        // Lead::whereBetween('start_date',['2019-12-30','2020-11-29'])->where('statuskh',1)->whereIn('id',$id)->update(['statuskh' => 2]);
        // Contact::whereBetween('start_date',['2019-12-30','2020-10-04'])->whereNull('status')->whereIn('id',$id)->update(['status' => 2]);
    }
    
    public function login(Request $request){
        $content = $request->getContent();

        if (!empty($content)) {
            $obj = json_decode($content,true);
            $username = $obj['username'];
            $password = $obj['password'];
            if (\Auth::attempt(['username' => $username, 'password' => $password])) {
                $user = User::where('username',$username)->first();
                return json_encode($user->toArray());
            }else{
                return  json_encode('');
            }
        }
    }

    public function datakh15(Request $request){
        
        $sales = \DB::table('model_has_roles')->whereIn('role_id',[2,9])->pluck('model_id')->toArray();
        $key = array_search($request->id, $sales); 
        if(is_numeric($key)){
            $leads = Lead::where('user_id',$request->id)->filter($request->all())->latest()->paginate(50);
        
        }else{
            $leads = Lead::filter($request->all())->latest()->paginate(50);
            
        }

        return json_encode(LeadResource::collection($leads));
    }
    
    public function searchkh15(Request $request){
        $search = [];
        $content = $request->getContent();
        $obj = json_decode($content,true);
        if($obj['sdates'] ){
            $search['sdates'][0] = $obj['sdates']['startDate'];
            $search['sdates'][1] = $obj['sdates']['endDate'];
        }else{
            $search['sdates'][0] = '2021-01-01';
            $search['sdates'][1] = '2021-12-30';
        }
        
        if($request->nguon > 0){
            $search['nguon'] = $obj['nguon'];
        }
        
        if(count($request->Sale) > 0){
            foreach($request->Sale as $id){
                $search['users'][] = $id['id']; 
            }
        }
        
        if(count($request->SR) > 0){
            foreach($request->SR as $id){
                $search['costcenter'][] = $id['id']; 
            }
        }
        
        if(count($request->KV) > 0){
            foreach($request->KV as $id){
                $search['khuvucs'][] = $id['id']; 
            }
        }
        
        if($request->SP > 0){
           $search['P'] = $obj['SP']; 
        }
        
        $leads = Lead::filter($search)->latest()->paginateFilter();
        return json_encode(LeadResource::collection($leads));
     
    }
    
    public function searchkhtn(Request $request){
        $search = [];
        $content = $request->getContent();
        $obj = json_decode($content,true);
        if($obj['sdates'] ){
            $search['sdates'][0] = $obj['sdates']['startDate'];
            $search['sdates'][1] = $obj['sdates']['endDate'];
        }else{
            $search['sdates'][0] = '2021-01-01';
            $search['sdates'][1] = '2021-12-30';
        }
        
        if($request->nguon > 0){
            $search['nguon'] = $obj['nguon'];
        }
        
        if(count($request->Sale) > 0){
            foreach($request->Sale as $id){
                $search['users'][] = $id['id']; 
            }
        }
        
        if(count($request->SR) > 0){
            foreach($request->SR as $id){
                $search['costcenter'][] = $id['id']; 
            }
        }
        
        if(count($request->KV) > 0){
            foreach($request->KV as $id){
                $search['khuvucs'][] = $id['id']; 
            }
        }
        
        if($request->SP > 0){
           $search['P'] = $obj['SP']; 
        }
        
        $leads = Contact::filter($search)->latest()->paginateFilter();
        return json_encode(ContactResource::collection($leads));
     
    }
    
    public function datakhtn(Request $request){
        $sales = \DB::table('model_has_roles')->whereIn('role_id',[2,9])->pluck('model_id')->toArray();
        $key = array_search($request->id, $sales); 
        if(is_numeric($key)){
            $contacts = Contact::where('user_id',$request->id)->filter($request->all())->latest()->paginate(50);
        }else{
            $contacts = Contact::filter($request->all())->latest()->paginate(50);
        }

        return json_encode(ContactResource::collection($contacts));
    }
    
    public function dieukien(Request $request){
        $user = User::find($request->idnv);
        $lead = Lead::find($request->id);
        DieuKien::where('phone',$lead->phone)->delete();
        $dk = new DieuKien;
        $dk->doituong = $request->doituong;
        $dk->matbang = $request->matbang;
        $dk->diemroi = $request->diemroi;
        $dk->t = $request->t;
        $dk->ngansach = $request->ngansach;
        $dk->phone = $lead->phone;
        $dk->user_id = $user->name;
        $dk->save();
        return json_encode(0);
    }

    public function datasp()
    {
        return json_encode(Product::select('id','code as name')->get()->toArray());
    }
    
    public function diemroi()
    {
        return json_encode(\DB::table('calendar_t')->select('id_table', 't as name')->get());
    }

    public function datakv()
    {
        return json_encode(SalesArea::all()->toArray());
    }

    public function datasr(Request $request)
    {
        $sales = \DB::table('model_has_roles')->whereIn('role_id',[2,9])->pluck('model_id');
        $costcenters = [];
        $user = User::find($request->id);
        $key = array_search($request->id, $sales->toArray()); 
        if(is_numeric($key)){
            $costcenters = $user->costcentersList();
            return $costcenters;
        }else{
            return json_encode(Costcenter::select('id', 'name')->get()->toArray());
        }
    }

    public function createkh15(Request $request){
        
       // return json_encode('ok');
        $content = $request->getContent();
        $sr = array();
        if (!empty($content)) {
            $obj = json_decode($content,true);
            
            $c = Lead::where('phone',$obj['phoneNumber'])->get();
            if ($c->isNotEmpty()) {
                foreach ($c as $key => $value) {
                    $a = count($value->costcentersList()) > 0 ? $value->costcentersList()[0]['name'] : '';
                    if ($a !== '') {
                        $sr[] = $a;     
                    }
                }
            }
            if(count($sr) > 0){
                return json_encode('Số điện thoại đã bị trùng với '.implode(',',$sr));
            }else{
                $user = User::find($obj['id']);
                $lead = new Lead;
                $lead->title = $obj['chucDanh'];
                $lead->last_name = $obj['tenKH'];
                $lead->email = $obj['UserEmail'];
                $lead->phone = $obj['phoneNumber'];
                $lead->company = $obj['tenCty'];
                $lead->zalo = $obj['tenZalo'];
                $lead->address = $obj['diaChi'];
                $lead->city = $obj['thanhPho'];
                $lead->description = $obj['dacDiem'];
                $lead->start_date =  $obj['start_date'];
                $lead->start_time = $obj['start_time'];
                $lead->end_time = $obj['end_time'];
                $lead->note     = $obj['ghiChu'];
                $lead->user_id = 9074;
                $lead->save();
    
             
                // foreach ($obj['KV'] as $key => $value) {
                   
                // }
                
                if (is_numeric($obj['KV'])) {
                     $id_salesarea[] = $obj['KV'];
                    $lead->salesarea_id = json_encode($id_salesarea);
                }
                $lead->save();
                $thread = Thread::create(
                    [
                        'subject' => 'KH15#'  . $lead->id . $lead->last_name . ' được tạo bởi' . $user->name
                    ]
                );
                $lead->thread_id = $thread->id;
                $lead->save();
                //
                // $lead->syncCategories(collect($request['categories'])->pluck('id'));
                $lead->syncCostcenters([$obj['SR']]);
                //Add sản phẩm quan tâm
                collect($obj['SP'])->pluck('id')
                ->each(function($id) use ($user, $lead)
                {
                    $newProduct = Product::find($id);
                    $lead->productLine([
                        'product_id' => $newProduct->id,
                        'quantity'   => 1,
                        'price'      => $newProduct->price,
                        'amount'     => $newProduct->price
                    ], $user);
                });
                $lead->setStatus('Mới tạo');
                // Message
                    Message::create([
                        'thread_id' => $thread->id,
                        'user_id' => $user->id,
                        'body' => $user->name . ' đã thêm KH15' . $lead->last_name,
                    ]);
                // Sender
                // Sender
                Participant::create(
                    [
                        'thread_id' => $thread->id,
                        'user_id'   => $user->id,
                        'last_read' => new Carbon,
                    ]
                );
                // collect($obj['SR'])->pluck('id')->each(function($id) use ($thread){
                    $costcenter = \NoiThatZip\Costcenter\Models\Costcenter::find($obj['SR']);
                    $thread->addParticipant($costcenter->entries('\App\User')->pluck('id')->toArray());
                // });
                activity()
                ->performedOn($lead)
                ->causedBy($user)
                ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
                ->log('Đã tạo KH15 :subject.last_name, bởi :causer.name');
        
                $giaidoan = new GiaiDoan;
                $giaidoan->id_kh15 = $lead->id;
                $giaidoan->save();
        
                $gd = GiaiDoan::where('id_kh15',$lead->id)->first();
                $log = new LogGiaiDoan;
                $log->id_tt = 1;
                $log->id_giaidoan = $gd['id'];
                $log->save();
          
                return json_encode('0');
            }
            
        }else{
            return json_encode('lỗi');
        }
    }
    
    public function showkh15(Request $request)
    {
       $lead = Lead::findOrFail($request->id);
       
       try {
          Thread::find($lead->thread_id)->markAsRead($request->iduser); 
       } catch (Exception $e) {
           
       }
       $l = new LeadResource($lead);
       
       return json_encode($l);
    }
    
    public function showkhtn(Request $request)
    {
       $lead = Contact::findOrFail($request->id);
       
       try {
          Thread::find($lead->thread_id)->markAsRead($request->iduser); 
       } catch (Exception $e) {
           
       }
       $l = new ContactResource($lead);
       
       return json_encode($l);
    }
    
    public function ddkh15(Request $request)
    {
        $content = $request->getContent();
        $obj = json_decode($content,true);
        $user = User::find($obj['id']);
        $lead = Lead::findOrFail($obj['idkh']);
        $kh15 = $lead->kh15([
            'name' => $obj['name'],
            'description' => $obj['description'],
        ], $user);
        
        $kh15 = Kh15::where('kh15log_type','NoiThatZip\Lead\Models\Lead')->where('kh15log_id',$obj['idkh'])->get();
        return json_encode($kh15);
    }
    
    public function deleteddkh15(Request $request)
    {
        Kh15::find($request->id)->delete();
        
        return json_encode(0);
    }
    
    public function llkh15(Request $request)
    {
        $content = $request->getContent();
        $obj = json_decode($content,true);
        $user = User::find($obj['id']);
        $lead = Lead::findOrFail($obj['idkh']);
        $lost = $lead->lost([
            'name' => $obj['name'],
            'description' => $obj['description'],
        ], $user);

        $kh15 = Lost::where('lostlog_type','NoiThatZip\Lead\Models\Lead')->where('lostlog_id',$obj['idkh'])->get();
        return json_encode($kh15);
    }
    
    public function deletellkh15(Request $request)
    {
        Lost::find($request->id)->delete();
        
        return json_encode(0);
    }
   
    public function editkh15(Request $request){
        $content = $request->getContent();
        $obj = json_decode($content,true);
        
        $lead = Lead::findOrFail($obj['id']);
        $lead->update([
            'title' => $obj['chucDanh'],
         
            'last_name' => $obj['tenKH'],
            'email' => $obj['UserEmail'],
            'phone' => $obj['phoneNumber'],
            'company' => $obj['tenCty'],
            'address' => $obj['diaChi'],
            'zalo' => $obj['tenZalo'],
            'city' => $obj['thanhPho'],
            'description' => $obj['dacDiem'],
            'start_date' => $obj['start_date'],
            'start_time' => $obj['start_time'],
            'end_time' => $obj['end_time'],
           // 'chiendich' => empty($obj['chiendich']) ? null : $request['chiendich']['id'],
            'note'     => $obj['ghiChu'],
           // 'statuskh'     => $request['checkbox'] == true ? 2 : 1,
        ]);
    
        foreach ($obj['KV'] as $key => $value) {
            $id_salesarea[] = $value['id'];
        }
        if (count($obj['KV']) > 0) {
            $lead->salesarea_id = json_encode($id_salesarea);
            $lead->save();
        }
       
        $lead->syncCostcenters(collect($obj['SR'])->pluck('id'));
        //Gán sản phẩm quan tâm mới
        $user = User::find($lead->user_id);
        $lead->productLines()->delete();
        collect($obj['SP'])->pluck('id')
        ->each(function($id) use ($user, $lead)
        {
            $newProduct = Product::find($id);
            $lead->productLine([
                'product_id' => $id,
                'quantity'   => 1,
                'price'      => $newProduct->price,
                'amount'     => $newProduct->price
            ], $user);
        });
        $lead->setStatus('Cập nhật');
        activity()
        ->performedOn($lead)
        ->causedBy($user)
        ->withProperties(['zip' => 'editLead','id' => $lead->id])
        ->log('Cập nhật KH15 :subject.last_name, bởi :causer.name');
        return json_encode('0');
    }
    
    public function addcommentkh15(Request $request){
               
                $user = User::find($request->id);
                $lead = Lead::findOrFail($request->idkh);
                $comment = $lead->comment([
                    'title' => $user->name,
                    'body' => $request['body'],
                ], $user);
                activity()
                    ->performedOn($comment)
                    ->causedBy($user)
                    ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
                    ->log('Bình luận :subject.body, bởi :causer.name');
                    try {
                    $thread = Thread::find($lead->thread_id);
                    if ($thread) {
                        // Message
                        Message::create([
                            'thread_id' => $thread->id,
                            'user_id' => $user->id,
                            'body' => $user->name . ' đã bình luận '  . $comment->body,
                        ]);
                    }
                } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
                    // do nothing
                }
                $cmt = \DB::table('comments')->where('commentable_type','NoiThatZip\Lead\Models\Lead')->where('commentable_id',$request->idkh)->get();
                return json_encode($cmt);
           }
   
    public function getUsers(Request $request) {
        $sales = \DB::table('model_has_roles')->whereIn('role_id',[2,9])->pluck('model_id');
        
        $key = array_search($request->id, $sales->toArray()); 
        if(is_numeric($key)){
            $user = User::find($request->id);
            $user->costcentersList();
            
            $users = User::select('id', 'name')
            ->where(function ($query) use ($user){
                foreach ($user->costcentersList()->pluck('id') as $costcenter) {
                    $query->orWhereIn('id', Costcenter::find($costcenter)->entries(User::class)->pluck('id'));
                }
            })
            ->distinct()
            ->get();
            return $users;
            
        }else{
            return json_encode(User::whereIn('id',$sales)->where('date_off','2090-01-01')->get());
        }
        
    }
    
    public function taskkh15(Request $request){
        $user = User::find($request['idnv']);
        $lead = Lead::findOrFail($request['id']);
        $task = $lead->task([
            'title' => $request['title'],
            'task' => $request['task'],
            'priority' => $request['priority'],
            'duedate' => $request['duedate']
        ], $user);
        activity()
        ->performedOn($task)
        ->causedBy($user)
        ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
        ->log('Kế hoạch :subject.title, cho :causer.name');
        try {
        $thread = Thread::find($lead->thread_id);
        if ($thread) {
            // Message
            Message::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'body' => $user->name . ' đã tạo kế hoạch ' . $task->title,
            ]);
        }
        } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
            // do nothing
        }
        return new TaskResource($task);
    }
    
    public function editkhtn(Request $request){
        $content = $request->getContent();
        $obj = json_decode($content,true);
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
    
        
        $user = User::find($obj['idnv']);
        $contact = Contact::findOrFail($obj['id']);
      
        $contact->update([
            'title' => $obj['chucDanh'],
            'last_name' => $obj['tenKH'],
            'email' => $obj['UserEmail'],
            'phone' => $obj['phoneNumber'],
            'company' => $obj['tenCty'],
            'address' => $obj['diaChi'],
            'zalo' => $obj['tenZalo'],
            'city' => $obj['thanhPho'],
            'description' => $obj['dacDiem'],
            'start_date' => $obj['start_date'],
            'start_time' => $obj['start_time'],
            'end_time' => $obj['end_time'],
            'note'     => $obj['ghiChu'],
        ]);
        foreach ($obj['KV'] as $key => $value) {
            $id_salesarea[] = $value['id'];
        }
        if (count($obj['KV'])) {
            $contact->salesarea_id = json_encode($id_salesarea);
            $contact->save();
        } 
        $contact->syncCostcenters(collect($obj['SR'])->pluck('id'));
        //Gán sản phẩm quan tâm mới
        $contact->productLines()->delete();
        collect($obj['SP'])->pluck('id')
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
       
        return json_encode(0);
    }
    
    public function addImage(Request $request)
    {
        
        $user = User::find($request->id);
        $lead = Lead::findOrFail($request->idkh);
        
        if(count($request->images) > 0){
            foreach ($request->images as $key => $value) {
                // return json_encode($value['uri']);
                $duoi = $value['uri']->getClientOriginalExtension();
                $name = $value['uri']->getClientOriginalName();
                $files = str_random(2)."_".$name;
                while (file_exists("uploads/chamcong/".$files)) {
                    $files = str_random(2)."_".$name;
                }
                $file->move("uploads/chamcong",$files);
             
            }
        }else{
            $media = $lead->addMedia($request->image)->toMediaCollection('images','public');
            $image = $lead->attach([
                'src' => $media->getUrl(),
            ], $user);
        }
        return json_encode($request->images);
        
    }
   
   public function createkhtn(Request $request)
    {
        $content = $request->getContent();
        $sr = array();
        $obj = json_decode($content,true);
        $user = User::find($obj['idnv']);
        $c = Contact::where('phone',$obj['phoneNumber'])->get();
        if ($c->isNotEmpty()) {
            foreach ($c as $key => $value) {
                $a = count($value->costcentersList()) > 0 ? $value->costcentersList()[0]['name'] : '';
                if ($a !== '') {
                    $sr[] = $a;     
                }
            }
        }
        if(count($sr) > 0){
            return json_encode('Số điện thoại đã bị trùng với '.implode(',',$sr));
        }else{
            $contact = new Contact([
                'title' => $obj['chucDanh'],
               
                'last_name' => $obj['tenKH'],
                'email' => $obj['UserEmail'],
                'zalo' => $obj['tenZalo'],
                'phone' => $obj['phoneNumber'],
                'company' => $obj['tenCty'],
                'address' => $obj['diaChi'],
                'city' => $obj['thanhPho'],
                'note' => $obj['ghiChu'],
                'description' => $obj['dacDiem'],
                'start_date' => $obj['start_date'],
                'start_time' => $obj['start_time'],
                'end_time' => $obj['end_time'],
                // 'type' => $request['type'],
                // 'chiendich' => empty($request['chiendich']) ? null : $request['chiendich']['id'],
                'status'   => 1,
                'user_id' => $user->id
         
        
            ]);
            foreach ($obj['KV'] as $key => $value) {
                $id_salesarea[] = $value['id'];
            }
            if (count($obj['KV']) > 0) {
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
            $contact->syncCostcenters(collect($obj['SR'])->pluck('id'));
            collect($obj['SP'])->pluck('id')
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
            collect($obj['SR'])->pluck('id')->each(function($id) use ($thread){
                $costcenter = \NoiThatZip\Costcenter\Models\Costcenter::find($id);
                $thread->addParticipant($costcenter->entries('\App\User')->pluck('id')->toArray());
            });
            activity()
            ->performedOn($contact)
            ->causedBy($user)
            ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
            ->log('Đã tạo KHTN :subject.last_name, bởi :causer.name');

        }
                
        return json_encode(0);     
 
    }

    public function getAll()
    {
        return \DB::table('products')->select('id','name','price','code')->get()->toArray();
    }
    
    public function infobaogia(Request $request){
        $tong = \DB::table('quotes')->where('id',$request->idbg)->first();
        $ds = \DB::table('product_lines')->where('productable_id',$request->idbg)->where('productable_type','NoiThatZip\Quote\Models\Quote')->join('products','product_lines.product_id','=','products.id')->get();
        return json_encode(['tong' => $tong,'ds' => $ds,'soluong' => \DB::table('product_lines')->where('productable_id',$request->idbg)->where('productable_type','NoiThatZip\Quote\Models\Quote')->sum('quantity')]);
    }
    
    public function taodonhang(Request $request){
        // $v = validator(request()->all(),[
        //     'so_number' => 'required',
        //     'phone' => 'required|string|max:10|min:10',
        //     'name' => 'required',
        //     'address' => 'required',
        //     'pre_pay' => 'required|numeric',
        //     'delivery' => 'required',
        //     'qgg' => 'required',
        //     'fee_vc' => 'required',
        //     'fee_ld' => 'required',
        //     'costcenter_id' => 'required',
        //     'soType' => 'required',
        //     'warehouse_id' => 'required'
        // ],
        // [
        //     'so_number.required'=>'Số đơn hàng',
        //     'phone.required'=>'Số điện thoại',
        //     'name.required'=>'Tên khách hàng',
        //     'address.required'=>'Địa chỉ khách hàng',
        //     'pre_pay.required'=>'Tiền đặt cọc',
        //     'delivery.required'=>'Ngày giao hàng',
        //     'costcenter_id.required'=>'Showroom',
        //     'warehouse_id.required'=>'Chọn tỉnh giao hàng',
        //     'soType.required'=>'Chọn loại đơn hàng'
        // ]);

        // if($v->fails())
        // {
        //     return response(array(
        //         'success' => false,
        //         'data' => request()->all(),
        //         'message' => $v->errors()
        //     ),400,[]);
        // }

        $user = User::find($request['user_id']);
        $userId = $user->id;
        $costcenter = Costcenter::where('name',request('costcenter_id'))->first();
        $customer = Customer::UpdateOrCreate([
            'phone' => request('phone')
        ]);
        $customer->name = request('name');
        $customer->address_line1 = request('address');
        $customer->save();
        $so = new Order;
        $so->so_number = request('so_number');
       

        // if ($so->status_id == 2) {
        //     return response(array(
        //         'success' => false,
        //         'data' => [],
        //         'message' => [ 'error' => 'Đơn hàng đã duyệt' ]
        //     ),400,[]);
        // }
        $so->type = request('soType');
        $so->description = request('soType').'_'.request('so_number');
        $so->your_ref    = request('so_number');
        $so->resource    = $userId;
        $so->ordered_by  = $customer->id;
        $so->deliver_to  = $customer->id;
        $so->invoice_to  = $customer->id;
        // $so->warehouse_id  = $request['warehouse_id']; // add 8/12
        $so->warehouse    = \NoiThatZip\Warehouse\Models\Warehouse::where('city',request('warehouse_id'))->first()->code;
        // return json_encode(request('delivery'));
        $so->delivery_method = 'DB';
        $so->delivery_date = request('delivery')['currentDate'];
        $so->start_date = request('start_date')['currentDate'];
        
        $so->costcenter = $costcenter->code;
        $so->user_id = $userId;
        $so->pre_pay = request('pre_pay');
        // $so->journal_id = request('journal_id');
        $so->qgg = request('qgg');
        $so->fee_vc = request('fee_vc');
        $so->fee_ld = request('fee_ld');
        $so->amount = request('sumtong');
        $so->vat = request('vat');
        $so->subtotal = request('sumtien');
        
        //$so->type = request('soType');
        $so->note = request('note');
        // $so->trangthai = request('trangthai');
        $so->status_id = 2;
        $so->save();
        $so->syncCostcenters([$costcenter->id]);
        $items = request('ds');
        \DB::table('order_lines')->where('order_id',  $so->id)->delete();
        foreach ($items as $item) {
            $product = Product::where('code',$item['code'])->first();

            $orderline = OrderLine::UpdateOrCreate([
                'product_id' =>$product->id,
                'item' => $product->code,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'discount' => $item['discount'],
                'amount' => $item['amount'],
                'order_id' => $so->id,
                'point' => $product->point,
                'delivery' => request('delivery')['currentDate'],
            ]);
        }
        Helper::orderToXml($so->id);
        activity()
        ->performedOn($so)
        ->causedBy($user)
        ->withProperties(['zip' => 'saleOrder','id' => $so->so_number])
        ->log('Tạo đơn hàng :subject.so_number, bởi :causer.name');
        return json_encode('0');
    }
}