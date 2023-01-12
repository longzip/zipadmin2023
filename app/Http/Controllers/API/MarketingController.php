<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Marketing;
use App\Product;
use NoiThatZip\Lead\Models\Lead;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Auth;
use App\User;
use App\GiaiDoan;
use App\ChienDich;
use App\LogGiaiDoan;
use Carbon\Carbon;
use DB;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\Http\Resources\Lead as LeadResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;

class MarketingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        $leads = Lead::filter($request->all())->get();
        $lead = $leads->map(function ($t) use ($user){
        	$id_giaidoan = GiaiDoan::where('id_kh15',$t->id)->first();
        	$tt = empty($id_giaidoan) ? ['id_tt' => 7,'created_at'=>Carbon::now('Asia/Ho_Chi_Minh')] : LogGiaiDoan::where('id_giaidoan',$id_giaidoan['id'])->orderBy('id','desc')->first();
        	$check = \DB::table('model_has_roles')->where('model_id',$user->id)->where('role_id',9)->get();
        	$checkasm = \DB::table('model_has_roles')->where('model_id',$user->id)->where('role_id',4)->get();
        	$idContact = Contact::where('phone',$t->phone)->orderBy('id','desc')->first();
	        if($check->isEmpty()) {
		        $m = 1;
	        }else {
		        $m = 0;
	        }
	        if($checkasm->isEmpty()) {
		        $a = 1;
	        }else {
		        $a = 0;
	        }

        	$checksm = \DB::table('model_has_roles')->where('model_id',$user->id)->where('role_id',6)->get();
        	$costcenter = $t->costcentersList()->isNotEmpty() ? $t->costcentersList()[0]['name'] : '';
        	if($checksm->isEmpty()) {
	        	return (object)[
	        		'id' => $t->id,
                    'user_create' => $t->user_id,
	        		'user_login' => $user->id,
		            'title'      => $t->title,
		            'first_name' => $t->first_name,
		            'last_name'  => $t->last_name,
		            'phone'      => $t->phone,
		            'email'      => $t->email,
		            'company'    => $t->company,
		            'address'    => $t->address,
		            'city'       => $t->city,
		            'description' => $t->description,
		            'start_date' => $t->start_date,
                    'start_time' => $t->start_time,
		            'statuskh' => $t->statuskh,
		            'end_time' => $t->end_time,
                    'status'     => $t->status,
		            'chiendich'     => empty(ChienDich::find($t->chiendich)) ? '' : ChienDich::find($t->chiendich)->name,
	            	'amount'     =>  Product::select('price')->whereIn('id',$t->productLines()->pluck('product_id'))->sum('price'),
		            'type'     => $t->type,
		            'costcenters' => $costcenter,
		            'products'    => Product::select('id','code as name')->whereIn('id',$t->productLines()->pluck('product_id'))->get(),
		            'username'   => User::find($t->user_id)->name,
		            'note'       =>  $t->note, 
		            'created_at' => (string)$t->created_at,
		            'sanpham' => $t->sanpham,
		            'giaidoan' => $tt['id_tt'],
		            'time_giaidoan' => $tt['created_at'],
		            'theodoi' => $id_giaidoan,
		            'id_khtn' => isset($id_giaidoan) ? $id_giaidoan['id_khtn'] : null,
		            'marketing' => $m,
		            'asm' => $a,
                    'idContact' => isset($idContact['id']) ? $idContact['id'] : '',
		            'typecontact' => isset($idContact['status']) ? (int)$idContact['status'] : null,
	        	];
	        }else {
	        	$idSR = \DB::table('model_has_costcenters')->where('model_id',$user->id)->pluck('costcenter_id');
	            $costcenters = \DB::table('costcenters')->whereIn('id',$idSR)->pluck('name');
	            foreach ($costcenters as $key => $value) {
	            	if ($costcenter == $value) {
	            		return (object)[
			        		'id' => $t->id,
                            'user_create' => $t->user_id,
			        		'user_login' => $user->id,
				            'title'      => $t->title,
				            'first_name' => $t->first_name,
				            'last_name'  => $t->last_name,
				            'phone'      => $t->phone,
				            'email'      => $t->email,
				            'company'    => $t->company,
				            'address'    => $t->address,
				            'city'       => $t->city,
				            'description' => $t->description,
				            'start_date' => $t->start_date,
                            'statuskh' => $t->statuskh,
				            'start_time' => $t->start_time,
				            'end_time' => $t->end_time,
                            'chiendich'     => empty(ChienDich::find($t->chiendich)) ? '' : ChienDich::find($t->chiendich)->name,
				            'status'     => $t->status,
			            	'amount'     =>  Product::select('price')->whereIn('id',$t->productLines()->pluck('product_id'))->sum('price'),
				            'type'     => $t->type,
				            'costcenters' => $costcenter,
				            'products'    => Product::select('id','code as name')->whereIn('id',$t->productLines()->pluck('product_id'))->get(),
				            'username'   => User::find($t->user_id)->name,
				            'note'       =>  $t->note, 
				            'created_at' => (string)$t->created_at,
				            'sanpham' => $t->sanpham,
				            'giaidoan' => $tt['id_tt'],
				            'time_giaidoan' => $tt['created_at'],
				            'theodoi' => $id_giaidoan,
				            'id_khtn' => $id_giaidoan['id_khtn'],
				            'marketing' => $m,
				            'asm' => $a,
                            'idContact' => isset($idContact['id']) ? $idContact['id'] : '',
				            'typecontact' => isset($idContact['status']) ? (int)$idContact['status'] : null,
			        	];
	            	}
	            }
	        }
        });
		$lead = array_values((array)$lead);
		$result = array_filter($lead[0]);                 
        $l = collect($result)->groupBy(function ($va)
        {
        	return $va->start_date;
        });

		return collect($l)->sortKeysDesc();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function sumkhach(Request $request)
    {
        if ($request->getkhtn == 0) {
            return Lead::filter($request->all())->count();
        }else{
            $phone = Contact::pluck('phone');
            return Lead::filter($request->all())->whereIn('phone', $phone)->count();
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            'last_name' => ['required', 'string', 'max:255']
        ]);
        $user = auth('api')->user();
        $lead = new Lead([
           'title' => $request['title'],
           'first_name' => '',
           'last_name' => $request['last_name'],
           'email' => $request['email'],
           'phone' => $request['phone'],
           'company' => '',
           'address' => $request['address'],
           'city' => $request['city'],
           'description' => $request['description'],
           'start_date' => $request['start_date'],
           'start_time' => null,
           'end_time' => null,
           'note'     => $request['note'],
           'user_id' => $user->id,
           'type' => 2,
           'chiendich' => empty($request['chiendich']) ? null : $request['chiendich']['id'],
       	]);


        $lead->save();
        $thread = Thread::create(
            [
                'subject' => 'Marketing#'  . $lead->id . $lead->last_name . ' được tạo bởi' . $user->name
            ]
        );
        $lead->thread_id = $thread->id;
        $lead->save();
        if($request['costcenters']){
	        $lead->syncCostcenters([collect($request['costcenters'])['id']]);
        }
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
        if($request['costcenters']){
            $costcenter = \NoiThatZip\Costcenter\Models\Costcenter::find(collect($request['costcenters'])['id']);
            $thread->addParticipant($costcenter->entries('\App\User')->pluck('id')->toArray());
        }
       
        activity()
        ->performedOn($lead)
        ->causedBy($user)
        ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
        ->log('Đã tạo khách Marketing :subject.last_name, bởi :causer.name');

        // dd($lead->id);
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
        //
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
        $lead = Lead::findOrFail($id);
        $lead->delete();
        activity()
        ->performedOn($lead)
        ->causedBy($user)
        ->withProperties(['zip' => 'kh15Show','id' => $lead->id])
        ->log('Xóa KH15 :subject.last_name, bởi :causer.name');
        return ['message' => 'Lead Deleted'];
      
    }

    public function loadgiaidoan(Request $request)
    {
    	$g = GiaiDoan::where('id_kh15',$request['id'])->first();
    	return LogGiaiDoan::where('id_giaidoan',$g['id'])->get();
    }

    public function loadgiaidoankhtn(Request $request)
    {
        $g = GiaiDoan::where('id_khtn',$request['id'])->first();
        return LogGiaiDoan::where('id_giaidoan',$g['id'])->get();
    }
}
