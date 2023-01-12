<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Resources\Contact as ContactResource;
class DetailTargetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        
        $start_day = $request->dates[0];
        $check = $request->value;
        $add=$check*7;
        if($check==0){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        if($check==1){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));


        }
        if($check==2){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        if($check==3){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        $range = $this->dateRange($day,$end_t);

        $showroom_id = \DB::table('costcenters')->whereDate('closed','>',$start_day)->OrderBy('warehouse','asc')->pluck('name','id');
        foreach ($showroom_id as $key => $value) {
            $customer= \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->where('costcenter_id',$key)->pluck('model_id');
            foreach ($range as  $val) {
              $s[$value][]=\DB::table('contacts')->whereIn('id',$customer)->whereDate('start_date',$val)->count();
            }

            //
        }
        // dd($s);
        return $s;
    }
    public function targetKH15(Request $request)
    {
        // dd($customer);
        $start_day = $request->dates[0];
        $check = $request->value;
        $add=$check*7;
        if($check==0){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        if($check==1){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));


        }
        if($check==2){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        if($check==3){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        $range = $this->dateRange($day,$end_t);

        $showroom_id = \DB::table('costcenters')->whereDate('closed','>',$start_day)->pluck('name','id');
        foreach ($showroom_id as $key => $value) {
            $customer= \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->where('costcenter_id',$key)->pluck('model_id');
            foreach ($range as  $val) {
              $s[$value][]=\DB::table('leads')->whereIn('id',$customer)->whereDate('start_date',$val)->count();
            }

            //
        }
        // dd($s);

        return $s;
    }
    public function targetDonHang(Request $request)
    {
        // dd($customer);
        $start_day = $request->dates[0];
        $check = $request->value;
        $add=$check*7;
        if($check==0){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        if($check==1){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));


        }
        if($check==2){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        if($check==3){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        $range = $this->dateRange($day,$end_t);
        $showroom_id = \DB::table('costcenters')->whereDate('closed','>',$start_day)->pluck('name','code');
        foreach ($showroom_id as $key => $value) {
            $code =    \DB::table('orders')->where('costcenter',$key)->pluck('id');
            foreach ($range as  $val) {
              $s[$value][]=\DB::table('orders')->whereIn('id',$code)->whereDate('start_date',$val)->count();
            }
        }
        return $s;
    }
    public function targetDoanhSo(Request $request)
    {
        // dd($customer);
        // dd($request->all());
        
        $start_day =  date ( 'Y-m-d' , strtotime ( $request->dates[0]) );
        // $start_day =  date ( 'Y-m-d' , strtotime ( '-1 day' , strtotime ( $request->dates[0]) ) );
        
        $check = $request->value;
        $add=$check*7;
        if($check==0){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        if($check==1){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));


        }
        if($check==2){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        if($check==3){
            $day = date('d-m-Y',strtotime($start_day . " +".$add." day"));
            $end_t=date('d-m-Y',strtotime($day . "+6 day"));

        }
        $range = $this->dateRange($day,$end_t);
        $showroom_code = \DB::table('costcenters')->whereDate('closed','>',$start_day)->pluck('name','code');
        foreach ($showroom_code as $key => $value) {
            $showroom_id =\DB::table('orders')->where('costcenter',$key)->whereNull('cos_chia')->pluck('id');
            $showroom_idonline =\DB::table('orders')->where('costcenter',$key)->whereNotNull('cos_chia')->pluck('id');
            $showroom_idchia =\DB::table('orders')->where('cos_chia',$key)->pluck('id');
            foreach ($range as  $val) {
              $money[$value][]=\DB::table('orders')->whereIn('id',$showroom_id)->whereDate('start_date',$val)->pluck('amount')->sum() + \DB::table('orders')->whereIn('id',$showroom_idchia)->whereDate('start_date',$val)->pluck('amount')->sum() / 2 + \DB::table('orders')->whereIn('id',$showroom_idonline)->whereDate('start_date',$val)->pluck('amount')->sum() / 2;
            }
        }
        return $money;
    }
    function dateRange ($first, $last, $step = '+1 day', $format = 'Y-m-d'){
        $dates = array();
        $current = strtotime( $first );
        $last = strtotime( $last );
        while( $current <= $last ) {

            $dates[] = date( $format, $current );
            $current = strtotime( $step, $current );
        }
        return $dates;
    }
    public function store(Request $request)
    {
        $user = auth('api')->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ],
        [
            'name.required'=>'Bạn chưa nhập tên quyết định'
        ]);
        // $role_id = \DB::table('roles')->whereIn('name',$request->roles)->pluck('id');
        $lastRecord = LoaiChiPhi::all()->last();

        $loaichiphi = new LoaiChiPhi;
        $loaichiphi->name = $request->name;
        $loaichiphi->save();
        // activity()
        // ->performedOn($loaichiphi) 
        // ->causedBy($user)
        // ->withProperties(['zip' => 'quytrinh','id' => $quytrinh->id])
        // ->log('Đã tạo Quy Trình :subject.name, bởi :causer.name');
        return $loaichiphi;
    }
    public function show(LoaiChiPhi $loaichiphi)
    {
      // return new LoaiChiPhiResource($loaichiphi);
    }

    public function update(Request $request, $id)
    {
        $user = auth('api')->user();
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ],
        [
            'name.required'=>'Bạn chưa nhập tên quyết định'
        ]);

        // $role_id = \DB::table('roles')->whereIn('name',$request->roles)->pluck('id');

        $loaichiphi = LoaiChiPhi::findOrFail($id);
        $loaichiphi->name = $request->name;
        $loaichiphi->save();
        return $loaichiphi;
    }

    public function destroy($id)
    {
        $user = auth('api')->user();
        // if (!$user->can('edit users')) {
        //     return response()->json([
        //         'message' => 'Bạn không có quyền xóa',
        //     ]);
        // }
        $user = LoaiChiPhi::where('id',$id)->delete();
        // return response()->json([
        //         'message' => 'Xóa Quy trình Thành Công',
        //     ]);
    }
    public function getAllLoaiChiPhi(){
        return LoaiChiPhi::all();
    }

}