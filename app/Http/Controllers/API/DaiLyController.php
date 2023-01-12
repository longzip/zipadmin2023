<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cmgmyr\Messenger\Models\Thread;
use App\Product;
use App\DaiLy;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use BrianFaust\Categories\Models\Category;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use App\Http\Resources\DaiLy as DaiLyResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;

class DaiLyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $daily = DaiLy::filter($request->all())->latest()->paginateFilter();
        return DaiLyResource::collection($daily);
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
        $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => 'required|min:10|max:10',
            'address' => 'required',
        ],
        [
            'last_name.required'=>'Bạn chưa nhập tên khách hàng',
            'phone.required'=>'Bạn chưa nhập số điện thoại',
            'address.required'=>'Bạn chưa nhập địa chỉ',
            'phone.unique' => 'Số Đã Trùng! Ra danh sách xem có khách chưa? Nếu không có liên hệ IT',
            'phone.min'=>'Số Điện Thoại Phải Có 10 Số',
            'phone.max'=>'Số Điện Thoại Phải Có 10 Số',
        ]);
        $user = auth('api')->user();
        $daily = new DaiLy([
            'title' => $request['title'],
            'name' => $request['name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'dientich' => $request['dientich'],
            'address' => $request['address'],
            'city' => $request['city'],
            'note' => $request['note'],
            'mota' => $request['mota'],
            'description' => $request['description'],
            'start_date' => $request['start_date'],
            'user_id' => $user->id
        ]);
        $daily->save();
        //gán trạng thái mới tạo cho khách hàng tiềm năng
        $thread = Thread::create(
            [
                'subject' => 'DaiLy#' . $daily->id . '#' . $daily->last_name . ' được tạo bởi' . $user->name,
            ]
        );
        $daily->thread_id = $thread->id;
        $daily->save();
        collect($request['products'])->pluck('id')
        ->each(function($id) use ($user, $daily)
        {
            $newProduct = Product::find($id);
            $daily->productLine([
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
            'body' => $user->name . 'tao DaiLy' . $daily->name,
        ]);
        // Sender
        Participant::create(
            [
                'thread_id' => $thread->id,
                'user_id'   => $user->id,
                'last_read' => new Carbon,
            ]
        );
        activity()
        ->performedOn($daily)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachdaily','id' => $daily->id])
        ->log('Đã tạo DaiLy :subject.last_name, bởi :causer.name');

        return $daily;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $daily = DaiLy::findOrFail($id);
        $user = auth('api')->user();
        Thread::find($daily->thread_id)->markAsRead($user->id);
        return new DaiLyResource($daily);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $daily = DaiLy::find($id);
        return $daily;
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
        ]);
        $user = auth('api')->user();
        $daily = DaiLy::findOrFail($id);
      
        if ($user->id != $daily->user_id) {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Bạn không có quyền sửa'
            ),400,[]);
        }
        $daily->update([
            'title' => $request['title'],
            'last_name' => $request['last_name'],
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'dientich' => $request['dientich'],
            'address' => $request['address'],
            'mota' => $request['mota'],
            'city' => $request['city'],
            'note' => $request['note'],
            'description' => $request['description'],
            'start_date' => $request['start_date'],
            // 'start_time' => $request['start_time'],
            // 'end_time' => $request['end_time']
        ]);
       
        //Gán sản phẩm quan tâm mới
        $daily->productLines()->delete();
        collect($request['products'])->pluck('id')
        ->each(function($id) use ($user, $daily)
        {
            $newProduct = Product::find($id);
            $daily->productLine([
                'product_id' => $newProduct->id,
                'quantity'   => 1,
                'price'      => $newProduct->price,
                'amount'     => $newProduct->price
            ], $user);
        });
        activity()
        ->performedOn($daily)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachtiemnang','id' => $daily->id])
        ->log('Cập nhật KHTN :subject.last_name, bởi :causer.name');

        return new DaiLyResource($daily);
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
        //
    }

    public function storeQuote(Request $request, $id)
    {
        $request->validate([
            'productLines' => ['required'],
        ]);
        $user = auth('api')->user();
        $daily = DaiLy::findOrFail($id);
        $quote = $daily->quote([
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
        ->withProperties(['zip' => 'ttkhachdaily','id' => $daily->id])
        ->log(':subject.subject, bởi :causer.name');
        return ['message' => "Thành công!"];
    }

    public function storeKh15(Request $request, $id){
        $user = auth('api')->user();
        $daily = DaiLy::findOrFail($id);
        $kh15 = $daily->kh15([
            'name' => $request['name'],
            'description' => $request['description'],
        ], $user);
        return $kh15;
    }

    public function storeLost(Request $request, $id){
        $user = auth('api')->user();
        $daily = DaiLy::findOrFail($id);
        $lost = $daily->lost([
            'name' => $request['name'],
            'description' => $request['description'],
        ], $user);
        return $lost;
    }

    public function storetask(Request $request, $id){
        $user = User::find($request['user_id']);
        $daily = DaiLy::findOrFail($id);
        $task = $daily->task([
            'title' => $request['title'],
            'task' => $request['task'],
            'priority' => $request['priority'],
            'duedate' => $request['duedate']
        ], $user);
        activity()
        ->performedOn($task)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachdaily','id' => $daily->id])
        ->log('Kế hoạch :subject.title, bởi :causer.name');
        return new TaskResource($task);
    }

    public function storecomment(Request $request, $id){
        $user = auth('api')->user();
        $daily = DaiLy::findOrFail($id);
        $comment = $daily->comment([
            'title' => $user->name,
            'body' => $request['body'],
        ], $user);
        activity()
        ->performedOn($comment)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachdaily','id' => $daily->id])
        ->log('Bình luận :subject.body, bởi :causer.name');
        try {
            $thread = Thread::find($daily->thread_id);
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

    public function storeVideo(Request $request, $id){
        $user = auth('api')->user();
        $daily = DaiLy::findOrFail($id);
        $video = $daily->video([
            'title' => $request['title'],
        ], $user);
        activity()
        ->performedOn($daily)
        ->causedBy($user)
        ->withProperties(['zip' => 'ttkhachdaily','id' => $daily->id])
        ->log('Video :subject.title, bởi :causer.name');
         try {
            $thread = Thread::find($daily->thread_id);
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
        $user = auth('api')->user();
        $daily = DaiLy::findOrFail($id);
        foreach ($request->file('files') as $key => $value) {
            $media = $daily->addMedia($value)
            ->toMediaCollection('images','public');
            $image = $daily->attach([
                'src' => $media->getUrl(),
            ], $user);
        }
        return ['message' => 'Thành công!'];
    }
}
