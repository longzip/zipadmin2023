<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderLine;
use App\Product;
use NoiThatZip\SalesTarget\Models\SalesTarget;

class ReportProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::filter($request->all())->orderBy('start_date','desc')->pluck('id');
        $array = array(); $sum = 0;
        $loai = array();$sumloai = 0;$theotien = array();$tongtheotien = array();
        $prokey = array();$sumpro = 0;

        $date = $request->sdates;
        $d_week1 = $date[0];
        $c_week1 = date('Y-m-d',strtotime($d_week1 . " +6 day"));

        $d_week2 = date('Y-m-d',strtotime($c_week1 . " +1 day"));
        $c_week2 = date('Y-m-d',strtotime($d_week2 . " +6 day"));

        $d_week3 = date('Y-m-d',strtotime($c_week2 . " +1 day"));
        $c_week3 = date('Y-m-d',strtotime($d_week3 . " +6 day"));

        $d_week4 = date('Y-m-d',strtotime($c_week3 . " +1 day"));
        $c_week4 = date('Y-m-d',strtotime($d_week4 . " +6 day"));

        $tivy1 = 0;$tiris1 = 0;$tkhac1 = 0;$tdahlia1 = 0;
        // $tivy2 = 0;$tiris2 = 0;$tkhac2 = 0;$tdahlia2 = 0;
        // $tivy3 = 0;$tiris3 = 0;$tkhac3 = 0;$tdahlia3 = 0;
        // $tivy4 = 0;$tiris4 = 0;$tkhac4 = 0;$tdahlia4 = 0;

        $ta1 = SalesTarget::where('targetable_type','App\Products')->whereDate('close',$c_week1)->first();
        $ta2 = SalesTarget::where('targetable_type','App\Products')->whereDate('close',$c_week2)->first();
        $ta3 = SalesTarget::where('targetable_type','App\Products')->whereDate('close',$c_week3)->first();
        $ta4 = SalesTarget::where('targetable_type','App\Products')->whereDate('close',$c_week4)->first();

        $tivy1 = $ta1['ivy'] + $ta2['ivy'] + $ta3['ivy'] + $ta4['ivy'];
        $tiris1 = $ta1['iris'] + $ta2['iris'] + $ta3['iris'] + $ta4['iris'];
        $tkhac1 = $ta1['khac'] + $ta2['khac'] + $ta3['khac'] + $ta4['khac'];
        $tdahlia1 = $ta1['dahlia'] + $ta2['dahlia'] + $ta3['dahlia'] + $ta4['dahlia'];

        if($request->p){
            if($request->p == 32 || $request->p == 29 || $request->p == 10 || $request->p == 2 || $request->p == 4 ){
                $pro = Product::where('groups', $request->p)->get();
            }
            $id = Product::where('groups', $request->p)->pluck('id','code');
            $k = Product::where('groups', $request->p)->get();
        }else{
            $id = Product::pluck('id','code');
            $k = Product::all();
            $pro = Product::whereIn('groups',[29,32,2,10,4])->get();
        }

        // $maploai = $k->groupBy(function($l){
        //     return $l->check_name;
        // });
        $maploai = $k->groupBy(function($l){
            return $l->name_groups;
        });

        $mapkey = $pro->groupBy(function($l){
            return $l->groups;
        });

        foreach ($id as $key => $value) {
            $sum = OrderLine::whereIn('order_id',$orders)->where('product_id',$value)->sum('quantity');         
            if($sum > 0){
                $array[$key] = $sum;
            }                                   
        }

        foreach ($maploai as $key => $value) {
            foreach ($value as $v) {
                $sumloai = OrderLine::whereIn('order_id',$orders)->where('product_id',$v['id'])->get();         
                if($sumloai->sum('quantity') > 0){
                    $loai[$key][] = $sumloai->sum('quantity');
                    $theotien[$key][] = $sumloai->sum('amount');
                }                                   
            }
            if(isset($loai[$key])) {
                $tongtheotien[$key] = array_sum($theotien[$key]);
                $loai[$key] = array_sum($loai[$key]);
            }
        }

        // DD(count($pro));
        if (isset($pro) > 0) {
            foreach ($mapkey as $key => $value) {
                foreach ($value as $v) {
                    $sumpro = OrderLine::whereIn('order_id',$orders)->where('product_id',$v['id'])->sum('quantity');         
                    if($sumpro > 0){
                        $prokey[$key][] = $sumpro;
                    }                                   
                }
                if(isset($prokey[$key])) {
                    $prokey[$key] = array_sum($prokey[$key]);
                }
            }
        }
        // dd($loai);
        arsort($array);
        arsort($loai);
        return ['sanpham'=>$array,'dong'=>$loai,'tongtheotien'=>$tongtheotien,'key'=>['Ivy'=>empty($prokey[29]) ? '0 / '. $tivy1 : $prokey[29] .' / '. $tivy1,'Olive'=>empty($prokey[32]) ? '0 / '. $tdahlia1 : $prokey[32].' / '. $tdahlia1,'Iris'=>empty($prokey[2]) ? ' / '. $tiris1 : $prokey[2].' / '. $tiris1,'Tulip'=>empty($prokey[10]) ? '0 / '. $tkhac1 : $prokey[10].' / '. $tkhac1,'Pansy'=>empty($prokey[4]) ? '0 / 0' : $prokey[4].' / 0',]];
    }

    public function loadone(Request $request)
    {
        // dd($request->all());
        $orders = Order::filter($request->all())->orderBy('start_date','desc')->pluck('id');
        $array = array(); $sum = 0;
        $id = Product::where('name_groups', $request->group)->pluck('check_kt','check_name');
// dd($id);
        foreach ($id as $key => $value) {
            $po = Product::where('check_kt', $value)->pluck('id');
            $sum = OrderLine::whereIn('order_id',$orders)->whereIn('product_id',$po)->sum('quantity');         
            if($sum > 0){
                $array[$key] = $sum;
            }                                   
        }
        arsort($array);
        return ['sanpham'=>$array,'name'=>$request->group];
    }


    public function load(Request $request)
    {
        $orders = Order::filter($request->all())->orderBy('start_date','desc')->pluck('id');
        $array = array(); $sum = 0;
        $id = Product::where('check_name', $request->group)->pluck('id','code');

        foreach ($id as $key => $value) {
            $sum = OrderLine::whereIn('order_id',$orders)->where('product_id',$value)->sum('quantity');         
            if($sum > 0){
                $array[$key] = $sum;
            }                                   
        }
        // dd($loai);
        arsort($array);
        return ['sanpham'=>$array,'name'=>$request->group];
    }

    public function loadkey(Request $request)
    {
        $orders = Order::filter($request->all())->orderBy('start_date','desc')->pluck('id');
        $array = array(); $sum = 0;
        if ($request->key == 'Ivy') {
            $id = Product::where('groups', 29)->pluck('id','code');
        }
        if ($request->key == 'Iris') {
            $id = Product::where('groups', 2)->pluck('id','code');
        }
        if ($request->key == 'Tulip') {
            $id = Product::where('groups', 10)->pluck('id','code');
        }
        if ($request->key == 'Olive') {
            $id = Product::where('groups', 32)->pluck('id','code');
        }
        if ($request->key == 'Pansy') {
            $id = Product::where('groups', 4)->pluck('id','code');
        }

        foreach ($id as $key => $value) {
            $sum = OrderLine::whereIn('order_id',$orders)->where('product_id',$value)->sum('quantity');         
            if($sum > 0){
                $array[$key] = $sum;
            }                                   
        }
        // dd($loai);
        arsort($array);
        return ['sanpham'=>$array,'name'=>$request->key];
    }

    public function loadkeytarget(Request $request)
    {
        $date = $request->sdates;
        $d_week1 = $date[0];
        $c_week1 = date('Y-m-d',strtotime($d_week1 . " +6 day"));

        $d_week2 = date('Y-m-d',strtotime($c_week1 . " +1 day"));
        $c_week2 = date('Y-m-d',strtotime($d_week2 . " +6 day"));

        $d_week3 = date('Y-m-d',strtotime($c_week2 . " +1 day"));
        $c_week3 = date('Y-m-d',strtotime($d_week3 . " +6 day"));

        $d_week4 = date('Y-m-d',strtotime($c_week3 . " +1 day"));
        $c_week4 = date('Y-m-d',strtotime($d_week4 . " +6 day"));

        $prokey1 = array();$sumpro1 = 0;$t1 = 0;
        $prokey2 = array();$sumpro2 = 0;$t2 = 0;
        $prokey3 = array();$sumpro3 = 0;$t3 = 0;
        $prokey4 = array();$sumpro4 = 0;$t4 = 0;

        $ta1 = SalesTarget::where('targetable_type','App\Products')->whereDate('close',$c_week1)->first();
        $ta2 = SalesTarget::where('targetable_type','App\Products')->whereDate('close',$c_week2)->first();
        $ta3 = SalesTarget::where('targetable_type','App\Products')->whereDate('close',$c_week3)->first();
        $ta4 = SalesTarget::where('targetable_type','App\Products')->whereDate('close',$c_week4)->first();

        if ($request->value == 0) {
            $id_sp = Product::where('groups', 29)->get();
            $t1 = isset($ta1['ivy']) ? $ta1['ivy'] : 0;
            $t2 = isset($ta2['ivy']) ? $ta2['ivy'] : 0;
            $t3 = isset($ta3['ivy']) ? $ta3['ivy'] : 0;
            $t4 = isset($ta4['ivy']) ? $ta4['ivy'] : 0;
        }
        if ($request->value == 2) {
            $id_sp = Product::where('groups', 2)->get();
            $t1 = isset($ta1['iris']) ? $ta1['iris'] : 0;
            $t2 = isset($ta2['iris']) ? $ta2['iris'] : 0;
            $t3 = isset($ta3['iris']) ? $ta3['iris'] : 0;
            $t4 = isset($ta4['iris']) ? $ta4['iris'] : 0;
        }
        if ($request->value == 3) {
            $id_sp = Product::where('groups', 10)->get();
            $t1 = isset($ta1['khac']) ? $ta1['khac']: 0;
            $t2 = isset($ta2['khac']) ? $ta2['khac']: 0;
            $t3 = isset($ta3['khac']) ? $ta3['khac']: 0;
            $t4 = isset($ta4['khac']) ? $ta4['khac']: 0;
        }
        if ($request->value == 1) {
            $id_sp = Product::where('groups', 32)->get();
            $t1 = isset($ta1['dahlia']) ? $ta1['dahlia'] : 0;
            $t2 = isset($ta2['dahlia']) ? $ta2['dahlia'] : 0;
            $t3 = isset($ta3['dahlia']) ? $ta3['dahlia'] : 0;
            $t4 = isset($ta4['dahlia']) ? $ta4['dahlia'] : 0;
        }
        if ($request->value == 4) {
            $id_sp = Product::where('groups', 4)->get();
            $t1 =  0;
            $t2 =  0;
            $t3 =  0;
            $t4 =  0;
        }
        $mapkey = $id_sp->groupBy(function($l){
            return $l->groups;
        });
        $ot1 = Order::whereBetween('start_date',[$d_week1,$c_week1])->pluck('id');
        $ot2 = Order::whereBetween('start_date',[$d_week2,$c_week2])->pluck('id');
        $ot3 = Order::whereBetween('start_date',[$d_week3,$c_week3])->pluck('id');
        $ot4 = Order::whereBetween('start_date',[$d_week4,$c_week4])->pluck('id');

        foreach ($mapkey as $key => $value) {
            foreach ($value as $v) {
                $sumpro1 = OrderLine::whereIn('order_id',$ot1)->where('product_id',$v['id'])->sum('quantity');         
                $sumpro2 = OrderLine::whereIn('order_id',$ot2)->where('product_id',$v['id'])->sum('quantity');         
                $sumpro3 = OrderLine::whereIn('order_id',$ot3)->where('product_id',$v['id'])->sum('quantity');         
                $sumpro4 = OrderLine::whereIn('order_id',$ot4)->where('product_id',$v['id'])->sum('quantity');         
                if($sumpro1 > 0){
                    $prokey1[] = $sumpro1;
                }  
                if($sumpro2 > 0){
                    $prokey2[] = $sumpro2;
                }  
                if($sumpro3 > 0){
                    $prokey3[] = $sumpro3;
                }  
                if($sumpro4 > 0){
                    $prokey4[] = $sumpro4;
                }                                   
            }
            if(isset($prokey1)) {
                $prokey1 = array_sum($prokey1);
            }
            if(isset($prokey2)) {
                $prokey2 = array_sum($prokey2);
            }
            if(isset($prokey3)) {
                $prokey3 = array_sum($prokey3);
            }
            if(isset($prokey4)) {
                $prokey4 = array_sum($prokey4);
            }
        }
      
        return ['sanpham'=>[['a'=>$prokey1,'t'=>$t1],['a'=>$prokey2,'t'=>$t2],['a'=>$prokey3,'t'=>$t3],['a'=>$prokey4,'t'=>$t4],],'name'=>$request->value];
    }
}
