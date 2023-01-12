<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AdminTarget;
use App\Order;
use App\OrderLine;
use App\ChuaGiao;
use App\ConsoleDuyet;

class AdminTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request)
    // {
    //     $admin = AdminTarget::leftjoin('calendar_t','target_admin.id_t','=','calendar_t.id_table')->whereBetween('date_fillter',[$request->sdates[0],$request->sdates[1]])->get();
    //     $time = $admin->groupBy(function($value){
    //         return $value->time;
    //     });
    //     $t = $admin->groupBy(function($value){
    //         return $value->t;
    //     });

    //     $summb = array();$summn = array();$summien = array();$sumt = array();
    //     foreach ($t as $key => $value) {
    //         foreach ($value as $v) {
    //             $mien_bac[$key][] = $v['mien_bac'];
    //             $mien_nam[$key][] = $v['mien_nam'];
    //             $start_t = $v['start_t'];
    //             $end_t = $v['end_t'];
    //             $d = $v['duyet'];
    //         }
    //         $duyet[] = $d;
    //         $summb[] = array_sum($mien_bac[$key]);
    //         $summn[] = array_sum($mien_nam[$key]);
    //         $sumort[] = Order::whereBetween('delivery_date',[$start_t,$end_t])->sum('amount');
    //     }

    //     for ($i=0; $i < count($summb) ; $i++) { 
    //         $summien[] = $summb[$i];
    //         $summien[] = $summn[$i];
    //         $sumt[$i][0] = $summn[$i] + $summb[$i];
    //         $sumt[$i][1] = $sumort[$i];
    //         $sumt[$i][2] = $duyet[$i];
    //     }

    //     $chuagiao = ChuaGiao::whereBetween('date_fillter',[$request->sdates[0],$request->sdates[1]])->get();
    //     foreach ($chuagiao as $key => $value) {
    //         $mienbac[0][] = $value['mien_bac'];
    //         $miennam[0][] = $value['mien_nam'];
    //         $mientrung[0][] = $value['mien_trung'];
    //         $total[0][] = $value['total'];
    //     }
    //     // DD($total);

    //     $mienbac[1] = Order::where('warehouse','A_MB')->where('costcenter','!=','ON_MB')->whereBetween('delivery_date',[date('Y-m-d', strtotime($end_t. ' + 1 days')),'2100-01-01'])->sum('amount');
    //     $miennam[1] = Order::where('warehouse','A_MN')->where('costcenter','!=','ON_MB')->whereBetween('delivery_date',[date('Y-m-d', strtotime($end_t. ' + 1 days')),'2100-01-01'])->sum('amount');
    //     $mientrung[1] = Order::where('warehouse','A_MT')->where('costcenter','!=','ON_MB')->whereBetween('delivery_date',[date('Y-m-d', strtotime($end_t. ' + 1 days')),'2100-01-01'])->sum('amount');
    //     $total[1] = Order::where('costcenter','!=','ON_MB')->whereBetween('delivery_date',[date('Y-m-d', strtotime($end_t. ' + 1 days')),'2100-01-01'])->sum('amount');

    //     return ['time'=>$time,'t'=>$t,'summien'=>$summien,'sumt'=>$sumt,'chuagiao'=>$chuagiao,'tongchuagiao'=>[
    //             [$mienbac[1],array_sum($mienbac[0])],
    //             [$mientrung[1],array_sum($mientrung[0])],
    //             [$miennam[1],array_sum($miennam[0])],
    //             [$total[1],array_sum($total[0])],
    //         ]
    //     ];
    // }

    public function index(Request $request)
    {
        $calendar = \DB::table('calendar')->where('start',$request->sdates[0])->where('end',$request->sdates[1])->first();
        $calendar_t = \DB::table('calendar_t')->where('id_p',$calendar->id)->get();
        // dd($calendar_t);

        foreach ($calendar_t as $key => $value) {
            $giao['tong'][$key]['tong'] = Order::whereBetween('delivery_date',[$value->start_t,$value->end_t])->sum('amount') - Order::whereBetween('delivery_date',[$value->start_t,$value->end_t])->sum('pre_pay'); 
            $giao['tong'][$key]['end'] = $value->end_t; 
            $giao['tong'][$key]['start'] = $value->start_t; 
            $giao['duyet'][] = ConsoleDuyet::where('start',$value->start_t)->where('end',$value->end_t)->orderBy('id','desc')->first(); 
            $giao['kv'][] = Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$value->start_t,$value->end_t])->sum('amount') - Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$value->start_t,$value->end_t])->sum('pre_pay');
            $giao['kv'][] = Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$value->start_t,$value->end_t])->sum('amount') - Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$value->start_t,$value->end_t])->sum('pre_pay');
            $giao['kv'][] = Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$value->start_t,$value->end_t])->sum('amount') - Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$value->start_t,$value->end_t])->sum('pre_pay');

            $giao['dautuan'][] = Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$value->start_t,date('Y-m-d', strtotime($value->end_t. ' -2 days'))])->sum('amount') - Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$value->start_t,date('Y-m-d', strtotime($value->end_t. ' -2 days'))])->sum('pre_pay');
            $giao['dautuan'][] = Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$value->start_t,date('Y-m-d', strtotime($value->end_t. ' -2 days'))])->sum('amount') - Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$value->start_t,date('Y-m-d', strtotime($value->end_t. ' -2 days'))])->sum('pre_pay');
            $giao['dautuan'][] = Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$value->start_t,date('Y-m-d', strtotime($value->end_t. ' -2 days'))])->sum('amount') - Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$value->start_t,date('Y-m-d', strtotime($value->end_t. ' -2 days'))])->sum('pre_pay');

            $giao['cuoituan'][] = Order::where('warehouse','A_MB')->whereBetween('delivery_date',[date('Y-m-d', strtotime($value->end_t. ' -1 days')),$value->end_t])->sum('amount') - Order::where('warehouse','A_MB')->whereBetween('delivery_date',[date('Y-m-d', strtotime($value->end_t. ' -1 days')),$value->end_t])->sum('pre_pay');
            $giao['cuoituan'][] = Order::where('warehouse','A_MT')->whereBetween('delivery_date',[date('Y-m-d', strtotime($value->end_t. ' -1 days')),$value->end_t])->sum('amount') - Order::where('warehouse','A_MT')->whereBetween('delivery_date',[date('Y-m-d', strtotime($value->end_t. ' -1 days')),$value->end_t])->sum('pre_pay');
            $giao['cuoituan'][] = Order::where('warehouse','A_MN')->whereBetween('delivery_date',[date('Y-m-d', strtotime($value->end_t. ' -1 days')),$value->end_t])->sum('amount') - Order::where('warehouse','A_MN')->whereBetween('delivery_date',[date('Y-m-d', strtotime($value->end_t. ' -1 days')),$value->end_t])->sum('pre_pay');
        }        

        $duyet['t1'][] = $giao['tong'][0];$duyet['t2'][] = $giao['tong'][1];$duyet['t3'][] = $giao['tong'][2];$duyet['t4'][] = $giao['tong'][3];
        $duyet['t1'][] = $calendar_t[0]->start_t;$duyet['t2'][] = $calendar_t[1]->start_t;$duyet['t3'][] = $calendar_t[2]->start_t;$duyet['t4'][] = $calendar_t[3]->start_t;
        $duyet['t1'][] = $calendar_t[0]->end_t;$duyet['t2'][] = $calendar_t[1]->end_t;$duyet['t3'][] = $calendar_t[2]->end_t;$duyet['t4'][] = $calendar_t[3]->end_t;
        if(\Auth::user()->id == 9017 || \Auth::user()->id == 9001){
            $duyet['t1'][] = 1;$duyet['t2'][] = 1;$duyet['t3'][] = 1;$duyet['t4'][] = 1;
        }else {
            $duyet['t1'][] = 0;$duyet['t2'][] = 0;$duyet['t3'][] = 0;$duyet['t4'][] = 0;
        }

        $date_tiep1 = \DB::table('calendar')->where('id',$calendar->id + 1)->first();
        $date_tiep2 = \DB::table('calendar')->where('id',$date_tiep1->id + 1)->first();
        $date_tiep3 = \DB::table('calendar')->where('id',$date_tiep2->id + 1)->first();
        $date_tiep4 = \DB::table('calendar')->where('id',$date_tiep3->id + 1)->first();
        $chuagiao['tiep'][$date_tiep1->p][0] = Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep1->start,$date_tiep1->end])->sum('amount') - Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep1->start,$date_tiep1->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep1->p][1] = Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep1->start,$date_tiep1->end])->sum('amount') - Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep1->start,$date_tiep1->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep1->p][2] = Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep1->start,$date_tiep1->end])->sum('amount') - Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep1->start,$date_tiep1->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep1->p][3] = Order::whereBetween('delivery_date',[$date_tiep1->start,$date_tiep1->end])->sum('amount') - Order::whereBetween('delivery_date',[$date_tiep1->start,$date_tiep1->end])->sum('pre_pay');

        $chuagiao['tiep'][$date_tiep2->p][0] = Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep2->start,$date_tiep2->end])->sum('amount') - Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep2->start,$date_tiep2->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep2->p][1] = Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep2->start,$date_tiep2->end])->sum('amount') - Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep2->start,$date_tiep2->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep2->p][2] = Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep2->start,$date_tiep2->end])->sum('amount') - Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep2->start,$date_tiep2->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep2->p][3] = Order::whereBetween('delivery_date',[$date_tiep2->start,$date_tiep2->end])->sum('amount') - Order::whereBetween('delivery_date',[$date_tiep2->start,$date_tiep2->end])->sum('pre_pay');


        $chuagiao['tiep'][$date_tiep3->p][0] = Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep3->start,$date_tiep3->end])->sum('amount') - Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep3->start,$date_tiep3->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep3->p][1] = Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep3->start,$date_tiep3->end])->sum('amount') - Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep3->start,$date_tiep3->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep3->p][2] = Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep3->start,$date_tiep3->end])->sum('amount') - Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep3->start,$date_tiep3->end])->sum('pre_pay');
        $chuagiao['tiep'][$date_tiep3->p][3] = Order::whereBetween('delivery_date',[$date_tiep3->start,$date_tiep3->end])->sum('amount') - Order::whereBetween('delivery_date',[$date_tiep3->start,$date_tiep3->end])->sum('pre_pay');


        $chuagiao['tiep']['còn lại'][0] = Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep4->start,'2090-01-01'])->sum('amount') - Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep4->start,'2090-01-01'])->sum('pre_pay');
        $chuagiao['tiep']['còn lại'][1] = Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep4->start,'2090-01-01'])->sum('amount') - Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep4->start,'2090-01-01'])->sum('pre_pay');
        $chuagiao['tiep']['còn lại'][2] = Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep4->start,'2090-01-01'])->sum('amount') - Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep4->start,'2090-01-01'])->sum('pre_pay');
        $chuagiao['tiep']['còn lại'][3] = Order::whereBetween('delivery_date',[$date_tiep4->start,'2090-01-01'])->sum('amount') - Order::whereBetween('delivery_date',[$date_tiep4->start,'2090-01-01'])->sum('pre_pay');

        $chuagiao['tiep']['Tổng'][0] = Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep1->start,'2090-01-01'])->sum('amount') - Order::where('warehouse','A_MB')->whereBetween('delivery_date',[$date_tiep1->start,'2090-01-01'])->sum('pre_pay');
        $chuagiao['tiep']['Tổng'][1] = Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep1->start,'2090-01-01'])->sum('amount') - Order::where('warehouse','A_MT')->whereBetween('delivery_date',[$date_tiep1->start,'2090-01-01'])->sum('pre_pay');
        $chuagiao['tiep']['Tổng'][2] = Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep1->start,'2090-01-01'])->sum('amount') - Order::where('warehouse','A_MN')->whereBetween('delivery_date',[$date_tiep1->start,'2090-01-01'])->sum('pre_pay');
        $chuagiao['tiep']['Tổng'][3] = Order::whereBetween('delivery_date',[$date_tiep1->start,'2090-01-01'])->sum('amount') - Order::whereBetween('delivery_date',[$date_tiep1->start,'2090-01-01'])->sum('pre_pay');


        return ['giao'=>$giao,'chuagiao'=>$chuagiao,'duyet'=>$duyet];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->line as $key => $value) {
            $admin = new AdminTarget;
            $admin->id_t = $request->form['id_table'];
            // $admin->start_date = $value['start_date'];
            $admin->time = $value['time'];
            // $admin->end_date = $value['end_date'];
            $admin->mien_bac = $value['mien_bac'];
            $admin->mien_nam = $value['mien_nam'];
            $admin->date_fillter = \Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
            $admin->save();
        }
        // dd($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showduyet(Request $request)
    {
        return ConsoleDuyet::where('start',$request->start)->where('end',$request->end)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateduyet(Request $request)
    {
        if ($request->stt == 1) {
            $check = ConsoleDuyet::where('start',$request->start)->where('end',$request->end)->where('stt',1)->get();
            if ($check->isEmpty()) {
                $new = new ConsoleDuyet;
                $new->start = $request->start;
                $new->end = $request->end;
                $new->money = $request->money;
                $new->stt = $request->stt;
                $new->orders = Order::whereBetween('delivery_date',[$request->start,$request->end])->pluck('so_number');
                $new->save();    
                Order::whereBetween('delivery_date',[$request->start,$request->end])->update(['color' => 2]);
            }
        }else{
            $new = new ConsoleDuyet;
            $new->start = $request->start;
            $new->end = $request->end;
            $new->money = $request->money;
            $new->stt = $request->stt;
            $new->orders = Order::whereBetween('delivery_date',[$request->start,$request->end])->pluck('so_number');
            $new->save();  
            Order::whereBetween('delivery_date',[$request->start,$request->end])->update(['color' => 1]);

        }

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

    public function ketoan(Request $request)
    {
        $donhang = ['giatrcck' => 0,'giasauck' => 0];
        $orders = Order::filter($request->all())->get();
        foreach ($orders as $key => $value) {
            $line = OrderLine::where('order_id',$value['id'])->get();
            foreach ($line as $v) {
                $donhang['listtrc'][] = $v['price'] * $v['quantity'] ;
                $donhang['listsau'][] = $v['amount'] ;
            }
            $donhang['giatrcck'] = array_sum($donhang['listtrc']);
            $donhang['giasauck'] = array_sum($donhang['listsau']);
        }
        $donhang['fee'] = $orders->sum('vat') + $orders->sum('fee_vc') + $orders->sum('fee_ld') + $orders->sum('qgg');
        $donhang['coc'] = $orders->sum('pre_pay');

        $g = $orders->groupBy(function ($value){
            return $value->warehouse;
        });
        foreach ($g as $k => $a) {
            foreach ($a as $val) {
                $l = OrderLine::where('order_id',$val['id'])->get();
                foreach ($l as $v) {
                    $donhang['listtrc'.$k][] = $v['price'] * $v['quantity'] ;
                    $donhang['listsau'.$k][] = $v['amount'] ;
                }
                $donhang['giatrcck'.$k] = array_sum($donhang['listtrc'.$k]);
                $donhang['giasauck'.$k] = array_sum($donhang['listsau'.$k]);
            }
            $donhang['fee'.$k] = $a->sum('vat') + $a->sum('fee_vc') + $a->sum('fee_ld') + $a->sum('qgg');
            $donhang['coc'.$k] = $a->sum('pre_pay');
        }
        // dd($donhang);
        return $donhang;
    }

    public function loadmore(Request $request)
    {
        // dd($request->all());
        $orders = Order::filter($request->all())->where('warehouse',$request->w)->get();
        $new = $orders->map(function ($value)
        {
            $line = OrderLine::where('order_id',$value->id)->get();
            $new = $line->map(function ($v)
            {
                return [
                    'giatrcck' => $v->price * $v->quantity,
                    'giasauck' => $v->amount,
                ];
            });
            return [
                'donhang' => $value->so_number,
                'giatrcck' => $new->sum('giatrcck'),
                'giasauck' => $new->sum('giasauck'),
                'coc' => $value->pre_pay,
                'fee' => $value->vat + $value->fee_ld + $value->fee_vc + $value->qgg,
            ];
        });
        return $new;
    }
}
