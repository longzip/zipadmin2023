<?php
namespace NoiThatZip\Lead\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use NoiThatZip\Lead\Models\Lead;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Auth;
use App\User;
use App\Product;
use Carbon\Carbon;
use DB;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\Http\Resources\Lead as LeadResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;
use App\GiaiDoan;
use App\LogGiaiDoan;

class LeadsController extends Controller
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
        //         Lead::all()->each(function($lead){
        //         $thread = Thread::where('subject','like', 'KH15#' . $lead->id . '%')->first();
        //         if ($thread) {
        //             Message::create([
        //                 'thread_id' => $thread->id,
        //                 'user_id' => $lead->user_id,
        //                 'body' => $lead->user_id . ' tao KH15' . $lead->name,
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

        // $data = DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->where('costcenter_id',25)->pluck('model_id');
        // Lead::whereIn('id',$data)->update(['chuyenchodiem' => 1]);

            // $leads = Lead::where('user_id',9134)->whereIn('phone',['0962257962','0913208054','0904900923','0949832496','0353336688','0913510875','0902209386','0936182281','0988888056','0903415650','0943283838','0904041118','0948699055','0988722885','0913365438','0966666666','0911958698','0933325999','0949202166','0982286215','0987169121','0936679579','0975086388','0906044079','0965282827','0915373892','0988692828','0988881212','0986727506','0976543211','0969566566','0946201168','0903403365','0932287722','0933283663','0973779778','0917709709','0983055142','0914623851','0913611623','0913118449','0933344672','0362781190','0949804888','0907591666','0904305115','0909802998','0984267975','0904179486','0966668666','0979740679','0916868988','0979591347','0906868338','0986242286','0966193371','0972779567','0975769616','0979017899','0983300795','0996900002','0909791177','0979878888','0988522367','0987136666','0976381322','0974004483','0976813364','0989844312','0982565688','0962473579','0989204530','0902063968','0915269994','0912982925','0886778888','0988521207','0374093643','0912389609','0868059468','0968981643','0915575565','1123456780','0903401586','0978980828','0963655512','0913205923','1234567899','0936401233','0963290266','0396250184','0946306993','0326689332','0912917017','0986730298','0963873573','0988415102','0927292386','0888011234','0963655512','0987462918','0984305565','0346813983','0969221704','0988475487','0988475487','0988745921','0989306282','0904110516','0989306282','0989983164','0969656305','0977928799','0942191171','0868633222','0989268698','0886149968','0886659274','0986589533','0982762982','0969656305','0977423291','0902079747','0982121228','0972449569','0982218540','0946205668','0904393580','0946778194','0906286822','0979144122','0979922846','0989081096','0912948808','0944848783','0375713175','0911636086','0912181111'])->update(['type' => 2]);
            $leads = Lead::filter($request->all())->latest()->paginateFilter();
            return LeadResource::collection($leads);
        }else{
            $leads = Lead::filter($request->all())->whereIn('user_id',[269,9318,9320,9357])->latest()->paginateFilter();
            return LeadResource::collection($leads);
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

    public function storeLost(Request $request, $id){
        $user = auth('api')->user();
        $lead = Lead::findOrFail($id);
        $lost = $lead->lost([
            'name' => $request['name'],
            'description' => $request['description'],
        ], $user);
        return $lost;
    }

    public function storeattachmens(Request $request,$id)
    {
        $user = auth('api')->user();
        $contact = Lead::findOrFail($id);
        // return $request->file('file');

        $media = $contact->addMedia($request->file('file'))
        ->withCustomProperties(['uploadBy' => $user->name . ' - ' . $user->id])
        ->toMediaCollection();
        activity()
        ->performedOn($contact)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkh15','id' => $contact->id])
        ->log('Gửi tài liệu :subject.last_name, bởi :causer.name');
        return $media->getUrl();
    }

    public function storeVideo(Request $request, $id){
        $user = auth('api')->user();
        $contact = Lead::findOrFail($id);
        $video = $contact->video([
            'title' => $request['title'],
        ], $user);
        activity()
        ->performedOn($contact)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkh15','id' => $contact->id])
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

    public function storeQuote(Request $request, $id)
    {
        $request->validate([
            'productLines' => ['required'],
        ]);
        $user = auth('api')->user();
        $contact = Lead::findOrFail($id);
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
        ->withProperties(['zip' => 'ttKH15','id' => $contact->id])
        ->log(':subject.subject, bởi :causer.name');
        return ['message' => "Thành công!"];
    }

    public function storeImage(Request $request,$id)
    {
        // dd($request->file('files'));
        $user = auth('api')->user();
        $lead = Lead::findOrFail($id);
        foreach ($request->file('files') as $key => $value) {
            $media = $lead->addMedia($value)
            ->toMediaCollection('images','public');
            $image = $lead->attach([
                'src' => $media->getUrl(),
            ], $user);
            // $tailieu = AttachmentResource($image);
        }
        return ['message' => 'Thành công!'];
    }

    public function storeKh15(Request $request, $id){
        $user = auth('api')->user();
        $lead = Lead::findOrFail($id);
        $kh15 = $lead->kh15([
            'name' => $request['name'],
            'description' => $request['description'],
        ], $user);
        return $kh15;
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $sr = array();
        $c = Lead::where('phone',$request->phone)->get();
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
            'phone' => 'unique:leads',
        ],[
            'phone.unique' => 'Số Đã Trùng! Liên Hệ IT hoặc '.implode(',',$sr).' để thương lượng',
        ]);
        
        $user = auth('api')->user();
        $lead = new Lead([
           'title' => $request['title'],
           'first_name' => $request['first_name'],
           'last_name' => $request['last_name'],
           'email' => $request['email'],
           'phone' => $request['phone'],
           'company' => $request['company'],
           'zalo' => $request['zalo'],
           'address' => $request['address'],
           'city' => $request['city'],
           'description' => $request['description'],
           'start_date' => $request['start_date'],
           'start_time' => $request['start_time'],
           'end_time' => $request['end_time'],
           'note'     => $request['note'],
           'user_id' => $user->id
       ]);
        foreach ($request['salesarea'] as $key => $value) {
            $id_salesarea[] = $value['id'];
        }
        // dd(json_encode($id_salesarea));
        if ($request->has('salesarea')) {
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
        $lead->syncCostcenters(collect($request['costcenters'])->pluck('id'));
        //Add sản phẩm quan tâm
        collect($request['products'])->pluck('id')
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
        collect($request['costcenters'])->pluck('id')->each(function($id) use ($thread){
            $costcenter = \NoiThatZip\Costcenter\Models\Costcenter::find($id);
            $thread->addParticipant($costcenter->entries('\App\User')->pluck('id')->toArray());
        });
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
        return $lead;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $lead = Lead::findOrFail($id);
       $user = auth('api')->user();
       try {
          Thread::find($lead->thread_id)->markAsRead($user->id); 
       } catch (Exception $e) {
           
       }
       
       return new LeadResource($lead);
   }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        // dd($request->all());
        $request->validate([
            'last_name' => ['required', 'string', 'max:255']
        ]);
        $lead = Lead::findOrFail($id);
        $lead->update([
            'title' => $request['title'],
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'company' => $request['company'],
            'address' => $request['address'],
            'zalo' => $request['zalo'],
            'city' => $request['city'],
            'description' => $request['description'],
            'start_date' => $request['start_date'],
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
            'chiendich' => empty($request['chiendich']) ? null : $request['chiendich']['id'],
            'note'     => $request['note'],
            'statuskh'     => $request['checkbox'] == true ? 2 : 1,
        ]);

        foreach ($request['salesarea'] as $key => $value) {
            $id_salesarea[] = $value['id'];
        }
        if ($request->has('salesarea')) {
            $lead->salesarea_id = json_encode($id_salesarea);
            $lead->save();
        }
        // $lead->syncCategories(collect($request['categories'])->pluck('id'));
        $lead->syncCostcenters(collect($request['costcenters'])->pluck('id'));
        //Gán sản phẩm quan tâm mới
        $user = auth('api')->user();
        $lead->productLines()->delete();
        collect($request['products'])->pluck('id')
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
        return $lead;
        return ['message' => 'Updated the lead info'];
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
        if($user->can('delete leads')){
            $lead = Lead::findOrFail($id);
            $lead->delete();
            activity()
            ->performedOn($lead)
            ->causedBy($user)
            ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
            ->log('Xóa KH15 :subject.last_name, bởi :causer.name');
            return ['message' => 'Lead Deleted'];
        }
        else{
            return response()->toJson([
                'message' => 'Bạn không có quyền xóa',
            ], 404);
        }
    }
    public function storeactivity(Request $request, $id){
        $user = auth('api')->user();
        $lead = Lead::find($id);
        $activity = $lead->activity(
            $request->all(),$user);
        activity()
        ->performedOn($activity)
        ->causedBy($user)
        ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
        ->log('Hoạt động :subject.subject, bởi :causer.name');
        try {
        $thread = Thread::find($lead->thread_id);
        if ($thread) {
            // Message
            Message::create([
                'thread_id' => $thread->id,
                'user_id' => $user->id,
                'body' => $user->name . ' đã tạo hoạt động ' . $activity->subject,
            ]);
        }
    } catch (ModelNotFoundException $e) { // @codeCoverageIgnore
        // do nothing
    }
        return $activity;
    }
    public function activities($id){
        $items = [];
        $lead = Lead::find($id);
        $lead->activities->each(function($item) use (&$items){
            $item['user_name'] = User::find($item->creator_id)->name;
            $items[] = $item;
        });
        return $items;
    }
    //Comments
    public function storecomment(Request $request, $id){
        $user = auth('api')->user();
        $lead = Lead::findOrFail($id);
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
        return $comment;
    }
    public function comments($id){
        $lead = Lead::findOrFail($id);
        return $lead->comments;
    }
    //Task
    public function storetask(Request $request, $id){
        $user = User::find($request['user_id']);
        $lead = Lead::findOrFail($id);
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
    public function tasks($id)
    {
        $lead = Lead::findOrFail($id);
        return TaskResource::collection($lead->tasks);
        // $lead->tasks->each(function($item) use (&$items){
        //     $item['user_name'] = User::find($item->creator_id)->name;
        //     $items[] = $item;
        // });
        // return $items;
    }
    public function countByDate(Request $request){
        $leads = Lead::filter($request->all())->get();
        $counted = $leads->countBy(function($lead){
           return $lead->start_date;
        });
        $sum = $counted->sum();
        return [$sum,$counted,'K15'];
    }
    public function countByCoscenter(Request $request){
        $leads = Lead::filter($request->all())->get();
        $counted = $leads->countBy(function($lead){
           $costcenter = $lead->costcenters()->first();
           if (!$costcenter) {
               return 'Khac';
           }
           return $costcenter->id;
        });
        $namesp = $request->p;
        $costcenter = Costcenter::where('closed','>',$request->sdates[0])->get();
        $costcenters = $costcenter->map(function($costcenter) use ($counted,$namesp,$leads){
        if($namesp != null){
            foreach ($leads as $value) {
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

                // dd($count_mang);
                    return [
                        'name'=> $costcenter->name, //chi nhánh
                        'count' => isset($counted[$costcenter->id])? $counted[$costcenter->id] : 0 ,//đếm id chi nhánh
                        'product' =>isset($count[$costcenter->name]) ? array_sum($count[$costcenter->name]) : 0,
                        ];
                }

            else {
                    return [
                        'name'=> $costcenter->name, //chi nhánh
                        'count' => isset($counted[$costcenter->id])? $counted[$costcenter->id] : 0 ,
                    ];
                }
        });
        return $costcenters->sortByDesc('count')->values()->take(150);
    }
    public function countBySale(Request $request){
        $leads = Lead::filter($request->all())->get();
        $counted = $leads->countBy(function($lead){
           return $lead->user_id;
        });
        $namesp = $request->p;
        $sales = $this->sales($request->sdates)->map(function($user) use ($counted,$leads,$namesp){
            $sr = $user->costcentersList();
         if($namesp != null){
            foreach ($leads as $value) {
                    $check = $value->productLines()->pluck('product_id')->toArray();
                    $pg_gr = Product::where('groups',$namesp)->whereIn('id',$check)->count();
                    $count[$value->user_id][]=isset($pg_gr) ? $pg_gr : 0;
                    // $tet = $check;
                    // dd($check);

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

        return $sales->sortByDesc('count')->values()->all();
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
                't0' => isset($targets[$weeks[0]]) ? (int)$targets[$weeks[0]]->sum('kh15_number') : 0,
                't1' => isset($targets[$weeks[1]]) ? (int)$targets[$weeks[1]]->sum('kh15_number') : 0,
                't2' => isset($targets[$weeks[2]]) ? (int)$targets[$weeks[2]]->sum('kh15_number') : 0,
                't3' => isset($targets[$weeks[3]]) ? (int)$targets[$weeks[3]]->sum('kh15_number') : 0,
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
                't0' => isset($targets[$weeks[0]]) ? (int)$targets[$weeks[0]]->sum('kh15_number') : 0,
                't1' => isset($targets[$weeks[1]]) ? (int)$targets[$weeks[1]]->sum('kh15_number') : 0,
                't2' => isset($targets[$weeks[2]]) ? (int)$targets[$weeks[2]]->sum('kh15_number') : 0,
                't3' => isset($targets[$weeks[3]]) ? (int)$targets[$weeks[3]]->sum('kh15_number') : 0,
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

    protected function contacts2Target($dates){
        $contacts = Lead::filter($dates)->get();
        $contacts = $this->groupBySale($contacts);
        $contacts = $contacts->map(function($contacts){
            return $this->groupByWeek($contacts)->map(function($contacts){
                return $this->countByDay($contacts);
            });
        });
        return $contacts;
    }

    protected function contacts2Target2($dates){
        $contacts = Lead::filter($dates)->get();
        $contacts = $this->groupByCostcenter($contacts);
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