<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Lead\Models\Lead;
use Carbon\CarbonPeriod;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\Order;
use Carbon\Carbon;
use NoiThatZip\SalesTarget\Models\SalesTarget;
use App\User;

class WeekController extends Controller
{
	public function selectweek(Request $request)
	{
		return \DB::table('calendar_t')->get();
	}

	public function showroom(Request $request)
	{
		$idfullkh15 = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->where('costcenter_id','!=',39)->pluck('model_id');
		$idfullkhtn = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->where('costcenter_id','!=',39)->pluck('model_id');
		if($request->week == 0){
			$dt = Carbon::now('Asia/Ho_Chi_Minh');
        	$date = \DB::table('calendar_t')->whereDate('start_t','<=',$dt->toDateString())->whereDate('end_t','>=',$dt->toDateString())->first();
		}else{
			$date = \DB::table('calendar_t')->where('id_table',$request->week)->first();
		}
		$p = \DB::table('calendar')->where('id',$date->id_p)->first();
		$datep = [$p->start,$p->end];
		// dd($datep);
        $dates = [$date->start_t,$date->end_t];
        $end = $date->end_t;
        $start = $date->start_t;
        $users = User::filter(['dates'=>$dates])->get();
		$sales = $users->filter(function($user){
            return $user->hasRole('sales') ||  $user->hasRole('Marketing');
        });

    	$period = CarbonPeriod::create($start, $end);
    	$listdate = array('Tổng T');
    	$salesCos = $sales->map(function($sale) use($dates){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            return (object)[
                'id' => $sale->id,
                'name' => $sale->name,
                'costcenter' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->name : 'Khac',
                'idcos' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->id : 'Khac',
            ];
        });

        $user = User::find(\Auth::user()->id);
        $checksale = $user->hasRole('sales');
        $listcos = $user->costcentersList();


        $sr = $salesCos->values()->groupBy(function($salet){
            return $salet->costcenter;
        });
        // dd($sr);
        if ($checksale) {
	        foreach ($listcos as $key => $value) {
	        	$showroom[$value['name']] = $sr[$value['name']];
	        }
	        // return $showroom;
        }else{
        	if ($request->costcenters) {
        		$cos = Costcenter::whereIn('id',$request->costcenters)->where('id','!=',39)->orderBy('pos','asc')->get();
        		foreach ($cos as $key => $value) {
		        	$showroom[$value['name']] = $sr[$value['name']];
		        }
        	}else{
		        $cos = Costcenter::where('closed','>',$start)->where('id','!=',39)->orderBy('pos','asc')->get();
        		foreach ($cos as $key => $value) {
		        	$showroom[$value['name']] = $sr[$value['name']];
		        }
        	}
        }

        $idkhkh15 = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->whereIn('costcenter_id',$cos->pluck('id'))->get();
        $idkhkhtn = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->whereIn('costcenter_id',$cos->pluck('id'))->get();

        // return Contact::whereBetween('start_date', $datep)->whereIn('id',$idkhkhtn->pluck('model_id'));
        $tongcolumn['P']['khtn'] = Contact::whereBetween('start_date', $datep)->whereIn('id',$idkhkhtn->pluck('model_id'))->count();
        $tongcolumn['P']['kh15'] = Lead::whereBetween('start_date', $datep)->whereIn('id',$idkhkh15->pluck('model_id'))->count();
    	$tongcolumn['P']['order'] = Order::whereIn('costcenter',$cos->pluck('code'))->whereBetween('start_date', $datep)->count();
		$tongcolumn['P']['amount'] = Order::whereIn('costcenter',$cos->pluck('code'))->whereBetween('start_date', $datep)->sum('amount');

        foreach ($showroom as $key => $value) {
        	$khtong=array();$tachkhtong=array();$kh15tong=array();$dstong=array();$sodontong=array();
        	$khtongnv=array();$tachkhtongnv=array();$kh15tongnv=array();$dstongnv=array();$sodontongnv=array();
        	foreach ($period as $date) {
	        	$tachkh2 =0; $tachkh1 = 0;
	        	$ptn = 0; $pk15 = 0; $por = 0; $pam = 0;
	        	$ckhtn = 0; $ckh15 = 0; $corder = 0; $samount = 0;$tachkhtn = 0;
	        	foreach ($value as $khac => $v) {
	        		$target = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$v->id)->whereDate('close',$dates[1])->get();
	        		$targetP = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$v->id)->whereBetween('close',$datep)->get();
	        		$srtarget = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$v->idcos)->whereDate('close',$dates[1])->get();
	        		$srtargetP = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$v->idcos)->whereBetween('close',$datep)->get();
        			$idkhl = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->where('costcenter_id',$v->idcos)->pluck('model_id');
        			$idkhc = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->where('costcenter_id',$v->idcos)->pluck('model_id');



		        	$kh152 = Lead::where('user_id',$v->id)->whereIn('id',$idkhl)->whereBetween('start_date', $datep)->pluck('phone');
		        	$khtn2 = Contact::where('user_id',$v->id)->whereIn('id',$idkhc)->whereBetween('start_date', $datep)->where('loai',0)->whereIn('phone',$kh152)->count();
		        	$tongkhtn2 = Contact::where('user_id',$v->id)->whereIn('id',$idkhc)->whereIn('id',$idkhc)->where('loai',0)->whereBetween('start_date', $datep)->count();
		        	$order2 = Order::where('user_id',$v->id)->whereBetween('start_date', $datep)->count();
		        	$amount2 = Order::where('user_id',$v->id)->whereBetween('start_date', $datep)->sum('amount');

		        	// $kh151 = Lead::where('user_id',$v->id)->whereBetween('start_date', $dates)->pluck('phone');
		        	// $khtn1 = Contact::where('user_id',$v->id)->whereBetween('start_date', $dates)->whereIn('phone',$kh151)->count();
		        	// $tongkhtn1 = Contact::where('user_id',$v->id)->whereBetween('start_date', $dates)->count();
		        	// $order1 = Order::where('user_id',$v->id)->whereBetween('start_date', $dates)->count();
		        	// $amount1 = Order::where('user_id',$v->id)->whereBetween('start_date', $dates)->sum('amount');

		        	$data[$key][$v->name]['P']['khtn'] = $khtn2; 
		        	$data[$key][$v->name]['P']['tachkhtn'] = $tongkhtn2 - $khtn2; 
		        	$data[$key][$v->name]['P']['kh15'] = $kh152->count(); 
		        	$data[$key][$v->name]['P']['order'] = $order2; 
		        	$data[$key][$v->name]['P']['amount'] = $amount2;

		        	

		        	$data[$key][$v->name]['P']['tkhtn'] = $targetP->sum('number'); 
		        	$data[$key][$v->name]['P']['tkh15'] = $targetP->sum('kh15_number'); 
		        	$data[$key][$v->name]['P']['torder'] = $targetP->sum('order_number'); 
		        	$data[$key][$v->name]['P']['tamount'] = $targetP->sum('amount_number');
		        	$data[$key][$v->name]['P']['tlogin'] = \Auth::user()->id;

		        	$data[$key][$v->name]['Tổng']['tkhtn'] = $target->sum('number'); 
		        	$data[$key][$v->name]['Tổng']['tkh15'] = $target->sum('kh15_number'); 
		        	$data[$key][$v->name]['Tổng']['torder'] = $target->sum('order_number'); 
		        	$data[$key][$v->name]['Tổng']['tamount'] = $target->sum('amount_number');
		        	$data[$key][$v->name]['Tổng']['tlogin'] = \Auth::user()->id;

		        	$tachkh2 += $tongkhtn2 - $khtn2; 
		        	$pk15 += $kh152->count();
		        	$ptn += $khtn2; 
		        	$por += $order2; 
		        	$pam += $amount2;
		        	$data[$key][$key]['P']['tkhtn'] = $srtargetP->sum('number');  
		        	$data[$key][$key]['P']['tkh15'] = $srtargetP->sum('kh15_number'); 
		        	$data[$key][$key]['P']['torder'] = $srtargetP->sum('order_number');  
		        	$data[$key][$key]['P']['tamount'] = $srtargetP->sum('amount_number');
		        	$data[$key][$key]['P']['tlogin'] = \Auth::user()->id;

		        	$data[$key][$key]['Tổng']['tkhtn'] = $srtarget->sum('number');  
		        	$data[$key][$key]['Tổng']['tkh15'] = $srtarget->sum('kh15_number'); 
		        	$data[$key][$key]['Tổng']['torder'] = $srtarget->sum('order_number');  
		        	$data[$key][$key]['Tổng']['tamount'] = $srtarget->sum('amount_number');
		        	$data[$key][$key]['Tổng']['tlogin'] = \Auth::user()->id;

		        	$data[$key][$key]['P']['khtn'] = $ptn + $tachkh2; 
		        	$data[$key][$key]['P']['tachkhtn'] = $tachkh2; 
		        	$data[$key][$key]['P']['kh15'] = $pk15; 
		        	$data[$key][$key]['P']['order'] = $por; 
		        	$data[$key][$key]['P']['amount'] = $pam; 

	                $vdate = $date->format('Y-m-d');
		        	$kh15 = Lead::where('user_id',$v->id)->whereIn('id',$idkhl)->where('start_date', $vdate)->pluck('phone');
		        	$khtn = Contact::where('user_id',$v->id)->where('loai',0)->whereIn('id',$idkhc)->where('start_date', $vdate)->whereIn('phone',$kh15)->count();
		        	$tongkhtn = Contact::where('user_id',$v->id)->where('loai',0)->whereIn('id',$idkhc)->where('start_date', $vdate)->count();
		        	$order = Order::where('user_id',$v->id)->where('start_date', $vdate)->count();
		        	$amount = Order::where('user_id',$v->id)->where('start_date', $vdate)->sum('amount');
		        	$ckh15 += $kh15->count(); 
		        	$ckhtn += $khtn; 
		        	$corder += $order; 
		        	$samount += $amount; 
		        	$tachkhtn += $tongkhtn - $khtn;
		        	$data[$key][$v->name][$vdate]['tachkhtn'] = $tongkhtn - $khtn; 
		        	$data[$key][$v->name][$vdate]['khtn'] = $khtn; 
		        	$data[$key][$v->name][$vdate]['kh15'] = $kh15->count(); 
		        	$data[$key][$v->name][$vdate]['order'] = $order; 
		        	$data[$key][$v->name][$vdate]['amount'] = $amount; 
		        	$data[$key][$key][$vdate]['khtn'] = $ckhtn; 
		        	$data[$key][$key][$vdate]['kh15'] = $ckh15; 
		        	$data[$key][$key][$vdate]['order'] = $corder; 
		        	$data[$key][$key][$vdate]['amount'] = $samount; 
		        	$data[$key][$key][$vdate]['tachkhtn'] = $tachkhtn; 

		        	$khtong[$vdate] = $ckhtn;
		        	$tachkhtong[$vdate] = $tachkhtn;
		        	$kh15tong[$vdate] = $ckh15;
		        	$dstong[$vdate] = $corder;
		        	$sodontong[$vdate] = $samount;

		        	$khtongnv[$v->id][$vdate] = $khtn;
		        	$tachkhtongnv[$v->id][$vdate] = $tongkhtn - $khtn;
		        	$kh15tongnv[$v->id][$vdate] = $kh15->count();
		        	$dstongnv[$v->id][$vdate] = $order;
		        	$sodontongnv[$v->id][$vdate] = $amount;

		        	$data[$key][$key]['Tổng']['khtn'] = array_sum($khtong); 
		        	$data[$key][$key]['Tổng']['tachkhtn'] = array_sum($tachkhtong); 
		        	$data[$key][$key]['Tổng']['kh15'] = array_sum($kh15tong); 
		        	$data[$key][$key]['Tổng']['order'] = array_sum($dstong); 
		        	$data[$key][$key]['Tổng']['amount'] = array_sum($sodontong); 

		        	$data[$key][$v->name]['Tổng']['khtn'] = array_sum($khtongnv[$v->id]); 
		        	$data[$key][$v->name]['Tổng']['tachkhtn'] = array_sum($tachkhtongnv[$v->id]); 
		        	$data[$key][$v->name]['Tổng']['kh15'] = array_sum($kh15tongnv[$v->id]); 
		        	$data[$key][$v->name]['Tổng']['order'] = array_sum($dstongnv[$v->id]); 
		        	$data[$key][$v->name]['Tổng']['amount'] = array_sum($sodontongnv[$v->id]);
	            }
        	}
        }


        foreach ($showroom as $key => $value) {
        	$data_new[$key][$key] = $data[$key][$key];
        	$tong[$key] = $data[$key][$key];
        }
        $tongp = array_values($tong);
        // dd($tongp);

        if ($request->detail == 1) {
        	foreach ($showroom as $key => $value) {
        		unset($data[$key][$key]);
    			// $data_new[$key] = $data[$key];
    			foreach ($data[$key] as $k => $v) {
	    			$data_new[$key][$k] = $data[$key][$k];
    			}
	        }
        }

        $k1 = Lead::whereBetween('start_date', $datep)->whereIn('id',$idfullkh15)->pluck('phone');
        $p11 = Contact::whereIn('phone',$k1)->where('loai',0)->whereIn('id',$idfullkhtn)->count();
        $p12 = Contact::whereBetween('start_date', $datep)->whereIn('id',$idfullkhtn)->where('loai',0)->count();
        // $tongcolumn['Tổng']['khtn'] = $p11;
        // $tongcolumn['Tổng']['tachkhtn'] = $p12 - $p11;
        // $tongcolumn['Tổng']['kh15'] = $k1->count();
        // $tongcolumn['Tổng']['order'] = Order::whereBetween('start_date', $datep)->where('costcenter','!=','ON_MB')->count();
        // $tongcolumn['Tổng']['amount'] = Order::whereBetween('start_date', $datep)->where('costcenter','!=','ON_MB')->sum('amount');
        // $tongcolumn['Tổng']['login'] = \Auth::user()->id;
// dd(count($request->costcenters));
        if (Costcenter::whereDate('closed','2090-01-01')->count() === count($request->costcenters) + 1) {
	        // $tongcolumn['Tổng']['khtn'] = $p11;
	        $chay = 1;
	        // $tongcolumn['Tổng']['tachkhtn'] = $p12 - $p11;
	        // $tongcolumn['Tổng']['kh15'] = $k1->count();
	        // $tongcolumn['Tổng']['order'] = Order::whereBetween('start_date', $datep)->where('costcenter','ON_MB')->count();
	        // $tongcolumn['Tổng']['amount'] = Order::whereBetween('start_date', $datep)->where('costcenter','ON_MB')->sum('amount');
        }else{
	        $chay = 0;
        }

        // $k2 = Lead::whereBetween('start_date', $dates)->pluck('phone');
        // $p21 = Contact::whereBetween('start_date', $dates)->whereIn('phone',$k2)->count();
        // $p22 = Contact::whereBetween('start_date', $dates)->whereNotIn('phone',$k2)->count();
        $tongcolumn['Tổng']['khtn'] = 0;
        $tongcolumn['Tổng']['tachkhtn'] = 0;
        $tongcolumn['Tổng']['kh15'] = 0;
        $tongcolumn['Tổng']['order'] = 0;
        $tongcolumn['Tổng']['amount'] = 0;

        foreach ($period as $date) {
            $vdate = $date->format('l');
            $listdate[$date->format('Y-m-d')] = substr($vdate,0,3);
            $lead = Lead::whereDate('start_date', $date->format('Y-m-d'))->whereIn('id',$idfullkh15)->pluck('phone');
            $t1 = Contact::whereDate('start_date', $date->format('Y-m-d'))->whereIn('id',$idfullkhtn)->where('loai',0)->count();
            $t2 = Contact::whereDate('start_date', $date->format('Y-m-d'))->whereIn('id',$idfullkhtn)->where('loai',0)->whereIn('phone',$lead)->count();
            $tongcolumn[$date->format('Y-m-d')]['khtn'] = $t2;
            $tongcolumn[$date->format('Y-m-d')]['tachkhtn'] = $t1 - $t2;
	        $tongcolumn[$date->format('Y-m-d')]['kh15'] = $lead->count();
	        $tongcolumn[$date->format('Y-m-d')]['order'] = Order::whereDate('start_date', $date->format('Y-m-d'))->where('costcenter','!=','ON_MB')->count();
	        $tongcolumn[$date->format('Y-m-d')]['amount'] = Order::whereDate('start_date', $date->format('Y-m-d'))->where('costcenter','!=','ON_MB')->sum('amount');


	        $tongcolumn['Tổng']['khtn'] += $tongcolumn[$date->format('Y-m-d')]['khtn'];
	        $tongcolumn['Tổng']['tachkhtn'] += $tongcolumn[$date->format('Y-m-d')]['tachkhtn'];
	        $tongcolumn['Tổng']['kh15'] += $tongcolumn[$date->format('Y-m-d')]['kh15'];
	        $tongcolumn['Tổng']['order'] += $tongcolumn[$date->format('Y-m-d')]['order'];
	        $tongcolumn['Tổng']['amount'] += $tongcolumn[$date->format('Y-m-d')]['amount'];
	        
            $lich = \DB::table('calendar_t')->whereDate('start_t','<=',$date->format('Y-m-d'))->whereDate('end_t','>=',$date->format('Y-m-d'))->first();
            $group[$lich->t][] = 1; 
            $sum[$lich->t] = array_sum($group[$lich->t]);
        }
		return ['date' => $listdate, 'group' => $sum, 'data' => $data_new, 'tong' => $tongcolumn,'chay' => $chay];
	}

	public function online(Request $request)
	{
		$idfullkh15 = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->where('costcenter_id',39)->pluck('model_id');
		$idfullkhtn = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->where('costcenter_id',39)->pluck('model_id');
		if($request->week == 0){
			$dt = Carbon::now('Asia/Ho_Chi_Minh');
        	$date = \DB::table('calendar_t')->whereDate('start_t','<=',$dt->toDateString())->whereDate('end_t','>=',$dt->toDateString())->first();
		}else{
			$date = \DB::table('calendar_t')->where('id_table',$request->week)->first();
		}
		$p = \DB::table('calendar')->where('id',$date->id_p)->first();
		$datep = [$p->start,$p->end];
		// dd($datep);
        $dates = [$date->start_t,$date->end_t];
        $end = $date->end_t;
        $start = $date->start_t;
        $users = User::filter(['dates'=>$dates])->get();
		$sales = $users->filter(function($user){
            return $user->hasRole('sales') ||  $user->hasRole('Marketing');
        });

    	$period = CarbonPeriod::create($start, $end);
    	$listdate = array('Tổng T');
    	$salesCos = $sales->map(function($sale) use($dates){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            return (object)[
                'id' => $sale->id,
                'name' => $sale->name,
                'costcenter' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->name : 'Khac',
                'idcos' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->id : 'Khac',
            ];
        });

        $user = User::find(\Auth::user()->id);
        $checksale = $user->hasRole('sales');
        $listcos = $user->costcentersList();


        $sr = $salesCos->values()->groupBy(function($salet){
            return $salet->costcenter;
        });
        // dd($sr);
        if ($checksale) {
	        foreach ($listcos as $key => $value) {
	        	$showroom[$value['name']] = $sr[$value['name']];
	        }
	        // return $showroom;
        }else{
        	if ($request->costcenters) {
        		$cos = Costcenter::whereIn('id',$request->costcenters)->where('id',39)->orderBy('pos','asc')->get();
        		foreach ($cos as $key => $value) {
		        	$showroom[$value['name']] = $sr[$value['name']];
		        }
        	}else{
		        $cos = Costcenter::where('closed','>',$start)->where('id',39)->orderBy('pos','asc')->get();
        		foreach ($cos as $key => $value) {
		        	$showroom[$value['name']] = $sr[$value['name']];
		        }
        	}
        }

        foreach ($showroom as $key => $value) {
        	$khtong=array();$tachkhtong=array();$kh15tong=array();$dstong=array();$sodontong=array();
        	$khtongnv=array();$tachkhtongnv=array();$kh15tongnv=array();$dstongnv=array();$sodontongnv=array();
        	foreach ($period as $date) {
	        	$tachkh2 =0; $tachkh1 = 0;
	        	$ptn = 0; $pk15 = 0; $por = 0; $pam = 0;
	        	$ckhtn = 0; $ckh15 = 0; $corder = 0; $samount = 0;$tachkhtn = 0;
	        	foreach ($value as $khac => $v) {
	        		$target = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$v->id)->whereDate('close',$dates[1])->get();
	        		$targetP = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$v->id)->whereBetween('close',$datep)->get();
	        		$srtarget = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$v->idcos)->whereDate('close',$dates[1])->get();
	        		$srtargetP = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$v->idcos)->whereBetween('close',$datep)->get();
        			$idkhl = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Lead\Models\Lead')->where('costcenter_id',$v->idcos)->pluck('model_id');
        			$idkhc = \DB::table('model_has_costcenters')->where('model_type','NoiThatZip\Contact\Models\Contact')->where('costcenter_id',$v->idcos)->pluck('model_id');

		        	$kh152 = Lead::where('user_id',$v->id)->whereIn('id',$idkhl)->whereBetween('start_date', $datep)->pluck('phone');
		        	$khtn2 = Contact::where('user_id',$v->id)->whereIn('id',$idkhc)->whereBetween('start_date', $datep)->where('loai',0)->whereIn('phone',$kh152)->count();
		        	$tongkhtn2 = Contact::where('user_id',$v->id)->whereIn('id',$idkhc)->whereIn('id',$idkhc)->where('loai',0)->whereBetween('start_date', $datep)->count();
		        	$order2 = Order::where('user_id',$v->id)->whereBetween('start_date', $datep)->count();
		        	$amount2 = Order::where('user_id',$v->id)->whereBetween('start_date', $datep)->sum('amount');

		        	// $kh151 = Lead::where('user_id',$v->id)->whereBetween('start_date', $dates)->pluck('phone');
		        	// $khtn1 = Contact::where('user_id',$v->id)->whereBetween('start_date', $dates)->whereIn('phone',$kh151)->count();
		        	// $tongkhtn1 = Contact::where('user_id',$v->id)->whereBetween('start_date', $dates)->count();
		        	// $order1 = Order::where('user_id',$v->id)->whereBetween('start_date', $dates)->count();
		        	// $amount1 = Order::where('user_id',$v->id)->whereBetween('start_date', $dates)->sum('amount');

		        	$data[$key][$v->name]['P']['khtn'] = $khtn2; 
		        	$data[$key][$v->name]['P']['tachkhtn'] = $tongkhtn2 - $khtn2; 
		        	$data[$key][$v->name]['P']['kh15'] = $kh152->count(); 
		        	$data[$key][$v->name]['P']['order'] = $order2; 
		        	$data[$key][$v->name]['P']['amount'] = $amount2;

		        	

		        	$data[$key][$v->name]['P']['tkhtn'] = $targetP->sum('number'); 
		        	$data[$key][$v->name]['P']['tkh15'] = $targetP->sum('kh15_number'); 
		        	$data[$key][$v->name]['P']['torder'] = $targetP->sum('order_number'); 
		        	$data[$key][$v->name]['P']['tamount'] = $targetP->sum('amount_number');
		        	$data[$key][$v->name]['P']['tlogin'] = \Auth::user()->id;

		        	$data[$key][$v->name]['Tổng']['tkhtn'] = $target->sum('number'); 
		        	$data[$key][$v->name]['Tổng']['tkh15'] = $target->sum('kh15_number'); 
		        	$data[$key][$v->name]['Tổng']['torder'] = $target->sum('order_number'); 
		        	$data[$key][$v->name]['Tổng']['tamount'] = $target->sum('amount_number');
		        	$data[$key][$v->name]['Tổng']['tlogin'] = \Auth::user()->id;

		        	$tachkh2 += $tongkhtn2 - $khtn2; 
		        	$pk15 += $kh152->count();
		        	$ptn += $khtn2; 
		        	$por += $order2; 
		        	$pam += $amount2;
		        	$data[$key][$key]['P']['tkhtn'] = $srtargetP->sum('number');  
		        	$data[$key][$key]['P']['tkh15'] = $srtargetP->sum('kh15_number'); 
		        	$data[$key][$key]['P']['torder'] = $srtargetP->sum('order_number');  
		        	$data[$key][$key]['P']['tamount'] = $srtargetP->sum('amount_number');
		        	$data[$key][$key]['P']['tlogin'] = \Auth::user()->id;

		        	$data[$key][$key]['Tổng']['tkhtn'] = $srtarget->sum('number');  
		        	$data[$key][$key]['Tổng']['tkh15'] = $srtarget->sum('kh15_number'); 
		        	$data[$key][$key]['Tổng']['torder'] = $srtarget->sum('order_number');  
		        	$data[$key][$key]['Tổng']['tamount'] = $srtarget->sum('amount_number');
		        	$data[$key][$key]['Tổng']['tlogin'] = \Auth::user()->id;

		        	$data[$key][$key]['P']['khtn'] = $ptn; 
		        	$data[$key][$key]['P']['tachkhtn'] = $tachkh2; 
		        	$data[$key][$key]['P']['kh15'] = $pk15; 
		        	$data[$key][$key]['P']['order'] = $por; 
		        	$data[$key][$key]['P']['amount'] = $pam; 

	                $vdate = $date->format('Y-m-d');
		        	$kh15 = Lead::where('user_id',$v->id)->whereIn('id',$idkhl)->where('start_date', $vdate)->pluck('phone');
		        	$khtn = Contact::where('user_id',$v->id)->where('loai',0)->whereIn('id',$idkhc)->where('start_date', $vdate)->whereIn('phone',$kh15)->count();
		        	$tongkhtn = Contact::where('user_id',$v->id)->where('loai',0)->whereIn('id',$idkhc)->where('start_date', $vdate)->count();
		        	$order = Order::where('user_id',$v->id)->where('start_date', $vdate)->count();
		        	$amount = Order::where('user_id',$v->id)->where('start_date', $vdate)->sum('amount');
		        	$ckh15 += $kh15->count(); 
		        	$ckhtn += $khtn; 
		        	$corder += $order; 
		        	$samount += $amount; 
		        	$tachkhtn += $tongkhtn - $khtn;
		        	$data[$key][$v->name][$vdate]['tachkhtn'] = $tongkhtn - $khtn; 
		        	$data[$key][$v->name][$vdate]['khtn'] = $khtn; 
		        	$data[$key][$v->name][$vdate]['kh15'] = $kh15->count(); 
		        	$data[$key][$v->name][$vdate]['order'] = $order; 
		        	$data[$key][$v->name][$vdate]['amount'] = $amount; 
		        	$data[$key][$key][$vdate]['khtn'] = $ckhtn; 
		        	$data[$key][$key][$vdate]['kh15'] = $ckh15; 
		        	$data[$key][$key][$vdate]['order'] = $corder; 
		        	$data[$key][$key][$vdate]['amount'] = $samount; 
		        	$data[$key][$key][$vdate]['tachkhtn'] = $tachkhtn; 

		        	$khtong[$vdate] = $ckhtn;
		        	$tachkhtong[$vdate] = $tachkhtn;
		        	$kh15tong[$vdate] = $ckh15;
		        	$dstong[$vdate] = $corder;
		        	$sodontong[$vdate] = $samount;

		        	$khtongnv[$v->id][$vdate] = $khtn;
		        	$tachkhtongnv[$v->id][$vdate] = $tongkhtn - $khtn;
		        	$kh15tongnv[$v->id][$vdate] = $kh15->count();
		        	$dstongnv[$v->id][$vdate] = $order;
		        	$sodontongnv[$v->id][$vdate] = $amount;

		        	$data[$key][$key]['Tổng']['khtn'] = array_sum($khtong); 
		        	$data[$key][$key]['Tổng']['tachkhtn'] = array_sum($tachkhtong); 
		        	$data[$key][$key]['Tổng']['kh15'] = array_sum($kh15tong); 
		        	$data[$key][$key]['Tổng']['order'] = array_sum($dstong); 
		        	$data[$key][$key]['Tổng']['amount'] = array_sum($sodontong); 

		        	$data[$key][$v->name]['Tổng']['khtn'] = array_sum($khtongnv[$v->id]); 
		        	$data[$key][$v->name]['Tổng']['tachkhtn'] = array_sum($tachkhtongnv[$v->id]); 
		        	$data[$key][$v->name]['Tổng']['kh15'] = array_sum($kh15tongnv[$v->id]); 
		        	$data[$key][$v->name]['Tổng']['order'] = array_sum($dstongnv[$v->id]); 
		        	$data[$key][$v->name]['Tổng']['amount'] = array_sum($sodontongnv[$v->id]);
	            }
        	}
        }


        foreach ($showroom as $key => $value) {
        	$data_new[$key][$key] = $data[$key][$key];
        	$tong[$key] = $data[$key][$key];
        }
        $tongp = array_values($tong);
        // dd($tongp);

        if ($request->detail == 1) {
        	foreach ($showroom as $key => $value) {
        		unset($data[$key][$key]);
    			// $data_new[$key] = $data[$key];
    			foreach ($data[$key] as $k => $v) {
	    			$data_new[$key][$k] = $data[$key][$k];
    			}
	        }
        }

        $k1 = Lead::whereBetween('start_date', $datep)->whereIn('id',$idfullkh15)->pluck('phone');
        $p11 = Contact::whereIn('id',$idfullkhtn)->whereIn('phone',$k1)->where('loai',0)->count();
        $p12 = Contact::whereIn('id',$idfullkhtn)->whereBetween('start_date', $datep)->where('loai',0)->count();
        
	        // $tongcolumn['Tổng']['chay'] = $p11;
	        // $tongcolumn['Tổng']['tachkhtn'] = $p12 - $p11;
	        // $tongcolumn['Tổng']['kh15'] = $k1->count();
	        // $tongcolumn['Tổng']['order'] = Order::whereBetween('start_date', $datep)->where('costcenter','ON_MB')->count();
	        // $tongcolumn['Tổng']['amount'] = Order::whereBetween('start_date', $datep)->where('costcenter','ON_MB')->sum('amount');
	        // $tongcolumn['Tổng']['chay'] = 0;

        // $k2 = Lead::whereBetween('start_date', $dates)->pluck('phone');
        // $p21 = Contact::whereBetween('start_date', $dates)->whereIn('phone',$k2)->count();
        // $p22 = Contact::whereBetween('start_date', $dates)->whereNotIn('phone',$k2)->count();
        $tongcolumn['P']['khtn'] = 0;
        $tongcolumn['P']['tachkhtn'] = 0;
        $tongcolumn['P']['kh15'] = 0;
        $tongcolumn['P']['order'] = 0;
        $tongcolumn['P']['amount'] = 0;

        foreach ($period as $date) {
            $vdate = $date->format('l');
            $listdate[$date->format('Y-m-d')] = substr($vdate,0,3);
            $lead = Lead::whereDate('start_date', $date->format('Y-m-d'))->whereIn('id',$idfullkh15)->pluck('phone');
            $t1 = Contact::whereDate('start_date', $date->format('Y-m-d'))->whereIn('id',$idfullkhtn)->where('loai',0)->count();
            $t2 = Contact::whereDate('start_date', $date->format('Y-m-d'))->whereIn('id',$idfullkhtn)->where('loai',0)->whereIn('phone',$lead)->count();
            $tongcolumn[$date->format('Y-m-d')]['khtn'] = $t2;
            $tongcolumn[$date->format('Y-m-d')]['tachkhtn'] = $t1 - $t2;
	        $tongcolumn[$date->format('Y-m-d')]['kh15'] = $lead->count();
	        $tongcolumn[$date->format('Y-m-d')]['order'] = Order::whereDate('start_date', $date->format('Y-m-d'))->where('costcenter','ON_MB')->count();
	        $tongcolumn[$date->format('Y-m-d')]['amount'] = Order::whereDate('start_date', $date->format('Y-m-d'))->where('costcenter','ON_MB')->sum('amount');


	        $tongcolumn['P']['khtn'] += $tongcolumn[$date->format('Y-m-d')]['khtn'];
	        $tongcolumn['P']['tachkhtn'] += $tongcolumn[$date->format('Y-m-d')]['tachkhtn'];
	        $tongcolumn['P']['kh15'] += $tongcolumn[$date->format('Y-m-d')]['kh15'];
	        $tongcolumn['P']['order'] += $tongcolumn[$date->format('Y-m-d')]['order'];
	        $tongcolumn['P']['amount'] += $tongcolumn[$date->format('Y-m-d')]['amount'];

            $lich = \DB::table('calendar_t')->whereDate('start_t','<=',$date->format('Y-m-d'))->whereDate('end_t','>=',$date->format('Y-m-d'))->first();
            $group[$lich->t][] = 1; 
            $sum[$lich->t] = array_sum($group[$lich->t]);
        }
		return ['date' => $listdate, 'group' => $sum, 'data' => $data_new, 'tong' => $tongcolumn];
	}

	public function select()
	{
		$dt = Carbon::now('Asia/Ho_Chi_Minh');
        return \DB::table('calendar_t')->whereDate('start_t','<=',$dt->toDateString())->whereDate('end_t','>=',$dt->toDateString())->get();
	}
}