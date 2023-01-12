<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Project;
use App\Company;
use App\Checkduan;
use App\ActivityLogs;
use NoiThatZip\Quocte\Models\Quocte;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use BrianFaust\Categories\Models\Category;
use App\Product;
use App\Http\Resources\Project as ProjectResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use App\Exports\ProjectExport;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
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
	    $contacts = Project::filter($request->all())->latest()->paginateFilter();
	    return ProjectResource::collection($contacts);
	}


	public function read(Request $request)
	{
        $id = \Auth::user()->id;
        $check = Checkduan::firstOrCreate(['user_id' => $id]);
        if($check->read == '') {
            $check->read = '[]';
            $check->save();
        }
	    $pushid = ActivityLogs::where('log_name',$request->id)->pluck('id');
	    // dd(json_encode($pushid));
	    $goc = json_decode($check->read);
	    $ss =  json_decode($pushid);
	    $diff = array_diff($ss , $goc);
	    if($diff != array()) {
	    	$merged = array_merge($diff,$goc);
	    }
	    $check->read = json_encode($merged);
        $check->save();
        // dd($diff);
	}
	// public function searchContactbyShowroom(Request $request)
	// {
	//     return $request;
	//     $user = auth('api')->user();
	//     $contacts = null;
	//     if ($user->hasRole('qa')) {
	//         $contacts = Contact::latest()->paginate(3);
	//     } else {
	//         $contacts = Contact::latest()->where('user_id',$user->id)->paginate(25);
	//     }
	//     $contacts->map(function($contact){
	//         $contact->note = $contact->status;
	//         $contact->user_id = User::find($contact->user_id)->name;
	//     });
	//     return $contacts;
	// }

	public function create()
	{
	//
	}

	public function store(Request $request)
	{
	    $request->validate([
	        'name_project' => 'required',
	        'company' => 'required',
	        'last_name1' => 'required',
	        'phone1' => 'required|min:10|max:10',
	        'address1' => 'required',
	        'email1' => 'required'
        ],
        [
            'name_project.required'=>'Bạn chưa nhập tên dự án',
            'company.required'=>'Bạn chưa nhập tên công ty',
            'last_name1.required'=>'Bạn chưa nhập tên',
            'phone1.required'=>'Bạn chưa nhập số điện thoại',
            'address1.required'=>'Bạn chưa nhập địa chỉ',
            'email1.required'=>'Bạn chưa nhập mail. Nếu chưa có thông tin hãy điền mail của bạn.',
			'phone1.min'=>'Số Điện Thoại Phải Có 10 Số',
			'phone1.max'=>'Số Điện Thoại Phải Có 10 Số',
        ]);
	    $company = new Company([
	        'name_company' => $request['company'],
	        'sapo' => $request['descompany'],
	        'name_one' => $request['last_name1'],
	    	'chucdanh_one' =>$request['chucdanh1'],
	        'phone_one' => $request['phone1'],
	        'address_one' => $request['address1'],
	        'email_one' => $request['email1'],

	        'name_two' => $request['last_name2'],
	    	'chucdanh_two' =>$request['chucdanh2'],
	        'phone_two' => $request['phone2'],
	        'address_two' => $request['address2'],
	        'email_two' => $request['email2'],
	    ]);	
	    $company->save();

	    $id = Company::latest()->first();

	    $user = auth('api')->user();
	    $contact = new Project([
	        'company_id' => $id->id,
			'sapo_project'  => $request['sapo_project'],
			'name_project'  => $request['name_project'],
	        'city' => $request['city'],
	        'note' => $request['note'],
	        'description' => $request['description'],
	        'start_date' => $request['start_date'],
	        'start_time' => $request['start_time'],
	        'end_time' => $request['end_time'],
	        'status'   => $request['status'],
	        'user_id' => $user->id
	    ]);
	    if ($request->has('salesarea')) {
	        $contact->salesarea_id = $request['salesarea']['id'];
	    }
	    $contact->save();
	    //gán trạng thái mới tạo cho khách hàng tiềm năng
	    $thread = Thread::create(
	        [
	            'subject' => 'KHDA#' . $contact->id . '#' . $contact->last_name . ' được tạo bởi' . $user->name,
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
	                'body' => $user->name . 'tao KHDA' . $contact->name,
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
	    activity('create')
	    ->performedOn($contact)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachtiemnang','id' => $contact->id])
	    ->log('Đã tạo KHDA :subject.last_name, bởi :causer.name');
	    return $contact;
	}

	public function show($id)
	{
	    $contact = Project::findOrFail($id);
	    $user = auth('api')->user();
	    Thread::find($contact->thread_id)->markAsRead($user->id);
	    return new ProjectResource($contact);
	}

	public function edit($id)
	{
	    $contact = Project::find($id);
	    return $contact->with('costcenters');
	}

    public function setproject($id)
    {
        request()->session()->put('contact_id', $id);
        return $id;
    }

    public function update(Request $request, $id)
	{
	    $request->validate([
	        'name_project' => 'required',
	        'company.name_company' => 'required',
	        'company.name_one' => 'required',
	        'company.phone_one' => 'required|min:10|max:10',
	        'company.address_one' => 'required'
        ],
        [
            'name_project.required'=>'Bạn chưa nhập tên dự án',
            'company.name_company.required'=>'Bạn chưa nhập tên công ty',
            'company.name_one.required'=>'Bạn chưa nhập tên',
            'company.phone_one.required'=>'Bạn chưa nhập số điện thoại',
            'company.address_one.required'=>'Bạn chưa nhập địa chỉ',
			'company.phone_one.min'=>'Số Điện Thoại Phải Có 10 Số',
			'company.phone_one.max'=>'Số Điện Thoại Phải Có 10 Số',
        ]);

	    $user = auth('api')->user();
	 // dd($user->id);
	    $contact = Project::findOrFail($id);

	    if ($user->id != $contact->user_id) {
			return response(array(
		        'success' => false,
		        'data' => [],
		        'message' => 'Bạn không có quyền sửa'
		    ),400,[]);
		}
		$idComapny = $contact->company_id;
	    $company =  Company::findOrFail($idComapny);
        $company->name_company = $request['company']['name_company'];
        $company->sapo = $request['company']['sapo'];
        $company->name_one = $request['company']['name_one'];
    	$company->chucdanh_one = $request['company']['chucdanh_one'];
        $company->phone_one = $request['company']['phone_one'];
        $company->address_one = $request['company']['address_one'];
        $company->email_one = $request['company']['email_one'];

        $company->name_two =  $request['company']['name_two'];
        $company->chucdanh_two = $request['company']['chucdanh_two'];
        $company->phone_two =  $request['company']['phone_two'];
        $company->address_two =  $request['company']['address_two'];
        $company->email_two =  $request['company']['email_two'];
	    $company->save();

		$contact->update([
			'sapo_project'  => $request['sapo_project'],
			'name_project'  => $request['name_project'],
		    'city' => $request['city'],
		    'note' => $request['note'],
		    'status'   => $request['status'],
		    'description' => $request['description'],
		    'start_date' => $request['start_date'],
		    'start_time' => $request['start_time'],
		    'end_time' => $request['end_time']
		]);
		if ($request->has('salesarea')) {
		    $contact->salesarea_id = $request['salesarea']['id'];
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
		activity($contact->id)
		->performedOn($contact)
		->causedBy($user)
		->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
		->log('Cập nhật Dự Án :subject.last_name, bởi :causer.name');
		return new ProjectResource($contact);
		return ['message' => 'Updated the Contact info'];
	}

	public function destroy($id)
	{
	    $user = auth('api')->user();
	    if($user->can('delete contacts')){
	        $contact = Project::find($id);
	        $contact->delete();
	        activity('xoaduan')
	        ->performedOn($contact)
	        ->causedBy($user)
	        ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
	        ->log('Xóa Dự Án :subject.last_name, bởi :causer.name');
	        return ['message' => 'Contact Deleted'];
	    }
	    else{
	        return response()->toJson([
	            'message' => 'Không thể xóa khách hàng tiềm năng!',
	        ], 404);
	    }
	}

    public function export(Request $request) 
    {
        $user = \Auth::User();
        if (!$user->can('import')) {
            if (!$request->has('users')) {
                $request['users'] = [$user->id];
            }
        }

        $contacts = Project::filter($request->all())->get();
        return Excel::download(new ProjectExport($contacts), 'duan.xlsx');
    }

	public function comments($id){
	    $contact = Project::findOrFail($id);
	    return $contact->comments;
	}

    public function storecomment(Request $request, $id){

	    $user = auth('api')->user();
	    $contact = Project::findOrFail($id);
	    $comment = $contact->comment([
	        'title' => $user->name,
	        'body' => $request['body'],
	    ], $user);

	    activity($contact->id)
	    ->performedOn($comment)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
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

	public function storetask(Request $request, $id){
	    $user = User::find($request['user_id']);
	    $contact = Project::findOrFail($id);
	    $task = $contact->task([
	        'title' => $request['title'],
	        'task' => $request['task'],
	        'priority' => $request['priority'],
	        'duedate' => $request['duedate']
	    ], $user);
	    activity($contact->id)
	    ->performedOn($task)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
	    ->log('Kế hoạch :subject.title, bởi :causer.name');
	    return new TaskResource($task);
	}

	public function storeLost(Request $request, $id){
	    $user = auth('api')->user();
	    $contact = Project::findOrFail($id);
	    $lost = $contact->lost([
	        'name' => $request['name'],
	        'description' => $request['description'],
	    ], $user);
	    activity($contact->id)
	    ->performedOn($lost)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
	    ->log('Thêm lo lắng :subject.name :subject.description, bởi :causer.name');
	    return $lost;
	}
	// public function losts($id){
	//     $items = [];
	//     $contact = Contact::findOrFail($id);
	//     $contact->losts->each(function($item) use (&$items){
	//         $item['user_name'] = User::find($item->creator_id)->name;
	//         $items[] = $item;
	//     });
	//     return $items;
	// }

	public function storeKh15(Request $request, $id){
	    $user = auth('api')->user();
	    $contact = Project::findOrFail($id);
	    $kh15 = $contact->kh15([
	        'name' => $request['name'],
	        'description' => $request['description'],
	    ], $user);
	    activity($contact->id)
	    ->performedOn($kh15)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
	    ->log('Thêm đặc điểm KHDA :subject.name :subject.description, bởi :causer.name');
	    return $kh15;
	}
	// public function kh15s($id){
	//     $items = [];
	//     $contact = Contact::findOrFail($id);
	//     $contact->kh15s->each(function($item) use (&$items){
	//         $item['user_name'] = User::find($item->creator_id)->name;
	//         $items[] = $item;
	//     });
	//     return $items;
	// }

	public function storeattachmens(Request $request,$id)
	{
	    $user = auth('api')->user();
	    $contact = Project::findOrFail($id);
	    // return $request->file('file');
	    $media = $contact->addMedia($request->file('file'))
	    ->withCustomProperties(['uploadBy' => $user->name . ' - ' . $user->id])
	    ->toMediaCollection();
	    activity($contact->id)
	    ->performedOn($contact)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
	    ->log('Gửi tài liệu :subject.last_name, bởi :causer.name');
	    return $media->getUrl();
	}

	public function storeVideo(Request $request, $id){
	    $user = auth('api')->user();
	    $contact = Project::findOrFail($id);
	    $video = $contact->video([
	        'title' => $request['title'],
	    ], $user);
	    activity($contact->id)
	    ->performedOn($contact)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
	    ->log('Video :subject.title, bởi :causer.name');
	    return $video;
	}

	public function storeImage(Request $request,$id)
	{
	    $user = auth('api')->user();
	    $contact = Project::findOrFail($id);
	    $media = $contact->addMedia($request->file('file'))
	    ->toMediaCollection('images','public');
	    $image = $contact->attach([
	        'src' => $media->getUrl(),
	    ], $user);
	    activity($contact->id)
	    ->performedOn($contact)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
	    ->log('Ảnh :subject.title, bởi :causer.name');
	    return new AttachmentResource($image);
	}

	public function storeQuote(Request $request, $id)
	{
	    $request->validate([
	        'productLines' => ['required'],
	    ]);
	    $user = auth('api')->user();
	    $contact = Project::findOrFail($id);
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
	    activity($contact->id)
	    ->performedOn($quote)
	    ->causedBy($user)
	    ->withProperties(['zip' => 'ttkhachduan','id' => $contact->id])
	    ->log(':subject.subject, bởi :causer.name');
	    return ['message' => "Thành công!"];
	}
}