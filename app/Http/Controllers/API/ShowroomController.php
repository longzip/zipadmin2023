<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use NoiThatZip\Lead\Models\Lead;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Auth;
use App\User;
use App\GiaiDoan;
use NoiThatZip\Quote\Http\Resources\Quote as QuoteResource;
use App\LogGiaiDoan;
use App\DiemRoi;
use Carbon\Carbon;
use App\LogStatus;
use DB;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\Http\Resources\Lead as LeadResource;
use NoiThatZip\Task\Http\Resources\Task as TaskResource;

class ShowroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

// dd( $request->datenew[0]);
//     	$stardate = new Carbon($request->datenew[0]);
//         $enddate = new Carbon($request->datenew[1]);
//         $dates[0] = $stardate->format('Y-m-d');
//         $dates[1] = $enddate->format('Y-m-d');
//         $lich = \DB::table('calendar_t')->where('start_t','>=',$dates[0])->where('end_t','<=',$dates[1])->pluck('id_table');
//         $dr = DiemRoi::whereIn('id_calendar_t',$lich)->orderBy('id','desc')->pluck('id','id_khtn')->toArray();
//         // $unique = $dr->unique('id_khtn')->toArray();
//         // $array = array_column($unique, 'id_khtn');
//         $arr = 	array();
//         foreach ($dr as $key => $value) {
//         	$arr = $key;
//         }
        
        $user = auth('api')->user();
        $leads = Contact::filter($request->all())->get();
        // return $leads;
        $lead = $leads->map(function ($t) use ($user){
        	$lead_start = Lead::where('phone',$t->phone)->orderBy('id','desc')->first();
        	$id_giaidoan = GiaiDoan::where('id_khtn',$t->id)->first();
        	$tt = empty($id_giaidoan) ? ['id_tt' => 7,'created_at'=>Carbon::now('Asia/Ho_Chi_Minh')] : LogGiaiDoan::where('id_giaidoan',$id_giaidoan['id'])->orderBy('id','desc')->first();
        	$check = \DB::table('model_has_roles')->where('model_id',$user->id)->where('role_id',9)->get();
        	$checkasm = \DB::table('model_has_roles')->where('model_id',$user->id)->where('role_id',4)->get();
        	$idContact = Contact::where('phone',$t->phone)->first();
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
	        $diemroi = DiemRoi::where('id_khtn',$t->id)->count();
	        $diemroi_get = DiemRoi::where('id_khtn',$t->id)->orderBy('id','desc')->first();
	        $diemroi_name = DB::table('calendar_t')->where('id_table',$diemroi_get['id_calendar_t'])->first();
        	$checksm = \DB::table('model_has_roles')->where('model_id',$user->id)->where('role_id',6)->get();
        	$costcenter = $t->costcentersList()->isNotEmpty() ? $t->costcentersList()[0]['name'] : '';
        	if($checksm->isEmpty()) {
	        	return (object)[
	        		'id' => $t->id,
	        		'user_login' => $user->id,
	        		'trangthai' => LogStatus::where('id_khtn',$t->id)->orderBy('id','desc')->first(),
		            'title'      => $t->title,
		            'first_name' => $t->first_name,
		            'last_name'  => $t->last_name,
		            'phone'      => $t->phone,
		            'duyet'      => $t->duyet,
		            'description'      => $t->description,
		            'email'      => $t->email,
		            'diemroi'    => $diemroi,
		            'diemroi_name' => $diemroi_name->t,
		            'company'    => $t->company,
		            'address'    => $t->address,
		            'city'       => $t->city,
		            'description' => $t->description,
		            'start_date_kh' => empty($lead_start['start_date']) ? null : $lead_start['start_date'], 
		            'start_date' => $t->start_date,
		            'start_time' => $t->start_time,
		            'end_time' => $t->end_time,
		            'quotes'     =>  QuoteResource::collection($t->quotes),
		            'status'     => (int)$t->status,
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
		            'id_khtn' => isset($id_giaidoan['id_khtn']) ? $id_giaidoan['id_khtn'] : null,
		            'marketing' => $m,
		            'asm' => $a,
		            'idContact' => isset($idContact['id']) ? $idContact['id'] : '',
	        	];
	        }else {
	        	$idSR = \DB::table('model_has_costcenters')->where('model_id',$user->id)->pluck('costcenter_id');
	            $costcenters = \DB::table('costcenters')->whereIn('id',$idSR)->pluck('name');
	            foreach ($costcenters as $key => $value) {
	            	if ($costcenter == $value) {
	            		return (object)[
			        		'id' => $t->id,
			        		'user_login' => $user->id,
				            'title'      => $t->title,
				            'first_name' => $t->first_name,
			        		'trangthai' => LogStatus::where('id_khtn',$t->id)->orderBy('id','desc')->first(),
				            'last_name'  => $t->last_name,
				            'phone'      => $t->phone,
		            		'diemroi_name'    => $diemroi_name->t,
				            'description'      => $t->description,
				            'email'      => $t->email,
				            'duyet'      => $t->duyet,
				            'quotes'     =>  QuoteResource::collection($t->quotes),
				            'company'    => $t->company,
				            'address'    => $t->address,
		            		'start_date_kh' => empty($lead_start['start_date']) ? null : $lead_start['start_date'], 
				            'city'       => $t->city,
				            'description' => $t->description,
				            'start_date' => $t->start_date,
				            'start_time' => $t->start_time,
				            'end_time' => $t->end_time,
				            'diemroi'    => $diemroi,
				            'status'     => (int)$t->status,
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
				            'id_khtn' => isset($id_giaidoan['id_khtn']) ? $id_giaidoan['id_khtn'] : null,
				            'marketing' => $m,
				            'asm' => $a,
				            'idContact' => isset($idContact['id']) ? $idContact['id'] : '',
			        	];
	            	}
	            }
	        }
        });
		$lead = array_values((array)$lead);
		$result = array_filter($lead[0]);                 
        $l = collect($result)->groupBy(function ($va)
        {
        	return $va->costcenters;
        });

		return collect($l)->sortKeysDesc();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removelistsr(Request $request)
    {
        $c = Contact::find($request->id);
        $c->type = null;
        $c->save();
    }

    public function updatelistsr(Request $request)
    {
    	$c = Contact::find($request->id);
        $c->type = 4;
        $c->save();
    }

    public function duyetlistsr(Request $request)
    {
    	$c = Contact::find($request->id);
        $c->duyet = 1;
        $c->save();
    }

    public function sumbaogia(Request $request)
    {
        $leads = Contact::filter($request->all())->get();
    	$lead = $leads->map(function ($t){
    		$total = QuoteResource::collection($t->quotes)->last();
        	return $total;
        });
        return $lead->sum('total');
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
        //
    }
}
