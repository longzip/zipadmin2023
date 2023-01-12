<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NoiThatZip\Lead\Models\Lead;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Auth;
use App\User;
use App\DieuKien;
use App\Product;
use App\DiemRoi;
use Carbon\Carbon;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use App\Http\Resources\Lead as LeadResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;

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
     if (!$user->can('import')) {
        if (!$request->has('users')) {
            $request['users'] = [$user->id];
        }
    }

    $leads = Lead::filter($request->all())->latest()->paginateFilter();
    return LeadResource::collection($leads);
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
        $this->validate($request,[
            'last_name' => ['required', 'string', 'max:255'],
            'salesarea' => 'required'
        ]);
        $user = auth('api')->user();
        $lead = Lead::Create([
         'title' => $request['title'],
         'first_name' => $request['first_name'],
         'last_name' => $request['last_name'],
         'email' => $request['email'],
         'phone' => $request['phone'],
         'company' => $request['company'],
         'address' => $request['address'],
         'city' => $request['city'],
         'description' => $request['description'],
         'start_date' => $request['start_date'],
         'start_time' => $request['start_time'],
         'end_time' => $request['end_time'],
         'note'     => $request['note'],
         'user_id' => $user->id
     ]);
        if ($request->has('salesarea')) {
            $lead->salesarea_id = $request['salesarea']['id'];
            $lead->save();
        }
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
        $thread = Thread::create(
            [
                'subject' => 'KH15#'  . $lead->id . '#' .  $lead->last_name . ' được tạo bởi' . $user->name
            ]
        );
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
        //return $request;
        $this->validate($request,[
            'last_name' => ['required', 'string', 'max:255'],
            'salesarea' => 'required'
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
            'city' => $request['city'],
            'description' => $request['description'],
            'start_date' => $request['start_date'],
            'start_time' => $request['start_time'],
            'end_time' => $request['end_time'],
            'note'     => $request['note']
        ]);
        if ($request->has('salesarea')) {
            $lead->salesarea_id = $request['salesarea']['id'];
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

    public function convertKh15($id){
        $user = auth('api')->user();
        $lead = Lead::find($id);
        activity()
        ->performedOn($lead)
        ->causedBy($user)
        ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
        ->log('Chuyển KH15 :subject.last_name, bởi :causer.name');
        return $lead;
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
        return $task;
    }

    public function tasks($id)
    {
        $lead = Lead::findOrFail($id);
        return TaskResource::collection($lead->tasks);
    }

    public function user($id){
        $user_id = Lead::find($id)->user_id;
        return User::select('id','name')->where('id',$user_id)->first();
    }

    public function adddk(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'doituong' => 'required',
            'matbang' => 'required',
            'diemroi' => 'required',
            'ngansach' => 'required',
        ]);
        DieuKien::where('phone',$request->phone)->delete();
        $dk = new DieuKien;
        $dk->doituong = $request->doituong;
        $dk->matbang = $request->matbang;
        $dk->diemroi = $request->diemroi;
        $dk->t = $request->t;
        $dk->ngansach = $request->ngansach;
        $dk->phone = $request->phone;
        $dk->user_id = \Auth::user()->name;
        $dk->save();
        if($request->hasFile('file')){
            $user = auth('api')->user();
            foreach ($request->file('file') as $key => $value) {
                $media = $dk->addMedia($value)
                ->toMediaCollection('images','public');
                $image = $dk->attach([
                    'src' => $media->getUrl(),
                ], $user);
            }
        }
        $dt = Carbon::now('Asia/Ho_Chi_Minh');

        if(!empty($request->idkhtn)){
            $get_t = \DB::table('calendar_t')->where('start_t','<=',$dt->toDateString())->where('end_t','>=',$dt->toDateString())->first();
            $get_t = json_decode(json_encode($get_t), true) ;
            $where_check = DiemRoi::where('id_khtn',$request->idkhtn)->where('id_calendar_t',$get_t['id_table'])->get();
            if($where_check->isEmpty()){
                if(!empty($get_t)){
                    $delete = DiemRoi::where('id_calendar_t','>',$get_t['id_table'])->delete();
                }
                $dr = new DiemRoi;
                $dr->id_khtn = $request->idkhtn;
                $dr->id_calendar_t = $request->diemroi;
                $dr->save();
            }else{
                return response(array(
                    'success' => false,
                    'data' => [],
                    'message' => 'Điểm Rơi Đã Chọn'
                ),400,[]);
            }
        }
    }

    public function updateProgramLead(Request $request)
    {
        $user = auth('api')->user();
        if ($user->id == 269) {
            Lead::where('id',$request->id)->update(['program' => 1]);
        }
    }

    public function blackfriday(Request $request)
    {
        $lead = Lead::where('program',1)->latest()->paginateFilter();
        return LeadResource::collection($lead);
    }
}
