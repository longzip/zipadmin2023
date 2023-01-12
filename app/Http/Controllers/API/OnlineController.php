<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Online;
use App\Order;
use App\Http\Resources\OnlineMarketing as OnlineResource;
use Carbon\Carbon;
use App\OnlineLine;
use App\ChienDichTarget;
use App\OrderLine;

class OnlineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
	{
	    $this->middleware('auth:api');
	}

    public function index(Request $request)
    {
        // dd($request->all());
    	$online = Online::filter($request->all())->latest()->paginateFilter();
    	return OnlineResource::collection($online);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $online = new Online;
        $online->chiphi = $request->form['chiphi'];
        $online->date = $request->form['date'];
        $online->id_chiendich = $request->form['chiendich']['id'];
        $online->mess = $request->form['mess'];
        $online->save();
        
        return $online;
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

        $online =  Online::findOrFail($id);;
        $online->chiphi = $request->form['chiphi'];
        $online->date = $request->form['date'];
        $online->id_chiendich = $request->form['chiendich']['id'];
        $online->mess = $request->form['mess'];
        $online->save();

        return $online;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $online = Online::findOrFail($id)->delete();
        return ['message' => 'Xóa chế tài Thành Công'];
    }

    public function soluong(Request $request){
        $online = Online::leftJoin('online_line', 'online.id', '=', 'online_line.id_online')->filter($request->all())->get();
        $mess = array();$phone = array();$khtn = array();

        $on = $online->groupBy(function($o) {
            return $o->date;
        });
        foreach ($on as $key => $value) {
            foreach ($value as $k => $v) {
                $mess[$key][$k+1] = $v->mess;
                $phone[$key][$k+1] = $v->phone;
                $khtn[$key][$k+1] = $v->khtn;
            }
            $mess[$key] = array_sum($mess[$key]);
            $phone[$key] = array_sum($phone[$key]);
            $khtn[$key] = array_sum($khtn[$key]);
        }
        $data = [$mess,$phone,$khtn];
        return [$data,'Tin Nhắn','Phone','KHTN'];
    }

    public function tien(Request $request){
        $online = Online::leftJoin('online_line', 'online.id', '=', 'online_line.id_online')->filter($request->all())->get();
        $order = array();
        $count = array();
        $chiphi = array();
        $product = array();
        $on = $online->groupBy(function($o) {
            return $o->date;
        });
        foreach ($on as $key => $value) {
            $loc = array();
            $o = Order::whereDate('start_date',$key)->Where('costcenter','ON_MB')->orWhere('costcenter','ON_MN')->pluck('id');
            foreach ($value as $k => $v) {
                $chiphi[$key][$k+1] = $v->chiphi;
                $product[$key][$k] = $v->product;
            }
            $li = OrderLine::whereIn('order_id',$o)->whereIn('item',$product[$key])->get();
            foreach ($li as $don) {
                $loc = $don['order_id'];
            }
            $chiphi[$key] = array_sum($chiphi[$key]);
            $order[$key] = $li->sum('amount');
            $count[$key] = is_array($loc) ? 0 : $loc;
        }
        // dd($li->sum('amount'));
        // foreach ($online as $key => $value) {
        // 	$order[$value->date] = Order::whereDate('start_date',$value->date)->Where('costcenter','ON_MB')->orWhere('costcenter','ON_MN')->sum('amount');
        // 	$count[$value->date] = Order::whereDate('start_date',$value->date)->Where('costcenter','ON_MB')->orWhere('costcenter','ON_MN')->count();
        // 	$chiphi[$value->date] = $value->chiphi;
        // }
        $data = [$count,$order,$chiphi];
        return [$data,'Tổng Đơn','Danh Số','Chi Phí'];
        // return $counted->sortKeys()->all();
    }

    public function total(Request $request)
    {
        // dd($request->all());
        // $chiendich = ChienDich::where('id',$this->id_chiendich)->first();
        // $arr = array();
        // $online = OnlineLine::where('id_online',$this->id)->get();
        // $date1 = new DateTime($chiendich['start']);
        // $date2 = new DateTime($chiendich['end']);
        $online = array();
        if (isset($request->chiendich)) {
            $findid = Online::where('id_chiendich',$request->chiendich)->pluck('id');
            $or = OnlineLine::whereIn('id_online',$findid)->get();
            $target = ChienDichTarget::where('id_chiendich',$request->chiendich)->get();
            $online['mess'] = $or->sum('mess'); 
            $online['khtn'] = $or->sum('khtn'); 
            $online['phone'] = $or->sum('phone'); 
            $online['targetmess'] = $target->sum('mess'); 
            $online['targetkhtn'] = $target->sum('khtn'); 
            $online['targetphone'] = $target->sum('phone');
        }else{
            $or = OnlineLine::all();
            $target = ChienDichTarget::all();
            $online['mess'] = $or->sum('mess'); 
            $online['khtn'] = $or->sum('khtn'); 
            $online['phone'] = $or->sum('phone');
            $online['targetmess'] = $target->sum('mess'); 
            $online['targetkhtn'] = $target->sum('khtn'); 
            $online['targetphone'] = $target->sum('phone');  
        }
        return $online;
    }

    public function load(Request $request)
    {
        return ChienDichTarget::where('id_chiendich',$request->id)->get();
    }
}
