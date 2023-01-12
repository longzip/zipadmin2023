<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use NoiThatZip\Contact\Models\Contact;
use NoiThatZip\Lead\Models\Lead;
use App\Http\Resources\Contact as ContactResource;
use App\Http\Resources\Lead as LeadResource;
use App\Http\Resources\Costcenter as CostcenterResource;
use App\Http\Resources\User as UserResource;
use NoiThatZip\Costcenter\Models\Costcenter;
use DB;
use Carbon\Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use NoiThatZip\SalesTarget\Models\SalesTarget;
use App\Order;
use App\Target;

class TargetShowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        // $this->middleware(['role_or_permission:super-admin|edit users']);
    }
  
    public function getRoles($id){
        return User::find($id)->getRoleNames();
    }

    public function checksales($id){
        $check = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',2)->get();
        if($check->isEmpty()) {
            $check = 0;
        }else {
            $check = 1;
        }
        return $check;
    }

    public function checksm($id){
        $check = \DB::table('model_has_roles')->where('model_id',$id)->where('role_id',6)->get();
        if($check->isEmpty()) {
            $check = 0;
        }else {
            $check = 1;
        }
        return $check;
    }

    public function costcenters(){
        $user = auth('api')->user();
        $costcenters = [];
        if ($user->can('import')) {
            $costcenters = Costcenter::select('id', 'name')->get();
        }else{
            $costcenters = $user->costcentersList();
        }
        return $costcenters;
    }

    public function sales(){
        $sales = User::all()->filter(function($user){
            return $user->hasRole('sales');
        });
        return $sales;
    }
    
    protected function findUser($id,$loc,$date){
        $user = User::select('id', 'name')
            ->whereIn('id',$loc)->whereDate('date_off','>',$date)
            ->where(function ($query) use ($id){
                $query->orWhereIn('id', Costcenter::find($id)->entries(User::class)->pluck('id'));
            })
        ->distinct()
        ->get();
        return $user;
    }
    
    public function targetLeads(Request $request){
        $dangnhap = \Auth::user()->id;
        $dates = $request['dates'];
        $iduserSR = \DB::table('model_has_costcenters')->where('model_id',\Auth::user()->id)->where('model_type','App\User')->pluck('costcenter_id');
        $iduserSR = ($iduserSR->isEmpty()) ? array(1) : json_decode($iduserSR);
        $r_costcenter = isset($request['costcenter']) ? $request['costcenter'] : $iduserSR;
        
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        if($start_week > 52){
            if($end_week == 3){
                $weeks = collect([$start_week, 1,2,3]);
            }else{
                $weeks = collect([$start_week, 54,1,2]);
            }
            
        }else{
            $weeks = collect(range($start_week, $end_week));
        }
        $contacts = Lead::filter($request->all())->get();
        $contacts = $contacts->groupBy(function($contact){
           return $contact->user_id;
        });

        $loc = \DB::table('model_has_roles')->where('role_id',2)->pluck('model_id');
        if (!$request['costcenter']) {
            $danhsach = User::whereIn('id',$loc)->where('date_off','>',$dates[0])->pluck('id');
        }else{
            $danhsach = User::select('id', 'name')
                ->where('id','>',0)->whereIn('id',$loc)->where('date_off','>',$dates[0])
                ->where(function ($query) use ($request){
                    foreach ($request['costcenter'] as $costcenter) {
                        $query->orWhereIn('id', Costcenter::find($costcenter)->entries(User::class)->pluck('id'));
                    }
                })
            ->distinct()
            ->pluck('id');
        }

        $ds = array_diff($danhsach->toArray(), $contacts->keys()->toArray());
      

        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $ss1 = strtotime($dates[0]);

        $hienthi = 1;
        if ($dangnhap != 9003 && $dangnhap != 9074) {
            if (strtotime($dt->toDateString()) <  $ss1) { 
                $role_watch = \DB::table('roles_target')->where('d',$dates[0])->where('user_id',$dangnhap)->get();
                if ($role_watch->isEmpty()) {
                    $hienthi = 0;
                }
            }
        }

        $contacts = $contacts->map(function($contactsList,$key){
            return $contactsList->countBy(function($contact){
                $date = new Carbon($contact->start_date);
                return $date->weekOfYear;
            });
        });

        foreach($ds as $c){
            $contacts[$c] = $contacts->first();
        }
// return $contacts;
        
        $year = date('Y',strtotime($dates[1]));
        $contacts = $this->targetByWeekLeads($contacts, $weeks, $year);
        $sales = User::all()->filter(function($user){
            return $user->hasRole('sales');
        });
            $costcenter = Costcenter::select('id','name')->get();
            foreach ($costcenter as $k => $v) {
                $find = $this->findUser($v->id,$sales->pluck('id'),$dates[0]);
                foreach ($find as $key => $value) {
                    $idtest = $value->id;
                    $noContact = $weeks->map(function($week) use ($idtest,$year){
                        return [ 'w' => $week,
                        't' => 0, 
                        'c' => 0, 
                        'monday' => '.',
                        'tuesday' =>  '.',
                        'wednesday' =>  '.',
                        'thursday' =>  '.',
                        'friday' =>  '.',
                        'saturday' =>  '.',
                        'sunday' =>  '.', 
                        'sumc' => 0, 
                        'chien' => 1, 
                        'sumd' => 0 ];
                    });
                    $infouser = isset($contacts[json_decode($find)[$key]->id]) ? $contacts[json_decode($find)[$key]->id] : $noContact;
                    
                    $merge[]= array('costcenter' => $v->name,
                                    'id' => $v->id,
                                    'name' =>  [
                                        'name' => json_decode($find)[$key]->name ,
                                        'contacts' => $infouser,
                                        't' => $infouser->sum('t'),
                                        'c' => $infouser->sum('c'),
                                        'sm' => $this->checksm(json_decode($find)[$key]->id),
                                        'cdahlia' => $infouser->sum('cdahlia'),
                                        'civy' => $infouser->sum('civy'),
                                        'ciris' => $infouser->sum('ciris'),
                                        'ckhac' => $infouser->sum('ckhac'),
                                        'tdahlia' => $infouser->sum('tdahlia'),
                                        'tivy' => $infouser->sum('tivy'),
                                        'tiris' => $infouser->sum('tiris'),
                                        'tkhac' => $infouser->sum('tkhac'),
                                    ]
                                );
                }
            }
            foreach ($merge as $key1 => $value1) {
                $a[$value1['id']][] = $value1['name'];
            }
            $idcos = Costcenter::where('closed','<',$dates[0])->pluck('id')->toArray();
            $r_costcenter = array_diff( $r_costcenter, $idcos );
            foreach ($r_costcenter as $idShowroom) {
                $c = array();
                $name = array();
                $v1 = array();
                foreach ($a[$idShowroom] as $k1 => $v1) {
                    $c[] = $v1;
                    $i = array();
                    foreach ($c as $key22 => $value22) {
                        $i[$key22] = $value22['contacts'];
                    }
                    
                    $sumt = array(); $sum1t = array(); $sum2t = array(); $sum3t = array(); $sum4t = array(); 
                    $sumc = array(); $sum1c = array(); $sum2c = array(); $sum3c = array(); $sum4c = array(); 
                    $sum1dd = array();$sum1dc = array();$sum2dd = array();$sum2dc = array();
                    $sum3dd = array();$sum3dc = array();$sum4dd = array();$sum4dc = array();
                    $sumM1 = array();$sumM2 = array();$sumM3 = array();$sumM4 = array();
                    $sumTU1 = array();$sumTU2 = array();$sumTU3 = array();$sumTU4 = array();
                    $sumSU1 = array();$sumSU2 = array();$sumSU3 = array();$sumSU4 = array();
                    $sumSA1 = array();$sumSA2 = array();$sumSA3 = array();$sumSA4 = array();
                    $sumTH1 = array();$sumTH2 = array();$sumTH3 = array();$sumTH4 = array();
                    $sumF1 = array();$sumF2 = array();$sumF3 = array();$sumF4 = array();
                    $sumW1 = array();$sumW2 = array();$sumW3 = array();$sumW4 = array();
                    $sumDa1 = array();$sumDa2 = array();$sumDa3 = array();$sumDa4 = array();
                    $sumIv1 = array();$sumIv2 = array();$sumIv3 = array();$sumIv4 = array();
                    $sumIr1 = array();$sumIr2 = array();$sumIr3 = array();$sumIr4 = array();
                    $sumK1 = array();$sumK2 = array();$sumK3 = array();$sumK4 = array();
                    $sumDat1 = array();$sumDat2 = array();$sumDat3 = array();$sumDat4 = array();
                    $sumIvt1 = array();$sumIvt2 = array();$sumIvt3 = array();$sumIvt4 = array();
                    $sumIrt1 = array();$sumIrt2 = array();$sumIrt3 = array();$sumIrt4 = array();
                    $sumKt1 = array();$sumKt2 = array();$sumKt3 = array();$sumKt4 = array();

                    foreach ($i as $k=>$subArray) {
                        foreach ($subArray as $id=>$value12) {
                            $sumArray[] = $value12['w'];
                            if($value12['w'] == $sumArray[0]) {
                                $sum1t[] = $value12['t']; 
                                $sum1c[] = $value12['c'];
                                $sum1dd[] = $value12['sumd'];
                                $sum1dc[] = $value12['sumc'];
                                $sumM1[] = $value12['monday'];
                                $sumTU1[] = $value12['tuesday'];
                                $sumW1[] = $value12['wednesday'];
                                $sumSU1[] = $value12['sunday'];
                                $sumSA1[] = $value12['saturday'];
                                $sumTH1[] = $value12['thursday'];
                                $sumF1[] = $value12['friday'];
                                $sumDa1[] = isset($value12['cdahlia']) ? $value12['cdahlia'] : 0;
                                $sumIv1[] = isset($value12['civy']) ? $value12['civy'] : 0;
                                $sumIr1[] = isset($value12['ciris']) ? $value12['ciris'] : 0;
                                $sumK1[] = isset($value12['ckhac']) ? $value12['ckhac'] : 0;
                                $sumDat1[] = isset($value12['tdahlia']) ? $value12['tdahlia'] : 0;
                                $sumIvt1[] = isset($value12['tivy']) ? $value12['tivy'] : 0;
                                $sumIrt1[] = isset($value12['tiris']) ? $value12['tiris'] : 0;
                                $sumKt1[] = isset($value12['tkhac']) ? $value12['tkhac'] : 0;
                            }
                            if($value12['w'] == $sumArray[0]+1) {
                                $sum2t[] = $value12['t']; 
                                $sum2c[] = $value12['c'];
                                $sum2dd[] = $value12['sumd'];
                                $sum2dc[] = $value12['sumc'];
                                $sumM2[] = $value12['monday'];
                                $sumTU2[] = $value12['tuesday'];
                                $sumW2[] = $value12['wednesday'];
                                $sumSU2[] = $value12['sunday'];
                                $sumSA2[] = $value12['saturday'];
                                $sumTH2[] = $value12['thursday'];
                                $sumF2[] = $value12['friday'];
                                $sumDa2[] = isset($value12['cdahlia']) ? $value12['cdahlia'] : 0;
                                $sumIv2[] = isset($value12['civy']) ? $value12['civy'] : 0;
                                $sumIr2[] = isset($value12['ciris']) ? $value12['ciris'] : 0;
                                $sumK2[] = isset($value12['ckhac']) ? $value12['ckhac'] : 0;
                                $sumDat2[] = isset($value12['tdahlia']) ? $value12['tdahlia'] : 0;
                                $sumIvt2[] = isset($value12['tivy']) ? $value12['tivy'] : 0;
                                $sumIrt2[] = isset($value12['tiris']) ? $value12['tiris'] : 0;
                                $sumKt2[] = isset($value12['tkhac']) ? $value12['tkhac'] : 0;
                            }
                            if($value12['w'] == $sumArray[0]+2) {
                                $sum3t[] = $value12['t']; 
                                $sum3c[] = $value12['c'];
                                $sum3dd[] = $value12['sumd']; 
                                $sum3dc[] = $value12['sumc'];
                                $sumM3[] = $value12['monday'];
                                $sumTU3[] = $value12['tuesday'];
                                $sumW3[] = $value12['wednesday'];
                                $sumSU3[] = $value12['sunday'];
                                $sumSA3[] = $value12['saturday'];
                                $sumTH3[] = $value12['thursday'];
                                $sumF3[] = $value12['friday'];
                                $sumDa3[] = isset($value12['cdahlia']) ? $value12['cdahlia'] : 0;
                                $sumIv3[] = isset($value12['civy']) ? $value12['civy'] : 0;
                                $sumIr3[] = isset($value12['ciris']) ? $value12['ciris'] : 0;
                                $sumK3[] = isset($value12['ckhac']) ? $value12['ckhac'] : 0;
                                $sumDat3[] = isset($value12['tdahlia']) ? $value12['tdahlia'] : 0;
                                $sumIvt3[] = isset($value12['tivy']) ? $value12['tivy'] : 0;
                                $sumIrt3[] = isset($value12['tiris']) ? $value12['tiris'] : 0;
                                $sumKt3[] = isset($value12['tkhac']) ? $value12['tkhac'] : 0;
                            }
                            if($value12['w'] == $sumArray[0]+3) {
                                $sum4t[] = $value12['t']; 
                                $sum4c[] = $value12['c']; 
                                $sum4dc[] = $value12['sumc'];
                                $sum4dd[] = $value12['sumd'];
                                $sumM4[] = $value12['monday'];
                                $sumTU4[] = $value12['tuesday'];
                                $sumW4[] = $value12['wednesday'];
                                $sumSU4[] = $value12['sunday'];
                                $sumSA4[] = $value12['saturday'];
                                $sumTH4[] = $value12['thursday'];
                                $sumF4[] = $value12['friday'];
                                $sumDa4[] = isset($value12['cdahlia']) ? $value12['cdahlia'] : 0;
                                $sumIv4[] = isset($value12['civy']) ? $value12['civy'] : 0;
                                $sumIr4[] = isset($value12['ciris']) ? $value12['ciris'] : 0;
                                $sumK4[] = isset($value12['ckhac']) ? $value12['ckhac'] : 0;
                                $sumDat4[] = isset($value12['tdahlia']) ? $value12['tdahlia'] : 0;
                                $sumIvt4[] = isset($value12['tivy']) ? $value12['tivy'] : 0;
                                $sumIrt4[] = isset($value12['tiris']) ? $value12['tiris'] : 0;
                                $sumKt4[] = isset($value12['tkhac']) ? $value12['tkhac'] : 0;
                            }
                        }
                        $t1 = array_sum($sum1t);
                        $t2 = array_sum($sum2t);
                        $t3 = array_sum($sum3t);
                        $t4 = array_sum($sum4t);
                        $sumt = $t1 + $t2 + $t3 + $t4;
                        $c1 = array_sum($sum1c);
                        $c2 = array_sum($sum2c);
                        $c3 = array_sum($sum3c);
                        $c4 = array_sum($sum4c);
                        $sumc = $c1 + $c2 + $c3 + $c4; 
                        $sumdc1 = array_sum($sum1dc);
                        $sumdc2 = array_sum($sum2dc);
                        $sumdc3 = array_sum($sum3dc);
                        $sumdc4 = array_sum($sum4dc);
                        $sumdc = $sumdc1 + $sumdc2 + $sumdc3 + $sumdc4;
                        $sumdd1 = array_sum($sum1dd);
                        $sumdd2 = array_sum($sum2dd);
                        $sumdd3 = array_sum($sum3dd);
                        $sumdd4 = array_sum($sum4dd);
                        $sumdd = $sumdd1 + $sumdd2 + $sumdd3 + $sumdd4;
                        $sumMt1 = array_sum($sumM1);$sumMt2 = array_sum($sumM2);$sumMt3 = array_sum($sumM3);$sumMt4 = array_sum($sumM4);
                        $sumTUt1 = array_sum($sumTU1);$sumTUt2 = array_sum($sumTU2);$sumTUt3 = array_sum($sumTU3);$sumTUt4 = array_sum($sumTU4);
                        $sumWt1 = array_sum($sumW1);$sumWt2 = array_sum($sumW2);$sumWt3 = array_sum($sumW3);$sumWt4 = array_sum($sumW4);
                        $sumSUt1 = array_sum($sumSU1);$sumSUt2 = array_sum($sumSU2);$sumSUt3 = array_sum($sumSU3);$sumSUt4 = array_sum($sumSU4);
                        $sumSAt1 = array_sum($sumSA1);$sumSAt2 = array_sum($sumSA2);$sumSAt3 = array_sum($sumSA3);$sumSAt4 = array_sum($sumSA4);
                        $sumTHt1 = array_sum($sumTH1);$sumTHt2 = array_sum($sumTH2);$sumTHt3 = array_sum($sumTH3);$sumTHt4 = array_sum($sumTH4);
                        $sumFt1 = array_sum($sumF1);$sumFt2 = array_sum($sumF2);$sumFt3 = array_sum($sumF3);$sumFt4 = array_sum($sumF4);
                        $sumDah1 = array_sum($sumDa1);$sumDah2 = array_sum($sumDa2);$sumDah3 = array_sum($sumDa3);$sumDah4 = array_sum($sumDa4);
                        $cdahlia = $sumDah1 + $sumDah2 + $sumDah3 + $sumDah4; 
                        $sumivy1 = array_sum($sumIv1);$sumivy2 = array_sum($sumIv2);$sumivy3 = array_sum($sumIv3);$sumivy4 = array_sum($sumIv4);
                        $civy = $sumivy1 + $sumivy2 + $sumivy3 + $sumivy4; 
                        $sumiris1 = array_sum($sumIr1);$sumiris2 = array_sum($sumIr2);$sumiris3 = array_sum($sumIr3);$sumiris4 = array_sum($sumIr4);
                        $ciris = $sumiris1 + $sumiris2 + $sumiris3 + $sumiris4; 
                        $sumkh1 = array_sum($sumK1);$sumkh2 = array_sum($sumK2);$sumkh3 = array_sum($sumK3);$sumkh4 = array_sum($sumK4);
                        $ckhac = $sumkh1 + $sumkh2 + $sumkh3 + $sumkh4;

                        $sumDaht1 = array_sum($sumDat1);$sumDaht2 = array_sum($sumDat2);$sumDaht3 = array_sum($sumDat3);$sumDaht4 = array_sum($sumDat4);
                        $tdahlia = $sumDaht1 + $sumDaht2 + $sumDaht3 + $sumDaht4; 
                        $sumivyt1 = array_sum($sumIvt1);$sumivyt2 = array_sum($sumIvt2);$sumivyt3 = array_sum($sumIvt3);$sumivyt4 = array_sum($sumIvt4);
                        $tivy = $sumivyt1 + $sumivyt2 + $sumivyt3 + $sumivyt4; 
                        $sumirist1 = array_sum($sumIrt1);$sumirist2 = array_sum($sumIrt2);$sumirist3 = array_sum($sumIrt3);$sumirist4 = array_sum($sumIrt4);
                        $tiris = $sumirist1 + $sumirist2 + $sumirist3 + $sumirist4; 
                        $sumkht1 = array_sum($sumKt1);$sumkht2 = array_sum($sumKt2);$sumkht3 = array_sum($sumKt3);$sumkht4 = array_sum($sumKt4);
                        $tkhac = $sumkht1 + $sumkht2 + $sumkht3 + $sumkht4;  
                    }
                    $name[] = $v1;    
                }
                $tsr1 = $this->targetAllSR($idShowroom,$weeks[0],$year,2);
                $tsr2 = $this->targetAllSR($idShowroom,$weeks[1],$year,2);
                $tsr3 = $this->targetAllSR($idShowroom,$weeks[2],$year,2);
                $tsr4 = $this->targetAllSR($idShowroom,$weeks[3],$year,2);
                $sumtsr = $tsr1 + $tsr2 + $tsr3 + $tsr4 ;
                $target[] = [   'costcenter'=> $costcenter->where('id',$idShowroom)->first()->name,
                                'name'=>$name,
                                'hienthi'=>$hienthi,
                                'checksales' => $this->checksales(\Auth::user()->id),
                                't1' => $t1,'t2' => $t2,'t3' => $t3,'t4' => $t4,
                                'c1' => $c1,'c2' => $c2,'c3' => $c3,'c4' => $c4,
                                'tsr1' => $tsr1,'tsr4' => $tsr4,'tsr3' => $tsr3,'tsr2' => $tsr2,
                                'sumtsr' => $sumtsr,
                                'sumc' => $sumc , 'sumt' => $sumt, 
                                'sumdc' => $sumdc, 'sumdd' => $sumdd, 
                                'sumdc1' => $sumdc1, 'sumdd1' => $sumdd1,
                                'sumdc2' => $sumdc2, 'sumdd2' => $sumdd2,
                                'sumdc3' => $sumdc3, 'sumdd3' => $sumdd3,
                                'sumdc4' => $sumdc4, 'sumdd4' => $sumdd4,
                                'sumMt1' => ($sumMt1 == 0) ? '.' : $sumMt1, 'sumMt2' => ($sumMt2 == 0) ? '.' : $sumMt2,'sumMt3' => ($sumMt3 == 0) ? '.' : $sumMt3, 'sumMt4' => ($sumMt4 == 0) ? '.' : $sumMt4,
                                'sumTUt1' => ($sumTUt1 == 0) ? '.' : $sumTUt1, 'sumTUt2' => ($sumTUt2 == 0) ? '.' : $sumTUt2,'sumTUt3' => ($sumTUt3 == 0) ? '.' : $sumTUt3, 'sumTUt4' => ($sumTUt4 == 0) ? '.' : $sumTUt4,
                                'sumWt1' => ($sumWt1 == 0) ? '.' : $sumWt1, 'sumWt2' => ($sumWt2 == 0) ? '.' : $sumWt2,'sumWt3' => ($sumWt3 == 0) ? '.' : $sumWt3, 'sumWt4' => ($sumWt4 == 0) ? '.' : $sumWt4,
                                'sumSUt1' => ($sumSUt1 == 0) ? '.' : $sumSUt1, 'sumSUt2' => ($sumSUt2 == 0) ? '.' : $sumSUt2,'sumSUt3' => ($sumSUt3 == 0) ? '.' : $sumSUt3, 'sumSUt4' => ($sumSUt4 == 0) ? '.' : $sumSUt4,
                                'sumSAt1' => ($sumSAt1 == 0) ? '.' : $sumSAt1, 'sumSAt2' =>($sumSAt2 == 0) ? '.' : $sumSAt2,'sumSAt3' => ($sumSAt3 == 0) ? '.' : $sumSAt3, 'sumSAt4' => ($sumSAt4 == 0) ? '.' : $sumSAt4,
                                'sumTHt1' => ($sumTHt1 == 0) ? '.' : $sumTHt1, 'sumTHt2' => ($sumTHt2 == 0) ? '.' : $sumTHt2,'sumTHt3' => ($sumTHt3 == 0) ? '.' : $sumTHt3, 'sumTHt4' => ($sumTHt4 == 0) ? '.' : $sumTHt4,
                                'sumFt1' => ($sumFt1 == 0) ? '.' : $sumFt1, 'sumFt2' => ($sumFt2 == 0) ? '.' : $sumFt2,'sumFt3' => ($sumFt3 == 0) ? '.' : $sumFt3, 'sumFt4' => ($sumFt4 == 0) ? '.' : $sumFt4,
                                'cdahlia' =>$cdahlia,'ciris'=>$ciris,'civy'=>$civy,'ckhac'=>$ckhac,
                                'cdahlia1' =>$sumDah1,'ciris1'=>$sumiris1,'civy1'=>$sumivy1,'ckhac1'=>$sumkh1,
                                'cdahlia2' =>$sumDah2,'ciris2'=>$sumiris2,'civy2'=>$sumivy2,'ckhac2'=>$sumkh2,
                                'cdahlia3' =>$sumDah3,'ciris3'=>$sumiris3,'civy3'=>$sumivy3,'ckhac3'=>$sumkh3,
                                'cdahlia4' =>$sumDah4,'ciris4'=>$sumiris4,'civy4'=>$sumivy4,'ckhac4'=>$sumkh4,

                                'tdahlia' =>$tdahlia,'tiris'=>$tiris,'tivy'=>$tivy,'tkhac'=>$tkhac,
                                'tdahlia1' =>$sumDaht1,'tiris1'=>$sumirist1,'tivy1'=>$sumivyt1,'tkhac1'=>$sumkht1,
                                'tdahlia2' =>$sumDaht2,'tiris2'=>$sumirist2,'tivy2'=>$sumivyt2,'tkhac2'=>$sumkht2,
                                'tdahlia3' =>$sumDaht3,'tiris3'=>$sumirist3,'tivy3'=>$sumivyt3,'tkhac3'=>$sumkht3,
                                'tdahlia4' =>$sumDaht4,'tiris4'=>$sumirist4,'tivy4'=>$sumivyt4,'tkhac4'=>$sumkht4,
                            ];
            }
            if(count($r_costcenter) > 2){
                foreach ($target as $tong) {
                    $tongc[] = $tong['sumc'];$tongt[] = $tong['sumt']; 
                    $tongt1[] = $tong['t1'];$tongt2[] = $tong['t2']; $tongt3[] = $tong['t3']; $tongt4[] = $tong['t4'];    
                    $tongc1[] = $tong['c1'];$tongc2[] = $tong['c2']; $tongc3[] = $tong['c3']; $tongc4[] = $tong['c4'];
                    $tongsumMt1[] = $tong['sumMt1'];$tongsumMt2[] = $tong['sumMt2']; $tongsumMt3[] = $tong['sumMt3']; $tongsumMt4[] = $tong['sumMt4'];
                    $tongsumTUt1[] = $tong['sumTUt1'];$tongsumTUt2[] = $tong['sumTUt2']; $tongsumTUt3[] = $tong['sumTUt3']; $tongsumTUt4[] = $tong['sumTUt4'];
                    $tongsumWt1[] = $tong['sumWt1'];$tongsumWt2[] = $tong['sumWt2']; $tongsumWt3[] = $tong['sumWt3']; $tongsumWt4[] = $tong['sumWt4'];
                    $tongsumSUt1[] = $tong['sumSUt1'];$tongsumSUt2[] = $tong['sumSUt2']; $tongsumSUt3[] = $tong['sumSUt3']; $tongsumSUt4[] = $tong['sumSUt4'];
                    $tongsumSAt1[] = $tong['sumSAt1'];$tongsumSAt2[] = $tong['sumSAt2']; $tongsumSAt3[] = $tong['sumSAt3']; $tongsumSAt4[] = $tong['sumSAt4'];
                    $tongsumTHt1[] = $tong['sumTHt1'];$tongsumTHt2[] = $tong['sumTHt2']; $tongsumTHt3[] = $tong['sumTHt3']; $tongsumTHt4[] = $tong['sumTHt4'];
                    $tongsumFt1[] = $tong['sumFt1'];$tongsumFt2[] = $tong['sumFt2']; $tongsumFt3[] = $tong['sumFt3']; $tongsumFt4[] = $tong['sumFt4'];

                    $tongsumdc[] = $tong['sumdc']; $tongsumdd[] = $tong['sumdd'];
                    $tongsumdc1[] = $tong['sumdc1']; $tongsumdd1[] = $tong['sumdd1'];
                    $tongsumdc2[] = $tong['sumdc2']; $tongsumdd2[] = $tong['sumdd2'];
                    $tongsumdc3[] = $tong['sumdc3']; $tongsumdd3[] = $tong['sumdd3'];
                    $tongsumdc4[]= $tong['sumdc4']; $tongsumdd4[] = $tong['sumdd4'];

                    $tongtsr1[] = $tong['tsr1'];$tongtsr4[] = $tong['tsr4'];$tongtsr3[] = $tong['tsr3'];$tongtsr2[] = $tong['tsr2'];$tongsumtsr[] = $tong['sumtsr'];

                    $tongsumtir[] = $tong['tiris'];$tongsumtir1[] = $tong['tiris1'];$tongsumtir2[] = $tong['tiris2'];$tongsumtir3[] = $tong['tiris3'];$tongsumtir4[] = $tong['tiris4'];
                    $tongsumcir[] = $tong['ciris'];$tongsumcir1[] = $tong['ciris1'];$tongsumcir2[] = $tong['ciris2'];$tongsumcir3[] = $tong['ciris3'];$tongsumcir4[] = $tong['ciris4'];

                    $tongsumtk[] = $tong['tkhac'];$tongsumtk1[] = $tong['tkhac1'];$tongsumtk2[] = $tong['tkhac2'];$tongsumtk3[] = $tong['tkhac3'];$tongsumtk4[] = $tong['tkhac1'];
                    $tongsumck[] = $tong['ckhac'];$tongsumck1[] = $tong['ckhac1'];$tongsumck2[] = $tong['ckhac2'];$tongsumck3[] = $tong['ckhac3'];$tongsumck4[] = $tong['ckhac4'];

                    $tongsumtda[] = $tong['tdahlia'];$tongsumtda1[] = $tong['tdahlia1'];$tongsumtda2[] = $tong['tdahlia2'];$tongsumtda3[] = $tong['tdahlia3'];$tongsumtda4[] = $tong['tdahlia4'];
                    $tongsumcda[] = $tong['sumdc1'];$tongsumcda1[] = $tong['cdahlia1'];$tongsumcda2[] = $tong['cdahlia2'];$tongsumcda3[] = $tong['cdahlia3'];$tongsumcda4[] = $tong['cdahlia4'];
                    
                    $tongsumtiv[] = $tong['tivy'];$tongsumtiv1[] = $tong['tivy1'];$tongsumtiv2[] = $tong['tivy2'];$tongsumtiv3[] = $tong['tivy3'];$tongsumtiv4[] = $tong['tivy4'];
                    $tongsumciv[] = $tong['civy'];$tongsumciv1[] = $tong['civy1'];$tongsumciv2[] = $tong['civy2'];$tongsumciv3[] = $tong['civy3'];$tongsumciv4[] = $tong['civy4'];
                }    
                if ($request->all == 1) {
                    $costcenter_name = 'Tất Cả';
                }else{
                    $costcenter_name = Costcenter::whereIn('id',$r_costcenter)->first()['warehouse'];
                }

                $sumSelect = [ 
                    'costcenter' => $costcenter_name,
                    'checksales' =>  $this->checksales(\Auth::user()->id),
                    'sumc' => array_sum($tongc), 'sumt' => array_sum($tongt),
                    't1' => array_sum($tongt1), 't2' => array_sum($tongt2),'t3' => array_sum($tongt3),'t4' => array_sum($tongt4),
                    'c1' => array_sum($tongc1), 'c2' => array_sum($tongc2),'c3' => array_sum($tongc3),'c4' => array_sum($tongc4),
                    'sumSUt1' => (array_sum($tongsumSUt1) == 0) ? '.' : array_sum($tongsumSUt1), 
                    'sumSUt2' => (array_sum($tongsumSUt2) == 0) ? '.' : array_sum($tongsumSUt2),
                    'sumSUt3' => (array_sum($tongsumSUt3) == 0) ? '.' : array_sum($tongsumSUt3),
                    'sumSUt4' => (array_sum($tongsumSUt4) == 0) ? '.' : array_sum($tongsumSUt4),

                    'sumTUt1' => (array_sum($tongsumTUt1) == 0) ? '.' : array_sum($tongsumTUt1), 
                    'sumTUt2' => (array_sum($tongsumTUt2) == 0) ? '.' : array_sum($tongsumTUt2),
                    'sumTUt3' => (array_sum($tongsumTUt3) == 0) ? '.' : array_sum($tongsumTUt3),
                    'sumTUt4' => (array_sum($tongsumTUt4) == 0) ? '.' : array_sum($tongsumTUt4),

                    'sumMt1' => (array_sum($tongsumMt1) == 0) ? '.' : array_sum($tongsumMt1),
                    'sumMt2' => (array_sum($tongsumMt2) == 0) ? '.' : array_sum($tongsumMt2) ,
                    'sumMt3' => (array_sum($tongsumMt3) == 0) ? '.' : array_sum($tongsumMt3) ,
                    'sumMt4' => (array_sum($tongsumMt4) == 0) ? '.' : array_sum($tongsumMt4) ,

                    'sumWt1' => (array_sum($tongsumWt1) == 0) ? '.' : array_sum($tongsumWt1) ,
                    'sumWt2' => (array_sum($tongsumWt2) == 0) ? '.' : array_sum($tongsumWt2),
                    'sumWt3' => (array_sum($tongsumWt3) == 0) ? '.' : array_sum($tongsumWt3),
                    'sumWt4' => (array_sum($tongsumWt4) == 0) ? '.' : array_sum($tongsumWt4),

                    'sumSAt1' => (array_sum($tongsumSAt1) == 0) ? '.' : array_sum($tongsumSAt1), 
                    'sumSAt2' => (array_sum($tongsumSAt2) == 0) ? '.' : array_sum($tongsumSAt2),
                    'sumSAt3' => (array_sum($tongsumSAt3) == 0) ? '.' : array_sum($tongsumSAt3),
                    'sumSAt4' => (array_sum($tongsumSAt4) == 0) ? '.' : array_sum($tongsumSAt4),
                    'sumTHt1' => (array_sum($tongsumTHt1) == 0) ? '.' : array_sum($tongsumTHt1), 
                    'sumTHt2' => (array_sum($tongsumTHt2) == 0) ? '.' : array_sum($tongsumTHt2),
                    'sumTHt3' => (array_sum($tongsumTHt3) == 0) ? '.' : array_sum($tongsumTHt3),
                    'sumTHt4' => (array_sum($tongsumTHt4) == 0) ? '.' : array_sum($tongsumTHt4),
                    'sumFt1' => (array_sum($tongsumFt1) == 0) ? '.' : array_sum($tongsumFt1) ,
                    'sumFt2' => (array_sum($tongsumFt2) == 0) ? '.' : array_sum($tongsumFt2),
                    'sumFt3' => (array_sum($tongsumFt3) == 0) ? '.' : array_sum($tongsumFt3),
                    'sumFt4' => (array_sum($tongsumFt4) == 0) ? '.' : array_sum($tongsumFt4),
                    'tsr1' => array_sum($tongtsr1),'tsr4' => array_sum($tongtsr4),'tsr3' => array_sum($tongtsr3),'tsr2' => array_sum($tongtsr2),'sumtsr' => array_sum($tongsumtsr),

                    'sumdc' => array_sum($tongsumdc), 'sumdd'=> array_sum($tongsumdd), 
                    'sumdc1' => array_sum($tongsumdc1), 'sumdd1' => array_sum($tongsumdd1) ,
                    'sumdc2' => array_sum($tongsumdc2),  'sumdd2' =>array_sum($tongsumdd2),
                    'sumdc3' => array_sum($tongsumdc3), 'sumdd3' =>  array_sum($tongsumdd3),
                    'sumdc4' => array_sum($tongsumdc4), 'sumdd4' => array_sum($tongsumdd4) ,

                    'civy' => array_sum($tongsumciv),'civy1'=> array_sum($tongsumciv1),'civy2'=> array_sum($tongsumciv2),'civy3'=> array_sum($tongsumciv3),'civy4'=> array_sum($tongsumciv4), 
                    'tivy' => array_sum($tongsumtiv),'tivy1'=> array_sum($tongsumtiv1),'tivy2'=> array_sum($tongsumtiv2),'tivy3'=> array_sum($tongsumtiv3),'tivy4'=> array_sum($tongsumtiv4), 

                    'cdahlia' => array_sum($tongsumcda),'cdahlia1'=> array_sum($tongsumcda1),'cdahlia2'=> array_sum($tongsumcda2),'cdahlia3'=> array_sum($tongsumcda3),'cdahlia4'=> array_sum($tongsumcda4), 
                    'tdahlia' => array_sum($tongsumtda),'tdahlia1'=> array_sum($tongsumtda1),'tdahlia2'=> array_sum($tongsumtda2),'tdahlia3'=> array_sum($tongsumtda3),'tdahlia4'=> array_sum($tongsumtda4), 

                    'ciris' => array_sum($tongsumcir),'ciris1'=> array_sum($tongsumcir1),'ciris2'=> array_sum($tongsumcir2),'ciris3'=> array_sum($tongsumcir3),'ciris4'=> array_sum($tongsumcir4), 
                    'tiris' => array_sum($tongsumtir),'tiris1'=> array_sum($tongsumtir1),'tiris2'=> array_sum($tongsumtir2),'tiris3'=> array_sum($tongsumtir3),'tiris4'=> array_sum($tongsumtir4), 

                    'ckhac' => array_sum($tongsumck),'ckhac1'=> array_sum($tongsumck1),'ckhac2'=> array_sum($tongsumck2),'ckhac3'=> array_sum($tongsumck3),'ckhac4'=> array_sum($tongsumck4), 
                    'tkhac' => array_sum($tongsumtk),'tkhac1'=> array_sum($tongsumtk1),'tkhac2'=> array_sum($tongsumtk2),'tkhac3'=> array_sum($tongsumtk3),'tkhac4'=> array_sum($tongsumtk4),
                ];

                $target = array_merge([0=>$sumSelect],$target);
            }
        return $target;
    }

    public function noContactday($id,$day,$week,$year) {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        
        $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);

        $dayTarget = Lead::where('user_id',$id)->whereDate('start_date',$date[$day])->count();
        if($dayTarget == 0) {
            $dayTarget = '.';
        }
                
        return $dayTarget;
    }

    public function noContactdd($id,$week,$year) {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        
        $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);

        $dd = Lead::where('user_id',$id)->whereBetween('start_date',[$date[0], $date[3]])->count();
                
        return $dd;
    }

    public function noContactdc($id,$week,$year) {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        
        $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);

        $dc = Lead::where('user_id',$id)->whereBetween('start_date',[$date[4], $date[6]])->count();
                
        return $dc;
    }

    public function targetContacts(Request $request){
        $dates = $request['dates'];
        $dangnhap = \Auth::user()->id;
        $iduserSR = \DB::table('model_has_costcenters')->where('model_id',$dangnhap)->where('model_type','App\User')->pluck('costcenter_id');
        $iduserSR = ($iduserSR->isEmpty()) ? array(1) : json_decode($iduserSR);
        $r_costcenter = isset($request['costcenter']) ? $request['costcenter'] : $iduserSR;
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        if($start_week > 52){
            if($end_week == 3){
                $weeks = collect([$start_week, 1,2,3]);
            }else{
                $weeks = collect([$start_week, 54,1,2]);
            }
            
        }else{
            $weeks = collect(range($start_week, $end_week));
        }
        $contacts = Contact::where('loai',0)->filter($request->all())->get();

        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $ss1 = strtotime($dates[0]);

        $hienthi = 1;
        if ($dangnhap != 9003 && $dangnhap != 9074) {
            if (strtotime($dt->toDateString()) <  $ss1) { 
                $role_watch = \DB::table('roles_target')->where('d',$dates[0])->where('user_id',$dangnhap)->get();
                if ($role_watch->isEmpty()) {
                    $hienthi = 0;
                }
            }
        }

        $loc = \DB::table('model_has_roles')->where('role_id',2)->pluck('model_id');
        if (!$request['costcenter']) {
            $danhsach = User::whereIn('id',$loc)->where('date_off','>',$dates[0])->pluck('id');
        }else{
            $danhsach = User::select('id', 'name')
                ->where('id','>',0)->whereIn('id',$loc)->where('date_off','>',$dates[0])
                ->where(function ($query) use ($request){
                    foreach ($request['costcenter'] as $costcenter) {
                        $query->orWhereIn('id', Costcenter::find($costcenter)->entries(User::class)->pluck('id'));
                    }
                })
            ->distinct()
            ->pluck('id');
        }

        $ds = array_diff($danhsach->toArray(), $contacts->keys()->toArray());

        $contacts = $contacts->groupBy(function($contact){
           return $contact->user_id;
        });
        $contacts = $contacts->map(function($contactsList,$key){
            return $contactsList->countBy(function($contact){
                $date = new Carbon($contact->start_date);
                return $date->weekOfYear;
            });
        });

        foreach($ds as $c){
            $contacts[$c] = $contacts->first();
        }
        
        $year = date('Y',strtotime($dates[1]));
        $contacts = $this->targetByWeek($contacts, $weeks, $year);
        $sales = User::all()->filter(function($user){
            return $user->hasRole('sales');
        });
            $costcenter = Costcenter::select('id','name')->get();
            foreach ($costcenter as $k => $v) {
                $find = $this->findUser($v->id,$sales->pluck('id'),$dates[0]);
                foreach ($find as $key => $value) {
                    $idtest = $value->id;
                    $noContact = $weeks->map(function($week) use ($idtest,$year){
                        return [ 'w' => $week,'t' => $this->targetNocontact($idtest,$week, $year,1), 'c' => 0, 'monday' => '.','tuesday' => '.','wednesday' => '.','thursday' => '.','friday' => '.','saturday' => '.','sunday' => '.', 'sumc' => 0, 'sumd' => 0 ];
                    });
                    $infouser = isset($contacts[json_decode($find)[$key]->id]) ? $contacts[json_decode($find)[$key]->id] : $noContact;
                    
                    $merge[]= array('costcenter' => $v->name,
                                    'id' => $v->id,
                                    'name' =>  [
                                        'name' => json_decode($find)[$key]->name ,
                                        'contacts' => $infouser,
                                        't' => $infouser->sum('t'),
                                        'c' => $infouser->sum('c'),
                                        'sm' => $this->checksm(json_decode($find)[$key]->id),
                                        'cdahlia' => $infouser->sum('cdahlia'),
                                        'civy' => $infouser->sum('civy'),
                                        'ciris' => $infouser->sum('ciris'),
                                        'ckhac' => $infouser->sum('ckhac'),
                                        'tdahlia' => $infouser->sum('tdahlia'),
                                        'tivy' => $infouser->sum('tivy'),
                                        'tiris' => $infouser->sum('tiris'),
                                        'tkhac' => $infouser->sum('tkhac'),
                                    ]
                                );
                }
            }
            foreach ($merge as $key1 => $value1) {
                $a[$value1['id']][] = $value1['name'];
            }
            $idcos = Costcenter::where('closed','<',$dates[0])->pluck('id')->toArray();
            $r_costcenter = array_diff( $r_costcenter, $idcos );
            // return $idcos;
            foreach ($r_costcenter as $idShowroom) {
                $c = array();
                $name = array();
                $v1 = array();
                foreach ($a[$idShowroom] as $k1 => $v1) {
                    $c[] = $v1;
                    $i = array();
                    foreach ($c as $key22 => $value22) {
                        $i[$key22] = $value22['contacts'];
                    }
                    
                    $sumt = array(); $sum1t = array(); $sum2t = array(); $sum3t = array(); $sum4t = array(); 
                    $sumc = array(); $sum1c = array(); $sum2c = array(); $sum3c = array(); $sum4c = array(); 
                    $sum1dd = array();$sum1dc = array();$sum2dd = array();$sum2dc = array();
                    $sum3dd = array();$sum3dc = array();$sum4dd = array();$sum4dc = array();
                    $sumM1 = array();$sumM2 = array();$sumM3 = array();$sumM4 = array();
                    $sumTU1 = array();$sumTU2 = array();$sumTU3 = array();$sumTU4 = array();
                    $sumSU1 = array();$sumSU2 = array();$sumSU3 = array();$sumSU4 = array();
                    $sumSA1 = array();$sumSA2 = array();$sumSA3 = array();$sumSA4 = array();
                    $sumTH1 = array();$sumTH2 = array();$sumTH3 = array();$sumTH4 = array();
                    $sumF1 = array();$sumF2 = array();$sumF3 = array();$sumF4 = array();
                    $sumW1 = array();$sumW2 = array();$sumW3 = array();$sumW4 = array();
                    $sumDa1 = array();$sumDa2 = array();$sumDa3 = array();$sumDa4 = array();
                    $sumIv1 = array();$sumIv2 = array();$sumIv3 = array();$sumIv4 = array();
                    $sumIr1 = array();$sumIr2 = array();$sumIr3 = array();$sumIr4 = array();
                    $sumK1 = array();$sumK2 = array();$sumK3 = array();$sumK4 = array();
                    $sumDat1 = array();$sumDat2 = array();$sumDat3 = array();$sumDat4 = array();
                    $sumIvt1 = array();$sumIvt2 = array();$sumIvt3 = array();$sumIvt4 = array();
                    $sumIrt1 = array();$sumIrt2 = array();$sumIrt3 = array();$sumIrt4 = array();
                    $sumKt1 = array();$sumKt2 = array();$sumKt3 = array();$sumKt4 = array();
                    foreach ($i as $k=>$subArray) {
                        foreach ($subArray as $id=>$value12) {
                            $sumArray[] = $value12['w'];
                            if($value12['w'] == $sumArray[0]) {
                                $sum1t[] = $value12['t']; 
                                $sum1c[] = $value12['c'];
                                $sum1dd[] = $value12['sumd'];
                                $sum1dc[] = $value12['sumc'];
                                $sumM1[] = $value12['monday'];
                                $sumTU1[] = $value12['tuesday'];
                                $sumW1[] = $value12['wednesday'];
                                $sumSU1[] = $value12['sunday'];
                                $sumSA1[] = $value12['saturday'];
                                $sumTH1[] = $value12['thursday'];
                                $sumF1[] = $value12['friday'];
                                $sumDa1[] = isset($value12['cdahlia']) ? $value12['cdahlia'] : 0;
                                $sumIv1[] = isset($value12['civy']) ? $value12['civy'] : 0;
                                $sumIr1[] = isset($value12['ciris']) ? $value12['ciris'] : 0;
                                $sumK1[] = isset($value12['ckhac']) ? $value12['ckhac'] : 0;
                                $sumDat1[] = isset($value12['tdahlia']) ? $value12['tdahlia'] : 0;
                                $sumIvt1[] = isset($value12['tivy']) ? $value12['tivy'] : 0;
                                $sumIrt1[] = isset($value12['tiris']) ? $value12['tiris'] : 0;
                                $sumKt1[] = isset($value12['tkhac']) ? $value12['tkhac'] : 0;
                            }
                            if($value12['w'] == $sumArray[0]+1) {
                                $sum2t[] = $value12['t']; 
                                $sum2c[] = $value12['c'];
                                $sum2dd[] = $value12['sumd'];
                                $sum2dc[] = $value12['sumc'];
                                $sumM2[] = $value12['monday'];
                                $sumTU2[] = $value12['tuesday'];
                                $sumW2[] = $value12['wednesday'];
                                $sumSU2[] = $value12['sunday'];
                                $sumSA2[] = $value12['saturday'];
                                $sumTH2[] = $value12['thursday'];
                                $sumF2[] = $value12['friday'];
                                $sumDa2[] = isset($value12['cdahlia']) ? $value12['cdahlia'] : 0;
                                $sumIv2[] = isset($value12['civy']) ? $value12['civy'] : 0;
                                $sumIr2[] = isset($value12['ciris']) ? $value12['ciris'] : 0;
                                $sumK2[] = isset($value12['ckhac']) ? $value12['ckhac'] : 0;
                                $sumDat2[] = isset($value12['tdahlia']) ? $value12['tdahlia'] : 0;
                                $sumIvt2[] = isset($value12['tivy']) ? $value12['tivy'] : 0;
                                $sumIrt2[] = isset($value12['tiris']) ? $value12['tiris'] : 0;
                                $sumKt2[] = isset($value12['tkhac']) ? $value12['tkhac'] : 0;
                            }
                            if($value12['w'] == $sumArray[0]+2) {
                                $sum3t[] = $value12['t']; 
                                $sum3c[] = $value12['c'];
                                $sum3dd[] = $value12['sumd']; 
                                $sum3dc[] = $value12['sumc'];
                                $sumM3[] = $value12['monday'];
                                $sumTU3[] = $value12['tuesday'];
                                $sumW3[] = $value12['wednesday'];
                                $sumSU3[] = $value12['sunday'];
                                $sumSA3[] = $value12['saturday'];
                                $sumTH3[] = $value12['thursday'];
                                $sumF3[] = $value12['friday'];
                                $sumDa3[] = isset($value12['cdahlia']) ? $value12['cdahlia'] : 0;
                                $sumIv3[] = isset($value12['civy']) ? $value12['civy'] : 0;
                                $sumIr3[] = isset($value12['ciris']) ? $value12['ciris'] : 0;
                                $sumK3[] = isset($value12['ckhac']) ? $value12['ckhac'] : 0;
                                $sumDat3[] = isset($value12['tdahlia']) ? $value12['tdahlia'] : 0;
                                $sumIvt3[] = isset($value12['tivy']) ? $value12['tivy'] : 0;
                                $sumIrt3[] = isset($value12['tiris']) ? $value12['tiris'] : 0;
                                $sumKt3[] = isset($value12['tkhac']) ? $value12['tkhac'] : 0;
                            }
                            if($value12['w'] == $sumArray[0]+3) {
                                $sum4t[] = $value12['t']; 
                                $sum4c[] = $value12['c']; 
                                $sum4dc[] = $value12['sumc'];
                                $sum4dd[] = $value12['sumd'];
                                $sumM4[] = $value12['monday'];
                                $sumTU4[] = $value12['tuesday'];
                                $sumW4[] = $value12['wednesday'];
                                $sumSU4[] = $value12['sunday'];
                                $sumSA4[] = $value12['saturday'];
                                $sumTH4[] = $value12['thursday'];
                                $sumF4[] = $value12['friday'];
                                $sumDa4[] = isset($value12['cdahlia']) ? $value12['cdahlia'] : 0;
                                $sumIv4[] = isset($value12['civy']) ? $value12['civy'] : 0;
                                $sumIr4[] = isset($value12['ciris']) ? $value12['ciris'] : 0;
                                $sumK4[] = isset($value12['ckhac']) ? $value12['ckhac'] : 0;
                                $sumDat4[] = isset($value12['tdahlia']) ? $value12['tdahlia'] : 0;
                                $sumIvt4[] = isset($value12['tivy']) ? $value12['tivy'] : 0;
                                $sumIrt4[] = isset($value12['tiris']) ? $value12['tiris'] : 0;
                                $sumKt4[] = isset($value12['tkhac']) ? $value12['tkhac'] : 0;
                            }
                        }
                        $t1 = array_sum($sum1t);
                        $t2 = array_sum($sum2t);
                        $t3 = array_sum($sum3t);
                        $t4 = array_sum($sum4t);
                        $sumt = $t1 + $t2 + $t3 + $t4;
                        $sumdc1 = array_sum($sum1dc);
                        $sumdc2 = array_sum($sum2dc);
                        $sumdc3 = array_sum($sum3dc);
                        $sumdc4 = array_sum($sum4dc);
                        $sumdc = $sumdc1 + $sumdc2 + $sumdc3 + $sumdc4;
                        $sumdd1 = array_sum($sum1dd);
                        $sumdd2 = array_sum($sum2dd);
                        $sumdd3 = array_sum($sum3dd);
                        $sumdd4 = array_sum($sum4dd);
                        $sumdd = $sumdd1 + $sumdd2 + $sumdd3 + $sumdd4;
                        $sumMt1 = array_sum($sumM1);$sumMt2 = array_sum($sumM2);$sumMt3 = array_sum($sumM3);$sumMt4 = array_sum($sumM4);
                        $sumTUt1 = array_sum($sumTU1);$sumTUt2 = array_sum($sumTU2);$sumTUt3 = array_sum($sumTU3);$sumTUt4 = array_sum($sumTU4);
                        $sumWt1 = array_sum($sumW1);$sumWt2 = array_sum($sumW2);$sumWt3 = array_sum($sumW3);$sumWt4 = array_sum($sumW4);
                        $sumSUt1 = array_sum($sumSU1);$sumSUt2 = array_sum($sumSU2);$sumSUt3 = array_sum($sumSU3);$sumSUt4 = array_sum($sumSU4);
                        $sumSAt1 = array_sum($sumSA1);$sumSAt2 = array_sum($sumSA2);$sumSAt3 = array_sum($sumSA3);$sumSAt4 = array_sum($sumSA4);
                        $sumTHt1 = array_sum($sumTH1);$sumTHt2 = array_sum($sumTH2);$sumTHt3 = array_sum($sumTH3);$sumTHt4 = array_sum($sumTH4);
                        $sumFt1 = array_sum($sumF1);$sumFt2 = array_sum($sumF2);$sumFt3 = array_sum($sumF3);$sumFt4 = array_sum($sumF4);
                        $c1 = $sumdc1 + $sumdd1;
                        $c2 = $sumdc2 + $sumdd2;
                        $c3 = $sumdc3 + $sumdd3;
                        $c4 = $sumdc4 + $sumdd4;
                        $sumc = $c1 + $c2 + $c3 + $c4; 
                        $sumDah1 = array_sum($sumDa1);$sumDah2 = array_sum($sumDa2);$sumDah3 = array_sum($sumDa3);$sumDah4 = array_sum($sumDa4);
                        $cdahlia = $sumDah1 + $sumDah2 + $sumDah3 + $sumDah4; 
                        $sumivy1 = array_sum($sumIv1);$sumivy2 = array_sum($sumIv2);$sumivy3 = array_sum($sumIv3);$sumivy4 = array_sum($sumIv4);
                        $civy = $sumivy1 + $sumivy2 + $sumivy3 + $sumivy4; 
                        $sumiris1 = array_sum($sumIr1);$sumiris2 = array_sum($sumIr2);$sumiris3 = array_sum($sumIr3);$sumiris4 = array_sum($sumIr4);
                        $ciris = $sumiris1 + $sumiris2 + $sumiris3 + $sumiris4; 
                        $sumkh1 = array_sum($sumK1);$sumkh2 = array_sum($sumK2);$sumkh3 = array_sum($sumK3);$sumkh4 = array_sum($sumK4);
                        $ckhac = $sumkh1 + $sumkh2 + $sumkh3 + $sumkh4;

                        $sumDaht1 = array_sum($sumDat1);$sumDaht2 = array_sum($sumDat2);$sumDaht3 = array_sum($sumDat3);$sumDaht4 = array_sum($sumDat4);
                        $tdahlia = $sumDaht1 + $sumDaht2 + $sumDaht3 + $sumDaht4; 

                        $sumivyt1 = array_sum($sumIvt1);$sumivyt2 = array_sum($sumIvt2);$sumivyt3 = array_sum($sumIvt3);$sumivyt4 = array_sum($sumIvt4);
                        $tivy = $sumivyt1 + $sumivyt2 + $sumivyt3 + $sumivyt4; 

                        $sumirist1 = array_sum($sumIrt1);$sumirist2 = array_sum($sumIrt2);$sumirist3 = array_sum($sumIrt3);$sumirist4 = array_sum($sumIrt4);
                        $tiris = $sumirist1 + $sumirist2 + $sumirist3 + $sumirist4; 

                        $sumkht1 = array_sum($sumKt1);$sumkht2 = array_sum($sumKt2);$sumkht3 = array_sum($sumKt3);$sumkht4 = array_sum($sumKt4);
                        $tkhac = $sumkht1 + $sumkht2 + $sumkht3 + $sumkht4; 
                    }
                    $name[] = $v1;     
                }
                $tsr1 = $this->targetAllSR($idShowroom,$weeks[0],$year,1);
                $tsr2 = $this->targetAllSR($idShowroom,$weeks[1],$year,1);
                $tsr3 = $this->targetAllSR($idShowroom,$weeks[2],$year,1);
                $tsr4 = $this->targetAllSR($idShowroom,$weeks[3],$year,1);
                $sumtsr = $tsr1 + $tsr2 + $tsr3 + $tsr4 ;
                $target[] = [   'costcenter'=> $costcenter->where('id',$idShowroom)->first()->name,
                                'name'=>$name,
                                'hienthi' => $hienthi,
                                'checksales' => $this->checksales(\Auth::user()->id),
                                't1' => $t1,'t2' => $t2,'t3' => $t3,'t4' => $t4,
                                'c1' => $c1,'c2' => $c2,'c3' => $c3,'c4' => $c4,
                                'tsr1' => $tsr1,'tsr4' => $tsr4,'tsr3' => $tsr3,'tsr2' => $tsr2,
                                'sumtsr' => $sumtsr,
                                'sumc' => $sumc , 'sumt' => $sumt, 
                                'sumdc' => $sumdc, 'sumdd' => $sumdd, 
                                'sumdc1' => $sumdc1, 'sumdd1' => $sumdd1,
                                'sumdc2' => $sumdc2, 'sumdd2' => $sumdd2,
                                'sumdc3' => $sumdc3, 'sumdd3' => $sumdd3,
                                'sumdc4' => $sumdc4, 'sumdd4' => $sumdd4,
                                'sumMt1' => ($sumMt1 == 0) ? '.' : $sumMt1, 'sumMt2' => ($sumMt2 == 0) ? '.' : $sumMt2,'sumMt3' => ($sumMt3 == 0) ? '.' : $sumMt3, 'sumMt4' => ($sumMt4 == 0) ? '.' : $sumMt4,
                                'sumTUt1' => ($sumTUt1 == 0) ? '.' : $sumTUt1, 'sumTUt2' => ($sumTUt2 == 0) ? '.' : $sumTUt2,'sumTUt3' => ($sumTUt3 == 0) ? '.' : $sumTUt3, 'sumTUt4' => ($sumTUt4 == 0) ? '.' : $sumTUt4,
                                'sumWt1' => ($sumWt1 == 0) ? '.' : $sumWt1, 'sumWt2' => ($sumWt2 == 0) ? '.' : $sumWt2,'sumWt3' => ($sumWt3 == 0) ? '.' : $sumWt3, 'sumWt4' => ($sumWt4 == 0) ? '.' : $sumWt4,
                                'sumSUt1' => ($sumSUt1 == 0) ? '.' : $sumSUt1, 'sumSUt2' => ($sumSUt2 == 0) ? '.' : $sumSUt2,'sumSUt3' => ($sumSUt3 == 0) ? '.' : $sumSUt3, 'sumSUt4' => ($sumSUt4 == 0) ? '.' : $sumSUt4,
                                'sumSAt1' => ($sumSAt1 == 0) ? '.' : $sumSAt1, 'sumSAt2' =>($sumSAt2 == 0) ? '.' : $sumSAt2,'sumSAt3' => ($sumSAt3 == 0) ? '.' : $sumSAt3, 'sumSAt4' => ($sumSAt4 == 0) ? '.' : $sumSAt4,
                                'sumTHt1' => ($sumTHt1 == 0) ? '.' : $sumTHt1, 'sumTHt2' => ($sumTHt2 == 0) ? '.' : $sumTHt2,'sumTHt3' => ($sumTHt3 == 0) ? '.' : $sumTHt3, 'sumTHt4' => ($sumTHt4 == 0) ? '.' : $sumTHt4,
                                'sumFt1' => ($sumFt1 == 0) ? '.' : $sumFt1, 'sumFt2' => ($sumFt2 == 0) ? '.' : $sumFt2,'sumFt3' => ($sumFt3 == 0) ? '.' : $sumFt3, 'sumFt4' => ($sumFt4 == 0) ? '.' : $sumFt4,
                                'cdahlia' =>$cdahlia,'ciris'=>$ciris,'civy'=>$civy,'ckhac'=>$ckhac,
                                'cdahlia1' =>$sumDah1,'ciris1'=>$sumiris1,'civy1'=>$sumivy1,'ckhac1'=>$sumkh1,
                                'cdahlia2' =>$sumDah2,'ciris2'=>$sumiris2,'civy2'=>$sumivy2,'ckhac2'=>$sumkh2,
                                'cdahlia3' =>$sumDah3,'ciris3'=>$sumiris3,'civy3'=>$sumivy3,'ckhac3'=>$sumkh3,
                                'cdahlia4' =>$sumDah4,'ciris4'=>$sumiris4,'civy4'=>$sumivy4,'ckhac4'=>$sumkh4,

                                'tdahlia' =>$tdahlia,'tiris'=>$tiris,'tivy'=>$tivy,'tkhac'=>$tkhac,
                                'tdahlia1' =>$sumDaht1,'tiris1'=>$sumirist1,'tivy1'=>$sumivyt1,'tkhac1'=>$sumkht1,
                                'tdahlia2' =>$sumDaht2,'tiris2'=>$sumirist2,'tivy2'=>$sumivyt2,'tkhac2'=>$sumkht2,
                                'tdahlia3' =>$sumDaht3,'tiris3'=>$sumirist3,'tivy3'=>$sumivyt3,'tkhac3'=>$sumkht3,
                                'tdahlia4' =>$sumDaht4,'tiris4'=>$sumirist4,'tivy4'=>$sumivyt4,'tkhac4'=>$sumkht4,

                            ];
            }
            if(count($r_costcenter) > 2){
                foreach ($target as $tong) {
                    $tongc[] = $tong['sumc'];$tongt[] = $tong['sumt']; 
                    $tongt1[] = $tong['t1'];$tongt2[] = $tong['t2']; $tongt3[] = $tong['t3']; $tongt4[] = $tong['t4'];    
                    $tongc1[] = $tong['c1'];$tongc2[] = $tong['c2']; $tongc3[] = $tong['c3']; $tongc4[] = $tong['c4'];
                    $tongsumMt1[] = $tong['sumMt1'];$tongsumMt2[] = $tong['sumMt2']; $tongsumMt3[] = $tong['sumMt3']; $tongsumMt4[] = $tong['sumMt4'];
                    $tongsumTUt1[] = $tong['sumTUt1'];$tongsumTUt2[] = $tong['sumTUt2']; $tongsumTUt3[] = $tong['sumTUt3']; $tongsumTUt4[] = $tong['sumTUt4'];
                    $tongsumWt1[] = $tong['sumWt1'];$tongsumWt2[] = $tong['sumWt2']; $tongsumWt3[] = $tong['sumWt3']; $tongsumWt4[] = $tong['sumWt4'];
                    $tongsumSUt1[] = $tong['sumSUt1'];$tongsumSUt2[] = $tong['sumSUt2']; $tongsumSUt3[] = $tong['sumSUt3']; $tongsumSUt4[] = $tong['sumSUt4'];
                    $tongsumSAt1[] = $tong['sumSAt1'];$tongsumSAt2[] = $tong['sumSAt2']; $tongsumSAt3[] = $tong['sumSAt3']; $tongsumSAt4[] = $tong['sumSAt4'];
                    $tongsumTHt1[] = $tong['sumTHt1'];$tongsumTHt2[] = $tong['sumTHt2']; $tongsumTHt3[] = $tong['sumTHt3']; $tongsumTHt4[] = $tong['sumTHt4'];
                    $tongsumFt1[] = $tong['sumFt1'];$tongsumFt2[] = $tong['sumFt2']; $tongsumFt3[] = $tong['sumFt3']; $tongsumFt4[] = $tong['sumFt4'];

                    $tongsumdc[] = $tong['sumdc']; $tongsumdd[] = $tong['sumdd'];
                    $tongsumdc1[] = $tong['sumdc1']; $tongsumdd1[] = $tong['sumdd1'];
                    $tongsumdc2[] = $tong['sumdc2']; $tongsumdd2[] = $tong['sumdd2'];
                    $tongsumdc3[] = $tong['sumdc3']; $tongsumdd3[] = $tong['sumdd3'];
                    $tongsumdc4[]= $tong['sumdc4']; $tongsumdd4[] = $tong['sumdd4'];

                    $tongsumtir[] = $tong['tiris'];$tongsumtir1[] = $tong['tiris1'];$tongsumtir2[] = $tong['tiris2'];$tongsumtir3[] = $tong['tiris3'];$tongsumtir4[] = $tong['tiris4'];
                    $tongsumcir[] = $tong['ciris'];$tongsumcir1[] = $tong['ciris1'];$tongsumcir2[] = $tong['ciris2'];$tongsumcir3[] = $tong['ciris3'];$tongsumcir4[] = $tong['ciris4'];

                    $tongsumtk[] = $tong['tkhac'];$tongsumtk1[] = $tong['tkhac1'];$tongsumtk2[] = $tong['tkhac2'];$tongsumtk3[] = $tong['tkhac3'];$tongsumtk4[] = $tong['tkhac1'];
                    $tongsumck[] = $tong['ckhac'];$tongsumck1[] = $tong['ckhac1'];$tongsumck2[] = $tong['ckhac2'];$tongsumck3[] = $tong['ckhac3'];$tongsumck4[] = $tong['ckhac4'];

                    $tongsumtda[] = $tong['tdahlia'];$tongsumtda1[] = $tong['tdahlia1'];$tongsumtda2[] = $tong['tdahlia2'];$tongsumtda3[] = $tong['tdahlia3'];$tongsumtda4[] = $tong['tdahlia4'];
                    $tongsumcda[] = $tong['sumdc1'];$tongsumcda1[] = $tong['cdahlia1'];$tongsumcda2[] = $tong['cdahlia2'];$tongsumcda3[] = $tong['cdahlia3'];$tongsumcda4[] = $tong['cdahlia4'];
                    
                    $tongsumtiv[] = $tong['tivy'];$tongsumtiv1[] = $tong['tivy1'];$tongsumtiv2[] = $tong['tivy2'];$tongsumtiv3[] = $tong['tivy3'];$tongsumtiv4[] = $tong['tivy4'];
                    $tongsumciv[] = $tong['civy'];$tongsumciv1[] = $tong['civy1'];$tongsumciv2[] = $tong['civy2'];$tongsumciv3[] = $tong['civy3'];$tongsumciv4[] = $tong['civy4'];


                    $tongtsr1[] = $tong['tsr1'];$tongtsr4[] = $tong['tsr4'];$tongtsr3[] = $tong['tsr3'];$tongtsr2[] = $tong['tsr2'];$tongsumtsr[] = $tong['sumtsr'];
                }    
                if ($request->all == 1) {
                    $costcenter_name = 'Tất Cả';
                }else{
                    $costcenter_name = Costcenter::whereIn('id',$r_costcenter)->first()['warehouse'];
                }
          
                $sumSelect = [ 

                    'civy' => array_sum($tongsumciv),'civy1'=> array_sum($tongsumciv1),'civy2'=> array_sum($tongsumciv2),'civy3'=> array_sum($tongsumciv3),'civy4'=> array_sum($tongsumciv4), 
                    'tivy' => array_sum($tongsumtiv),'tivy1'=> array_sum($tongsumtiv1),'tivy2'=> array_sum($tongsumtiv2),'tivy3'=> array_sum($tongsumtiv3),'tivy4'=> array_sum($tongsumtiv4), 

                    'cdahlia' => array_sum($tongsumcda),'cdahlia1'=> array_sum($tongsumcda1),'cdahlia2'=> array_sum($tongsumcda2),'cdahlia3'=> array_sum($tongsumcda3),'cdahlia4'=> array_sum($tongsumcda4), 
                    'tdahlia' => array_sum($tongsumtda),'tdahlia1'=> array_sum($tongsumtda1),'tdahlia2'=> array_sum($tongsumtda2),'tdahlia3'=> array_sum($tongsumtda3),'tdahlia4'=> array_sum($tongsumtda4), 

                    'ciris' => array_sum($tongsumcir),'ciris1'=> array_sum($tongsumcir1),'ciris2'=> array_sum($tongsumcir2),'ciris3'=> array_sum($tongsumcir3),'ciris4'=> array_sum($tongsumcir4), 
                    'tiris' => array_sum($tongsumtir),'tiris1'=> array_sum($tongsumtir1),'tiris2'=> array_sum($tongsumtir2),'tiris3'=> array_sum($tongsumtir3),'tiris4'=> array_sum($tongsumtir4), 

                    'ckhac' => array_sum($tongsumck),'ckhac1'=> array_sum($tongsumck1),'ckhac2'=> array_sum($tongsumck2),'ckhac3'=> array_sum($tongsumck3),'ckhac4'=> array_sum($tongsumck4), 
                    'tkhac' => array_sum($tongsumtk),'tkhac1'=> array_sum($tongsumtk1),'tkhac2'=> array_sum($tongsumtk2),'tkhac3'=> array_sum($tongsumtk3),'tkhac4'=> array_sum($tongsumtk4), 
                    

                    'costcenter' => $costcenter_name,
                    'checksales' =>  $this->checksales(\Auth::user()->id),
                    'sumc' => array_sum($tongc), 'sumt' => array_sum($tongt),
                    't1' => array_sum($tongt1), 't2' => array_sum($tongt2),'t3' => array_sum($tongt3),'t4' => array_sum($tongt4),
                    'c1' => array_sum($tongc1), 'c2' => array_sum($tongc2),'c3' => array_sum($tongc3),'c4' => array_sum($tongc4),
                    'sumSUt1' => (array_sum($tongsumSUt1) == 0) ? '.' : array_sum($tongsumSUt1), 
                    'sumSUt2' => (array_sum($tongsumSUt2) == 0) ? '.' : array_sum($tongsumSUt2),
                    'sumSUt3' => (array_sum($tongsumSUt3) == 0) ? '.' : array_sum($tongsumSUt3),
                    'sumSUt4' => (array_sum($tongsumSUt4) == 0) ? '.' : array_sum($tongsumSUt4),

                    'sumTUt1' => (array_sum($tongsumTUt1) == 0) ? '.' : array_sum($tongsumTUt1), 
                    'sumTUt2' => (array_sum($tongsumTUt2) == 0) ? '.' : array_sum($tongsumTUt2),
                    'sumTUt3' => (array_sum($tongsumTUt3) == 0) ? '.' : array_sum($tongsumTUt3),
                    'sumTUt4' => (array_sum($tongsumTUt4) == 0) ? '.' : array_sum($tongsumTUt4),

                    'sumMt1' => (array_sum($tongsumMt1) == 0) ? '.' : array_sum($tongsumMt1),
                    'sumMt2' => (array_sum($tongsumMt2) == 0) ? '.' : array_sum($tongsumMt2) ,
                    'sumMt3' => (array_sum($tongsumMt3) == 0) ? '.' : array_sum($tongsumMt3) ,
                    'sumMt4' => (array_sum($tongsumMt4) == 0) ? '.' : array_sum($tongsumMt4) ,

                    'sumWt1' => (array_sum($tongsumWt1) == 0) ? '.' : array_sum($tongsumWt1) ,
                    'sumWt2' => (array_sum($tongsumWt2) == 0) ? '.' : array_sum($tongsumWt2),
                    'sumWt3' => (array_sum($tongsumWt3) == 0) ? '.' : array_sum($tongsumWt3),
                    'sumWt4' => (array_sum($tongsumWt4) == 0) ? '.' : array_sum($tongsumWt4),

                    'sumSAt1' => (array_sum($tongsumSAt1) == 0) ? '.' : array_sum($tongsumSAt1), 
                    'sumSAt2' => (array_sum($tongsumSAt2) == 0) ? '.' : array_sum($tongsumSAt2),
                    'sumSAt3' => (array_sum($tongsumSAt3) == 0) ? '.' : array_sum($tongsumSAt3),
                    'sumSAt4' => (array_sum($tongsumSAt4) == 0) ? '.' : array_sum($tongsumSAt4),
                    'sumTHt1' => (array_sum($tongsumTHt1) == 0) ? '.' : array_sum($tongsumTHt1), 
                    'sumTHt2' => (array_sum($tongsumTHt2) == 0) ? '.' : array_sum($tongsumTHt2),
                    'sumTHt3' => (array_sum($tongsumTHt3) == 0) ? '.' : array_sum($tongsumTHt3),
                    'sumTHt4' => (array_sum($tongsumTHt4) == 0) ? '.' : array_sum($tongsumTHt4),
                    'sumFt1' => (array_sum($tongsumFt1) == 0) ? '.' : array_sum($tongsumFt1) ,
                    'sumFt2' => (array_sum($tongsumFt2) == 0) ? '.' : array_sum($tongsumFt2),
                    'sumFt3' => (array_sum($tongsumFt3) == 0) ? '.' : array_sum($tongsumFt3),
                    'sumFt4' => (array_sum($tongsumFt4) == 0) ? '.' : array_sum($tongsumFt4),

                    'tsr1' => array_sum($tongtsr1),'tsr4' => array_sum($tongtsr4),'tsr3' => array_sum($tongtsr3),'tsr2' => array_sum($tongtsr2),'sumtsr' => array_sum($tongsumtsr),

                    'sumdc' => array_sum($tongsumdc), 'sumdd'=> array_sum($tongsumdd), 
                    'sumdc1' => array_sum($tongsumdc1), 'sumdd1' => array_sum($tongsumdd1) ,
                    'sumdc2' => array_sum($tongsumdc2),  'sumdd2' =>array_sum($tongsumdd2),
                    'sumdc3' => array_sum($tongsumdc3), 'sumdd3' =>  array_sum($tongsumdd3),
                    'sumdc4' => array_sum($tongsumdc4), 'sumdd4' => array_sum($tongsumdd4) ,
                ];

                $target = array_merge([0=>$sumSelect],$target);
            }


        return $target;
    }
    public function targetSRcontact(Request $request){
        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        $weeks = collect(range($start_week, $end_week));

        $dangnhap = \Auth::user()->id;
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $ss1 = strtotime($dates[0]);

        if (strtotime($dt->toDateString()) <  $ss1) { 
            if ($dangnhap != 9003 && $dangnhap != 9074) {
                $role_watch = \DB::table('roles_target')->whereDate('d',$dates[0])->where('user_id',$dangnhap)->get();
                if ($role_watch->isEmpty()) {
                    return response()->json([
                        'message' => 'Bạn không có quyền xem'
                    ],400);
                }
            }
        }

        $contacts = Contact::where('loai',0)->filter($request->all())->get();
        $contacts = $contacts->groupBy(function($contact){
           return $contact->user_id;
        });
        $contacts = $contacts->map(function($contactsList,$key){
            return $contactsList->countBy(function($contact){
                    $date = new Carbon($contact->start_date);
                    return $date->weekOfYear;
                });
        });
        $year = date('Y',strtotime($dates[1]));
        $contacts = $this->targetByWeek($contacts, $weeks, $year);
        $sales = User::all()->filter(function($user){
            return $user->hasRole('sales');
        });
            $noContact = $weeks->map(function($week){
                return [ 'w' => $week, 't' => 1, 'c' => 0, 'monday' => 0,'tuesday' => 0,'wednesday' => 0,'thursday' => 0,'friday' => 0,'saturday' => 0,'sunday' => 0, 'sumc' => 0, 'sumd' => 0 ];
            });
            $costcenter = Costcenter::whereDate('closed','>',$dates[0])->select('id','name','warehouse')->get();

            foreach ($costcenter as $k => $v) {
                $find = $this->findUser($v->id,$sales->pluck('id'),$dates[0]);

                foreach ($find as $key => $value) {
                    $infouser = isset($contacts[json_decode($find)[$key]->id]) ? $contacts[json_decode($find)[$key]->id] : $noContact;
                    $merge[]= array('costcenter' => $v->name,
                                    'name' =>  [
                                        'warehouse' => $v->warehouse,
                                        'name' => json_decode($find)[$key]->name ,
                                        'contacts' => $infouser,
                                        't' => $infouser->sum('t'),
                                        'c' => $infouser->sum('c'),
                                    ]
                                );
                }
            }
            foreach ($merge as $key1 => $value1) {
                $a[$value1['costcenter']][] = $value1['name'];
            }

            foreach ($a as $k1 => $v1) {
                $c[0] = $v1;
                $i = array();
                foreach ($c as $key22 => $value22) {
                    foreach ($value22 as $key23 => $value23) {
                        $i[$key23] = $value23['contacts'];
                    }
                }
                $sumArray = array();
                $sum1c = array();
                $sum2c = array();
                $sum3c = array();
                $sum4c = array();
                $sum1dd = array();
                $sum1dc = array();
                $sum2dd = array();
                $sum2dc = array();
                $sum3dd = array();
                $sum3dc = array();
                $sum4dd = array();
                $sum4dc = array();
                $idCostcenter = Costcenter::where('name',$k1)->first('id');
                foreach ($i as $k=>$subArray) {
                    foreach ($subArray as $id=>$value12) {
                        $sumArray[] = $value12['w'];
                        if($value12['w'] == $sumArray[0]) {
                            $sum1c[] = $value12['c'];
                            $sum1dd[] = $value12['sumd'];
                            $sum1dc[] = $value12['sumc'];
                        }
                        if($value12['w'] == $sumArray[0]+1) {
                            $sum2c[] = $value12['c'];
                            $sum2dd[] = $value12['sumd'];
                            $sum2dc[] = $value12['sumc'];
                        }
                        if($value12['w'] == $sumArray[0]+2) {
                            $sum3c[] = $value12['c'];
                            $sum3dd[] = $value12['sumd']; 
                            $sum3dc[] = $value12['sumc'];
                        }
                        if($value12['w'] == $sumArray[0]+3) {
                            $sum4c[] = $value12['c']; 
                            $sum4dc[] = $value12['sumc'];
                            $sum4dd[] = $value12['sumd'];
                        }
                    }
                $t1 = $this->targetAllSR($idCostcenter->id,$weeks[0],$year,1);
                $t2 = $this->targetAllSR($idCostcenter->id,$weeks[1],$year,1);
                $t3 = $this->targetAllSR($idCostcenter->id,$weeks[2],$year,1);
                $t4 = $this->targetAllSR($idCostcenter->id,$weeks[3],$year,1);
                $sumt = $t1 + $t2 + $t3 + $t4;
                $c1 = array_sum($sum1c);
                $c2 = array_sum($sum2c);
                $c3 = array_sum($sum3c);
                $c4 = array_sum($sum4c);
                $sumc = $c1 + $c2 + $c3 + $c4;
                $sumdc1 = array_sum($sum1dc);
                $sumdc2 = array_sum($sum2dc);
                $sumdc3 = array_sum($sum3dc);
                $sumdc4 = array_sum($sum4dc);
                $sumdc = $sumdc1 + $sumdc2 + $sumdc3 + $sumdc4;
                $sumdd1 = array_sum($sum1dd);
                $sumdd2 = array_sum($sum2dd);
                $sumdd3 = array_sum($sum3dd);
                $sumdd4 = array_sum($sum4dd);
                $sumdd = $sumdd1 + $sumdd2 + $sumdd3 + $sumdd4;
                }

                $warehouse = Costcenter::where('name',$k1)->first('warehouse');
                $target[] = [   'costcenter'=>$k1,
                                'name'=>$v1,
                                'warehouse' => $warehouse->warehouse,
                                't1' => $t1,'t2' => $t2,'t3' => $t3,'t4' => $t4,
                                'c1' => $c1,'c2' => $c2,'c3' => $c3,'c4' => $c4, 
                                'sumc' => $sumc , 'sumt' => $sumt, 
                                'sumdc' => $sumdc, 'sumdd' => $sumdd, 
                                'sumdc1' => $sumdc1, 'sumdd1' => $sumdd1,
                                'sumdc2' => $sumdc2, 'sumdd2' => $sumdd2,
                                'sumdc3' => $sumdc3, 'sumdd3' => $sumdd3,
                                'sumdc4' => $sumdc4, 'sumdd4' => $sumdd4,
                            ];
                foreach ($target as $khoa => $item) {
                    $showroom[$item['warehouse']][$khoa] = [
                        'costcenter' => $item['costcenter'],
                        'warehouse' => $item['warehouse'],
                        't1' => $item['t1'] ,'t2' => $item['t2'],'t3' => $item['t3'],'t4' => $item['t4'],
                        'c1' => $item['c1'],'c2' => $item['c2'],'c3' => $item['c3'],'c4' => $item['c4'], 
                        'sumc' => $item['sumc'] , 'sumt' => $item['sumt'], 
                        'sumdc' => $item['sumdc'], 'sumdd' => $item['sumdd'] ,
                        'sumdc1' => $item['sumdc1'], 'sumdd1' => $item['sumdd1'],
                        'sumdc2' => $item['sumdc2'], 'sumdd2' => $item['sumdd2'],
                        'sumdc3' => $item['sumdc3'], 'sumdd3' => $item['sumdd3'],
                        'sumdc4' => $item['sumdc4'], 'sumdd4' => $item['sumdd4'],
                        'date' => $dates,
                    ];
                }
                foreach ($showroom as $warehousename => $infoshowroom) {
                    $laytongt1 = array();$laytongc1 = array();
                    $laytongt2 = array();$laytongc2 = array();
                    $laytongt3 = array();$laytongc3 = array();
                    $laytongt4 = array();$laytongc4 = array();
                    $laytongdt1 = array();$laytongdc1 = array();
                    $laytongdt2 = array();$laytongdc2 = array();
                    $laytongdt3 = array();$laytongdc3 = array();
                    $laytongdt4 = array();$laytongdc4 = array();
                    $laytongpt = array();$laytongpc = array();
                    foreach ($infoshowroom as $getinfo) {
                        $laytongt1[$getinfo['warehouse']][] = $getinfo['t1'];$laytongc1[$getinfo['warehouse']][] = $getinfo['c1'];
                        $laytongt2[$getinfo['warehouse']][] = $getinfo['t2'];$laytongc2[$getinfo['warehouse']][] = $getinfo['c2'];
                        $laytongt3[$getinfo['warehouse']][] = $getinfo['t3'];$laytongc3[$getinfo['warehouse']][] = $getinfo['c3'];
                        $laytongt4[$getinfo['warehouse']][] = $getinfo['t4'];$laytongc4[$getinfo['warehouse']][] = $getinfo['c4'];
                        $laytongdt1[$getinfo['warehouse']][] = $getinfo['sumdd1'];$laytongdc1[$getinfo['warehouse']][] = $getinfo['sumdc1'];
                        $laytongdt2[$getinfo['warehouse']][] = $getinfo['sumdd2'];$laytongdc2[$getinfo['warehouse']][] = $getinfo['sumdc2'];
                        $laytongdt3[$getinfo['warehouse']][] = $getinfo['sumdd3'];$laytongdc3[$getinfo['warehouse']][] = $getinfo['sumdc3'];
                        $laytongdt4[$getinfo['warehouse']][] = $getinfo['sumdd4'];$laytongdc4[$getinfo['warehouse']][] = $getinfo['sumdc4'];
                        $laytongpt[$getinfo['warehouse']][] = $getinfo['sumt'];$laytongpc[$getinfo['warehouse']][] = $getinfo['sumc'];
                    }
                    $targetcost[$warehousename] = ['warehouse' => $warehousename , 'costcenter' => array_values($infoshowroom), 
                        't1' => array_sum($laytongt1[$warehousename]),'c1' => array_sum($laytongc1[$warehousename]),
                        't2' => array_sum($laytongt2[$warehousename]),'c2' => array_sum($laytongc2[$warehousename]),
                        't3' => array_sum($laytongt3[$warehousename]),'c3' => array_sum($laytongc3[$warehousename]),
                        't4' => array_sum($laytongt4[$warehousename]),'c4' => array_sum($laytongc4[$warehousename]),
                        'sumdc1' => array_sum($laytongdc1[$warehousename]), 'sumdd1' => array_sum($laytongdt1[$warehousename]),
                        'sumdc2' => array_sum($laytongdc2[$warehousename]), 'sumdd2' => array_sum($laytongdt2[$warehousename]),
                        'sumdc3' => array_sum($laytongdc3[$warehousename]), 'sumdd3' => array_sum($laytongdt3[$warehousename]),
                        'sumdc4' => array_sum($laytongdc4[$warehousename]), 'sumdd4' => array_sum($laytongdt4[$warehousename]),
                        'sumc' => array_sum($laytongpc[$warehousename]), 'sumt' => array_sum($laytongpt[$warehousename]), 
                    ];
                }
            }
        $targetcost = array_values($targetcost);
        $sumZip = $this->sumZip($targetcost);
        return array_merge($sumZip,$targetcost);
    }
    protected function targetByWeek($items, $weeks,$year){
        $dto = new DateTime();
        
        return $items->map(function($item,$key) use ($weeks,$dto,$year){
            return $weeks->map(function($week) use ($item,$dto,$year,$key){
                
                if($week > 52){
                    $year = $year - 1;
                }else{
                    $year = $year;
                }
                $dto->setISODate($year, $week);
                $ret['week_start'] = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $ret['week_end'] = $dto->format('Y-m-d');
                $targetUser = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('number');
                if($targetUser == null) {
                    $target = 0;
                }else{
                    $target = json_decode($targetUser)->number;
                }
                $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);
                $monday = Contact::where('loai',0)->where('user_id',$key)->whereDate('start_date',$date[0])->count();
                $tuesday = Contact::where('loai',0)->where('user_id',$key)->whereDate('start_date',$date[1])->count();
                $wednesday = Contact::where('loai',0)->where('user_id',$key)->whereDate('start_date',$date[2])->count();
                $thursday = Contact::where('loai',0)->where('user_id',$key)->whereDate('start_date',$date[3])->count();
                $friday = Contact::where('loai',0)->where('user_id',$key)->whereDate('start_date',$date[4])->count();
                $saturday = Contact::where('loai',0)->where('user_id',$key)->whereDate('start_date',$date[5])->count();
                $sunday = Contact::where('loai',0)->where('user_id',$key)->whereDate('start_date',$date[6])->count();
                $sumd = $monday + $tuesday + $wednesday + $thursday;
                $sumc = $friday + $saturday +$sunday;
                $targetsp = SalesTarget::where('targetable_id',$key)->where('targetable_type','App\User')->whereDate('close',$ret['week_end'])->first();
                if (empty($targetsp)) {
                    $targetsp['dahlia'] = 0;
                    $targetsp['ivy'] = 0;
                    $targetsp['iris'] = 0;
                    $targetsp['khac'] = 0;
                }

                $dal = 0;$iri = 0;$ivy = 0;$khac = 0;$idP = array();
                $totalContact = Contact::where('loai',0)->where('user_id',$key)->whereBetween('start_date',[$ret['week_start'],$ret['week_end']])->pluck('id');

                foreach ($totalContact as $key => $value) {
                    $getId = \DB::table('product_lines')->where('productable_type','NoiThatZip\Contact\Models\Contact')->where('productable_id',$value)->first();
                    if(!empty($getId)){
                        $idP[] = $getId->product_id;
                    }
                }
                if (count($idP) > 0) {
                    foreach ($idP as $v) {
                        $checkd = \DB::table('products')->where('id',$v)->where('groups',1)->get();
                        if($checkd->isNotEmpty()){
                            $dal += 1;
                        }
                        $checki = \DB::table('products')->where('id',$v)->where('groups',2)->get();
                        if($checki->isNotEmpty()){
                            $iri += 1;
                        }
                        $checkiv = \DB::table('products')->where('id',$v)->where('groups',29)->get();
                        if($checkiv->isNotEmpty()){
                            $ivy += 1;
                        }
                    }
                }

                $khac = count($idP) - $dal - $iri - $ivy;

                return [
                    'id' => $key,
                    'w' => $week,
                    't' => $target,
                    'c'=> $sumd + $sumc,
                    'monday' => ($monday == 0) ? '.' : $monday,
                    'tuesday' => ($tuesday == 0) ? '.' : $tuesday,
                    'wednesday' => ($wednesday == 0) ? '.' : $wednesday,
                    'thursday' => ($thursday == 0) ? '.' : $thursday,
                    'friday' => ($friday == 0) ? '.' : $friday,
                    'saturday' => ($saturday == 0) ? '.' : $saturday,
                    'sunday' => ($sunday == 0) ? '.' : $sunday,
                    'sumd' => $sumd,
                    'sumc' => $sumc,
                    'tdahlia' => $targetsp['dahlia'],
                    'tivy' => $targetsp['ivy'],
                    'tiris' => $targetsp['iris'],
                    'tkhac' => $targetsp['khac'],
                    'cdahlia' => $dal,
                    'civy' => $ivy,
                    'ciris' => $iri,
                    'ckhac' => $khac,
                ];
            });
        });
    }
    protected function targetByWeekLeads($items, $weeks,$year)
    {
        $dto = new DateTime();
        
        return $items->map(function($item,$key) use ($weeks,$dto,$year){
            return $weeks->map(function($week) use ($item,$dto,$year,$key){
                if($week > 52){
                    $year = $year - 1;
                }else{
                    $year = $year;
                }
                $dto->setISODate($year, $week);
                $ret['week_start'] = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $ret['week_end'] = $dto->format('Y-m-d');
                $targetUser = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('kh15_number');
                if($targetUser == null) {
                    $target = 0;
                }else{
                    $target = json_decode($targetUser)->kh15_number;
                }
                $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);
                $monday = Lead::where('user_id',$key)->whereDate('start_date',$date[0])->count();
                $tuesday = Lead::where('user_id',$key)->whereDate('start_date',$date[1])->count();
                $wednesday = Lead::where('user_id',$key)->whereDate('start_date',$date[2])->count();
                $thursday = Lead::where('user_id',$key)->whereDate('start_date',$date[3])->count();
                $friday = Lead::where('user_id',$key)->whereDate('start_date',$date[4])->count();
                $saturday = Lead::where('user_id',$key)->whereDate('start_date',$date[5])->count();
                $sunday = Lead::where('user_id',$key)->whereDate('start_date',$date[6])->count();
                $sumd = $monday + $tuesday + $wednesday + $thursday;
                $sumc = $friday + $saturday +$sunday;

                $targetsp = SalesTarget::where('targetable_id',$key)->where('targetable_type','App\User')->whereDate('close',$ret['week_end'])->first();
                if (empty($targetsp)) {
                    $targetsp['dahlia'] = 0;
                    $targetsp['ivy'] = 0;
                    $targetsp['iris'] = 0;
                    $targetsp['khac'] = 0;
                }

                $dal = 0;$iri = 0;$ivy = 0;$khac = 0;$idP = array();
                $totalContact = Contact::where('loai',0)->where('user_id',$key)->whereBetween('start_date',[$ret['week_start'],$ret['week_end']])->pluck('id');

                foreach ($totalContact as $key => $value) {
                    $getId = \DB::table('product_lines')->where('productable_type','NoiThatZip\Lead\Models\Lead')->where('productable_id',$value)->first();
                    if(!empty($getId)){
                        $idP[] = $getId->product_id;
                    }
                }
                if (count($idP) > 0) {
                    foreach ($idP as $v) {
                        $checkd = \DB::table('products')->where('id',$v)->where('groups',1)->get();
                        if($checkd->isNotEmpty()){
                            $dal += 1;
                        }
                        $checki = \DB::table('products')->where('id',$v)->where('groups',2)->get();
                        if($checki->isNotEmpty()){
                            $iri += 1;
                        }
                        $checkiv = \DB::table('products')->where('id',$v)->where('groups',29)->get();
                        if($checkiv->isNotEmpty()){
                            $ivy += 1;
                        }
                    }
                }

                $khac = count($idP) - $dal - $iri - $ivy;
                return [
                    'w' => $week,
                    't' => $target,
                    'id' => $key,
                    'c'=> $sumd + $sumc ,
                    'monday' => ($monday == 0) ? '.' : $monday,
                    'tuesday' => ($tuesday == 0) ? '.' : $tuesday,
                    'wednesday' => ($wednesday == 0) ? '.' : $wednesday,
                    'thursday' => ($thursday == 0) ? '.' : $thursday,
                    'friday' => ($friday == 0) ? '.' : $friday,
                    'saturday' => ($saturday == 0) ? '.' : $saturday,
                    'sunday' => ($sunday == 0) ? '.' : $sunday,
                    'sumd' => $sumd,
                    'sumc' => $sumc,
                    'tdahlia' => $targetsp['dahlia'],
                    'tivy' => $targetsp['ivy'],
                    'tiris' => $targetsp['iris'],
                    'tkhac' => $targetsp['khac'],
                    'cdahlia' => $dal,
                    'civy' => $ivy,
                    'ciris' => $iri,
                    'ckhac' => $khac,
                ];
            });
        });
    }
    protected function getDatesFromRange($start, $end, $format = 'Y-m-d') { 
      
        $array = array(); 
        $interval = new DateInterval('P1D'); 
      
        $realEnd = new DateTime($end); 
        $realEnd->add($interval); 
      
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
      
        foreach($period as $date) {                  
            $array[] = $date->format($format);  
        } 
      
        return $array; 
    } 
    public function targetOrder(Request $request)
    {
        $dangnhap = \Auth::user()->id;
        $dates = $request['dates'];
        $iduserSR = \DB::table('model_has_costcenters')->where('model_id',\Auth::user()->id)->where('model_type','App\User')->pluck('costcenter_id');
        $iduserSR = ($iduserSR->isEmpty()) ? array(1) : json_decode($iduserSR);
        $r_costcenter = isset($request['costcenter']) ? $request['costcenter'] : $iduserSR;

        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        if($start_week > 52){
            if($end_week == 3){
                $weeks = collect([$start_week, 1,2,3]);
            }else{
                $weeks = collect([$start_week, 54,1,2]);
            }
            
        }else{
            $weeks = collect(range($start_week, $end_week));
        }
        $orders = Order::filter($request->all())->get();
        $orders = $orders->groupBy(function($contact){
           return $contact->user_id;
        });

        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $ss1 = strtotime($dates[0]);

        $hienthi = 1;
        if ($dangnhap != 9003 && $dangnhap != 9074) {
            if (strtotime($dt->toDateString()) <  $ss1) { 
                $role_watch = \DB::table('roles_target')->where('d',$dates[0])->where('user_id',$dangnhap)->get();
                if ($role_watch->isEmpty()) {
                    $hienthi = 0;
                }
            }
        }

        $orders = $orders->map(function($contactsList,$key){
            return $contactsList->countBy(function($contact){
                    $date = new Carbon($contact->created_at);
                    return $date->weekOfYear;
                });
        });
        
        $year = date('Y',strtotime($dates[1]));
        $idSales =  $this->sales()->map(function($id){
            return $id->id;
        });
        $idcos = Costcenter::where('closed','<',$dates[0])->pluck('id')->toArray();
        $r_costcenter = array_diff( $r_costcenter, $idcos );
        $r_costcenter = array_values($r_costcenter);
        foreach ($r_costcenter as $khoa => $idCostcenter) {
            $user = User::select('id', 'name')->whereIn('id',$idSales)->whereDate('date_off','>',$dates[0])
                ->where(function ($query) use ($idCostcenter){
                    $query->orWhereIn('id', Costcenter::find($idCostcenter)->entries(User::class)->pluck('id'));
                })
            ->distinct()
            ->get();
            $orders = $this->targetByWeekOrder($user, $weeks, $year, $idCostcenter);
            $sales = $user->map(function($user) use ($orders){
                $sr = $user->costcentersList();
                return[
                    'id' => $user->id,
                    'name' => $user->name,
                    'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
                    'id' => $sr->isNotEmpty() ? $sr->first()->id : 'Khac',
                    'contacts' => $orders[$user->id],
                    't' => collect($orders[$user->id])->sum('t'),
                    'c' => collect($orders[$user->id])->sum('c'),
                    'sm' => $this->checksm($user->id),
                    'cdahlia' => collect($orders[$user->id])->sum('cdahlia'),
                    'civy' => collect($orders[$user->id])->sum('civy'),
                    'ciris' => collect($orders[$user->id])->sum('ciris'),
                    'ckhac' => collect($orders[$user->id])->sum('ckhac'),
                    'tdahlia' => collect($orders[$user->id])->sum('tdahlia'),
                    'tivy' => collect($orders[$user->id])->sum('tivy'),
                    'tiris' => collect($orders[$user->id])->sum('tiris'),
                    'tkhac' => collect($orders[$user->id])->sum('tkhac'),
                ];
            });
            $group = array();
            foreach ($sales as $value) { //lấy ra danh sách theo showroom
                $group[$value['id']][] = $value;
            }
            $list = array();
            foreach ($group[$idCostcenter] as $key => $v) { //lấy ra user của showroom theo tìm kiếm
                $list[] = $v;
            }
            $targetnotsum[] = [
                            'costcenter' => Costcenter::find($idCostcenter)->name,
                            'name' => $list
                        ]; // mảng chưa có tổng
            $subArray = array();
            $sumt = array(); $sum1t = array(); $sum2t = array(); $sum3t = array(); $sum4t = array(); 
            $sumc = array(); $sum1c = array(); $sum2c = array(); $sum3c = array(); $sum4c = array(); 
            $sum1dd = array();$sum1dc = array();$sum2dd = array();$sum2dc = array();
            $sum3dd = array();$sum3dc = array();$sum4dd = array();$sum4dc = array();
            $sumM1 = array();$sumM2 = array();$sumM3 = array();$sumM4 = array();
            $sumTU1 = array();$sumTU2 = array();$sumTU3 = array();$sumTU4 = array();
            $sumSU1 = array();$sumSU2 = array();$sumSU3 = array();$sumSU4 = array();
            $sumSA1 = array();$sumSA2 = array();$sumSA3 = array();$sumSA4 = array();
            $sumTH1 = array();$sumTH2 = array();$sumTH3 = array();$sumTH4 = array();
            $sumF1 = array();$sumF2 = array();$sumF3 = array();$sumF4 = array();
            $sumW1 = array();$sumW2 = array();$sumW3 = array();$sumW4 = array();
            $sumDa1 = array();$sumDa2 = array();$sumDa3 = array();$sumDa4 = array();
            $sumIv1 = array();$sumIv2 = array();$sumIv3 = array();$sumIv4 = array();
            $sumIr1 = array();$sumIr2 = array();$sumIr3 = array();$sumIr4 = array();
            $sumK1 = array();$sumK2 = array();$sumK3 = array();$sumK4 = array();
            $sumDat1 = array();$sumDat2 = array();$sumDat3 = array();$sumDat4 = array();
            $sumIvt1 = array();$sumIvt2 = array();$sumIvt3 = array();$sumIvt4 = array();
            $sumIrt1 = array();$sumIrt2 = array();$sumIrt3 = array();$sumIrt4 = array();
            $sumKt1 = array();$sumKt2 = array();$sumKt3 = array();$sumKt4 = array();
            foreach ($targetnotsum[$khoa]['name'] as $value) {
                $infosum[0]=$value;
                $listcontact = array();
                foreach ($infosum as $k => $v) {
                    $listcontact[$k] = $v['contacts'];
                }
                foreach ($listcontact as $k=>$subArray) {
                    foreach ($subArray as $id=>$getContact) {
                        $sumArray[] = $getContact->w;
                        if($getContact->w == $sumArray[0]) {
                            $sum1t[] = $getContact->t; 
                            $sum1c[] = $getContact->c;
                            $sum1dd[] = $getContact->sumd;
                            $sum1dc[] = $getContact->sumc;
                            $sumM1[] = $getContact->monday;
                            $sumTU1[] = $getContact->tuesday;
                            $sumW1[] = $getContact->wednesday;
                            $sumSU1[] = $getContact->sunday;
                            $sumSA1[] = $getContact->saturday;
                            $sumTH1[] = $getContact->thursday;
                            $sumF1[] = $getContact->friday;
                            $amount1[] = $getContact->amount;
                            $sumDa1[] = isset($getContact->cdahlia) ? $getContact->cdahlia : 0;
                            $sumIv1[] = isset($getContact->civy) ? $getContact->civy : 0;
                            $sumIr1[] = isset($getContact->ciris) ? $getContact->ciris : 0;
                            $sumK1[] = isset($getContact->ckhac) ? $getContact->ckhac : 0;
                            $sumDat1[] = isset($getContact->tdahlia) ? $getContact->tdahlia : 0;
                            $sumIvt1[] = isset($getContact->tivy) ? $getContact->tivy : 0;
                            $sumIrt1[] = isset($getContact->tiris) ? $getContact->tiris : 0;
                            $sumKt1[] = isset($getContact->tkhac) ? $getContact->tkhac : 0;
                        }
                        if($getContact->w == $sumArray[0]+1) {
                            $sum2t[] = $getContact->t; 
                            $sum2c[] = $getContact->c;
                            $sum2dd[] = $getContact->sumd;
                            $sum2dc[] = $getContact->sumc;
                            $sumM2[] = $getContact->monday;
                            $sumTU2[] = $getContact->tuesday;
                            $sumW2[] = $getContact->wednesday;
                            $sumSU2[] = $getContact->sunday;
                            $sumSA2[] = $getContact->saturday;
                            $sumTH2[] = $getContact->thursday;
                            $sumF2[] = $getContact->friday;
                            $amount2[] = $getContact->amount;
                            $sumDa2[] = isset($getContact->cdahlia) ? $getContact->cdahlia : 0;
                            $sumIv2[] = isset($getContact->civy) ? $getContact->civy : 0;
                            $sumIr2[] = isset($getContact->ciris) ? $getContact->ciris : 0;
                            $sumK2[] = isset($getContact->ckhac) ? $getContact->ckhac : 0;
                            $sumDat2[] = isset($getContact->tdahlia) ? $getContact->tdahlia : 0;
                            $sumIvt2[] = isset($getContact->tivy) ? $getContact->tivy : 0;
                            $sumIrt2[] = isset($getContact->tiris) ? $getContact->tiris : 0;
                            $sumKt2[] = isset($getContact->tkhac) ? $getContact->tkhac : 0;
                        }
                        if($getContact->w == $sumArray[0]+2) {
                            $sum3t[] = $getContact->t; 
                            $sum3c[] = $getContact->c;
                            $sum3dd[] = $getContact->sumd; 
                            $sum3dc[] = $getContact->sumc;
                            $sumM3[] = $getContact->monday;
                            $sumTU3[] = $getContact->tuesday;
                            $sumW3[] = $getContact->wednesday;
                            $sumSU3[] = $getContact->sunday;
                            $sumSA3[] = $getContact->saturday;
                            $sumTH3[] = $getContact->thursday;
                            $sumF3[] = $getContact->friday;
                            $amount3[] = $getContact->amount;
                            $sumDa3[] = isset($getContact->cdahlia) ? $getContact->cdahlia : 0;
                            $sumIv3[] = isset($getContact->civy) ? $getContact->civy : 0;
                            $sumIr3[] = isset($getContact->ciris) ? $getContact->ciris : 0;
                            $sumK3[] = isset($getContact->ckhac) ? $getContact->ckhac : 0;
                            $sumDat3[] = isset($getContact->tdahlia) ? $getContact->tdahlia : 0;
                            $sumIvt3[] = isset($getContact->tivy) ? $getContact->tivy : 0;
                            $sumIrt3[] = isset($getContact->tiris) ? $getContact->tiris : 0;
                            $sumKt3[] = isset($getContact->tkhac) ? $getContact->tkhac : 0;
                        }
                        if($getContact->w == $sumArray[0]+3) {
                            $sum4t[] = $getContact->t; 
                            $sum4c[] = $getContact->c; 
                            $sum4dc[] = $getContact->sumc;
                            $sum4dd[] = $getContact->sumd;
                            $sumM4[] = $getContact->monday;
                            $sumTU4[] = $getContact->tuesday;
                            $sumW4[] = $getContact->wednesday;
                            $sumSU4[] = $getContact->sunday;
                            $sumSA4[] = $getContact->saturday;
                            $sumTH4[] = $getContact->thursday;
                            $sumF4[] = $getContact->friday;
                            $amount4[] = $getContact->amount;
                            $sumDa4[] = isset($getContact->cdahlia) ? $getContact->cdahlia : 0;
                            $sumIv4[] = isset($getContact->civy) ? $getContact->civy : 0;
                            $sumIr4[] = isset($getContact->ciris) ? $getContact->ciris : 0;
                            $sumK4[] = isset($getContact->ckhac) ? $getContact->ckhac : 0;
                            $sumDat4[] = isset($getContact->tdahlia) ? $getContact->tdahlia : 0;
                            $sumIvt4[] = isset($getContact->tivy) ? $getContact->tivy : 0;
                            $sumIrt4[] = isset($getContact->tiris) ? $getContact->tiris : 0;
                            $sumKt4[] = isset($getContact->tkhac) ? $getContact->tkhac : 0;
                        }
                    }
                    $t1 = array_sum($sum1t);
                    $t2 = array_sum($sum2t);
                    $t3 = array_sum($sum3t);
                    $t4 = array_sum($sum4t);
                    $sumt = $t1 + $t2 + $t3 + $t4;
                    $c1 = array_sum($sum1c);
                    $c2 = array_sum($sum2c);
                    $c3 = array_sum($sum3c);
                    $c4 = array_sum($sum4c);
                    $sumc = $c1 + $c2 + $c3 + $c4; 
                    $sumdc1 = array_sum($sum1dc);
                    $sumdc2 = array_sum($sum2dc);
                    $sumdc3 = array_sum($sum3dc);
                    $sumdc4 = array_sum($sum4dc);
                    $sumdc = $sumdc1 + $sumdc2 + $sumdc3 + $sumdc4;
                    $sumdd1 = array_sum($sum1dd);
                    $sumdd2 = array_sum($sum2dd);
                    $sumdd3 = array_sum($sum3dd);
                    $sumdd4 = array_sum($sum4dd);
                    $sumdd = $sumdd1 + $sumdd2 + $sumdd3 + $sumdd4;
                    $sumamount4 = array_sum($amount4);
                    $sumamount1 = array_sum($amount1);
                    $sumamount2 = array_sum($amount2);
                    $sumamount3 = array_sum($amount3);
                    // $sumamount4 = array_sum($amount4);
                    $sumMt1 = array_sum($sumM1);$sumMt2 = array_sum($sumM2);$sumMt3 = array_sum($sumM3);$sumMt4 = array_sum($sumM4);
                    $sumTUt1 = array_sum($sumTU1);$sumTUt2 = array_sum($sumTU2);$sumTUt3 = array_sum($sumTU3);$sumTUt4 = array_sum($sumTU4);
                    $sumWt1 = array_sum($sumW1);$sumWt2 = array_sum($sumW2);$sumWt3 = array_sum($sumW3);$sumWt4 = array_sum($sumW4);
                    $sumSUt1 = array_sum($sumSU1);$sumSUt2 = array_sum($sumSU2);$sumSUt3 = array_sum($sumSU3);$sumSUt4 = array_sum($sumSU4);
                    $sumSAt1 = array_sum($sumSA1);$sumSAt2 = array_sum($sumSA2);$sumSAt3 = array_sum($sumSA3);$sumSAt4 = array_sum($sumSA4);
                    $sumTHt1 = array_sum($sumTH1);$sumTHt2 = array_sum($sumTH2);$sumTHt3 = array_sum($sumTH3);$sumTHt4 = array_sum($sumTH4);
                    $sumFt1 = array_sum($sumF1);$sumFt2 = array_sum($sumF2);$sumFt3 = array_sum($sumF3);$sumFt4 = array_sum($sumF4);
                    $sumDah1 = array_sum($sumDa1);$sumDah2 = array_sum($sumDa2);$sumDah3 = array_sum($sumDa3);$sumDah4 = array_sum($sumDa4);
                    $cdahlia = $sumDah1 + $sumDah2 + $sumDah3 + $sumDah4; 
                    $sumivy1 = array_sum($sumIv1);$sumivy2 = array_sum($sumIv2);$sumivy3 = array_sum($sumIv3);$sumivy4 = array_sum($sumIv4);
                    $civy = $sumivy1 + $sumivy2 + $sumivy3 + $sumivy4; 
                    $sumiris1 = array_sum($sumIr1);$sumiris2 = array_sum($sumIr2);$sumiris3 = array_sum($sumIr3);$sumiris4 = array_sum($sumIr4);
                    $ciris = $sumiris1 + $sumiris2 + $sumiris3 + $sumiris4; 
                    $sumkh1 = array_sum($sumK1);$sumkh2 = array_sum($sumK2);$sumkh3 = array_sum($sumK3);$sumkh4 = array_sum($sumK4);
                    $ckhac = $sumkh1 + $sumkh2 + $sumkh3 + $sumkh4;

                    $sumDaht1 = array_sum($sumDat1);$sumDaht2 = array_sum($sumDat2);$sumDaht3 = array_sum($sumDat3);$sumDaht4 = array_sum($sumDat4);
                    $tdahlia = $sumDaht1 + $sumDaht2 + $sumDaht3 + $sumDaht4; 

                    $sumivyt1 = array_sum($sumIvt1);$sumivyt2 = array_sum($sumIvt2);$sumivyt3 = array_sum($sumIvt3);$sumivyt4 = array_sum($sumIvt4);
                    $tivy = $sumivyt1 + $sumivyt2 + $sumivyt3 + $sumivyt4; 

                    $sumirist1 = array_sum($sumIrt1);$sumirist2 = array_sum($sumIrt2);$sumirist3 = array_sum($sumIrt3);$sumirist4 = array_sum($sumIrt4);
                    $tiris = $sumirist1 + $sumirist2 + $sumirist3 + $sumirist4; 

                    $sumkht1 = array_sum($sumKt1);$sumkht2 = array_sum($sumKt2);$sumkht3 = array_sum($sumKt3);$sumkht4 = array_sum($sumKt4);
                    $tkhac = $sumkht1 + $sumkht2 + $sumkht3 + $sumkht4; 
                }
            }
            $tsr1 = $this->targetAllSR($idCostcenter,$weeks[0],$year,3);
            $tsr2 = $this->targetAllSR($idCostcenter,$weeks[1],$year,3);
            $tsr3 = $this->targetAllSR($idCostcenter,$weeks[2],$year,3);
            $tsr4 = $this->targetAllSR($idCostcenter,$weeks[3],$year,3);
            $sumtsr = $tsr1 + $tsr2 + $tsr3 + $tsr4 ;
            $target[] = [   
                        'costcenter' => Costcenter::find($idCostcenter)->name,
                        'name' => $list,
                        'hienthi'=>$hienthi,
                        'checksales' => $this->checksales(\Auth::user()->id),
                        't1' => $t1,'t2' => $t2,'t3' => $t3,'t4' => $t4,
                        'c1' => $c1,'c2' => $c2,'c3' => $c3,'c4' => $c4,
                        'tsr1' => $tsr1,'tsr4' => $tsr4,'tsr3' => $tsr3,'tsr2' => $tsr2,'sumtsr' => $sumtsr, 
                        'sumc' => $sumc , 'sumt' => $sumt, 
                        'sumdc' => $sumdc, 'sumdd' => $sumdd, 
                        'sumdc1' => $sumdc1, 'sumdd1' => $sumdd1,
                        'sumdc2' => $sumdc2, 'sumdd2' => $sumdd2,
                        'sumdc3' => $sumdc3, 'sumdd3' => $sumdd3,
                        'sumdc4' => $sumdc4, 'sumdd4' => $sumdd4,
                        'sumMt1' => ($sumMt1 == 0) ? '.' : $sumMt1, 'sumMt2' => ($sumMt2 == 0) ? '.' : $sumMt2,'sumMt3' => ($sumMt3 == 0) ? '.' : $sumMt3, 'sumMt4' => ($sumMt4 == 0) ? '.' : $sumMt4,
                        'sumTUt1' => ($sumTUt1 == 0) ? '.' : $sumTUt1, 'sumTUt2' => ($sumTUt2 == 0) ? '.' : $sumTUt2,'sumTUt3' => ($sumTUt3 == 0) ? '.' : $sumTUt3, 'sumTUt4' => ($sumTUt4 == 0) ? '.' : $sumTUt4,
                        'sumWt1' => ($sumWt1 == 0) ? '.' : $sumWt1, 'sumWt2' => ($sumWt2 == 0) ? '.' : $sumWt2,'sumWt3' => ($sumWt3 == 0) ? '.' : $sumWt3, 'sumWt4' => ($sumWt4 == 0) ? '.' : $sumWt4,
                        'sumSUt1' => ($sumSUt1 == 0) ? '.' : $sumSUt1, 'sumSUt2' => ($sumSUt2 == 0) ? '.' : $sumSUt2,'sumSUt3' => ($sumSUt3 == 0) ? '.' : $sumSUt3, 'sumSUt4' => ($sumSUt4 == 0) ? '.' : $sumSUt4,
                        'sumSAt1' => ($sumSAt1 == 0) ? '.' : $sumSAt1, 'sumSAt2' =>($sumSAt2 == 0) ? '.' : $sumSAt2,'sumSAt3' => ($sumSAt3 == 0) ? '.' : $sumSAt3, 'sumSAt4' => ($sumSAt4 == 0) ? '.' : $sumSAt4,
                        'sumTHt1' => ($sumTHt1 == 0) ? '.' : $sumTHt1, 'sumTHt2' => ($sumTHt2 == 0) ? '.' : $sumTHt2,'sumTHt3' => ($sumTHt3 == 0) ? '.' : $sumTHt3, 'sumTHt4' => ($sumTHt4 == 0) ? '.' : $sumTHt4,
                        'sumFt1' => ($sumFt1 == 0) ? '.' : $sumFt1, 'sumFt2' => ($sumFt2 == 0) ? '.' : $sumFt2,'sumFt3' => ($sumFt3 == 0) ? '.' : $sumFt3, 'sumFt4' => ($sumFt4 == 0) ? '.' : $sumFt4,
                        'sumamount1'=>$sumamount1,'sumamount4'=>$sumamount4,'sumamount2'=>$sumamount2,'sumamount3'=>$sumamount3,

                        'cdahlia' =>$cdahlia,'ciris'=>$ciris,'civy'=>$civy,'ckhac'=>$ckhac,
                        'cdahlia1' =>$sumDah1,'ciris1'=>$sumiris1,'civy1'=>$sumivy1,'ckhac1'=>$sumkh1,
                        'cdahlia2' =>$sumDah2,'ciris2'=>$sumiris2,'civy2'=>$sumivy2,'ckhac2'=>$sumkh2,
                        'cdahlia3' =>$sumDah3,'ciris3'=>$sumiris3,'civy3'=>$sumivy3,'ckhac3'=>$sumkh3,
                        'cdahlia4' =>$sumDah4,'ciris4'=>$sumiris4,'civy4'=>$sumivy4,'ckhac4'=>$sumkh4,

                        'tdahlia' =>$tdahlia,'tiris'=>$tiris,'tivy'=>$tivy,'tkhac'=>$tkhac,
                        'tdahlia1' =>$sumDaht1,'tiris1'=>$sumirist1,'tivy1'=>$sumivyt1,'tkhac1'=>$sumkht1,
                        'tdahlia2' =>$sumDaht2,'tiris2'=>$sumirist2,'tivy2'=>$sumivyt2,'tkhac2'=>$sumkht2,
                        'tdahlia3' =>$sumDaht3,'tiris3'=>$sumirist3,'tivy3'=>$sumivyt3,'tkhac3'=>$sumkht3,
                        'tdahlia4' =>$sumDaht4,'tiris4'=>$sumirist4,'tivy4'=>$sumivyt4,'tkhac4'=>$sumkht4,
                    ];
        }
        if(count($r_costcenter) > 2){
            foreach ($target as $tong) {
                $tongc[] = $tong['sumc'];$tongt[] = $tong['sumt']; 
                $tongt1[] = $tong['t1'];$tongt2[] = $tong['t2']; $tongt3[] = $tong['t3']; $tongt4[] = $tong['t4'];    
                $tongc1[] = $tong['c1'];$tongc2[] = $tong['c2']; $tongc3[] = $tong['c3']; $tongc4[] = $tong['c4'];
                $tongsumMt1[] = $tong['sumMt1'];$tongsumMt2[] = $tong['sumMt2']; $tongsumMt3[] = $tong['sumMt3']; $tongsumMt4[] = $tong['sumMt4'];
                $tongsumTUt1[] = $tong['sumTUt1'];$tongsumTUt2[] = $tong['sumTUt2']; $tongsumTUt3[] = $tong['sumTUt3']; $tongsumTUt4[] = $tong['sumTUt4'];
                $tongsumWt1[] = $tong['sumWt1'];$tongsumWt2[] = $tong['sumWt2']; $tongsumWt3[] = $tong['sumWt3']; $tongsumWt4[] = $tong['sumWt4'];
                $tongsumSUt1[] = $tong['sumSUt1'];$tongsumSUt2[] = $tong['sumSUt2']; $tongsumSUt3[] = $tong['sumSUt3']; $tongsumSUt4[] = $tong['sumSUt4'];
                $tongsumSAt1[] = $tong['sumSAt1'];$tongsumSAt2[] = $tong['sumSAt2']; $tongsumSAt3[] = $tong['sumSAt3']; $tongsumSAt4[] = $tong['sumSAt4'];
                $tongsumTHt1[] = $tong['sumTHt1'];$tongsumTHt2[] = $tong['sumTHt2']; $tongsumTHt3[] = $tong['sumTHt3']; $tongsumTHt4[] = $tong['sumTHt4'];
                $tongsumFt1[] = $tong['sumFt1'];$tongsumFt2[] = $tong['sumFt2']; $tongsumFt3[] = $tong['sumFt3']; $tongsumFt4[] = $tong['sumFt4'];

                $tongsumdc[] = $tong['sumdc']; $tongsumdd[] = $tong['sumdd'];
                $tongsumdc1[] = $tong['sumdc1']; $tongsumdd1[] = $tong['sumdd1'];
                $tongsumdc2[] = $tong['sumdc2']; $tongsumdd2[] = $tong['sumdd2'];
                $tongsumdc3[] = $tong['sumdc3']; $tongsumdd3[] = $tong['sumdd3'];
                $tongsumdc4[] = $tong['sumdc4']; $tongsumdd4[] = $tong['sumdd4'];
                $tongsumamount1[]= $tong['sumamount1']; $tongsumamount4[] = $tong['sumamount4'];$tongsumamount2[]= $tong['sumamount2']; $tongsumamount3[] = $tong['sumamount3'];

                $tongtsr1[] = $tong['tsr1'];$tongtsr4[] = $tong['tsr4'];$tongtsr3[] = $tong['tsr3'];$tongtsr2[] = $tong['tsr2'];$tongsumtsr[] = $tong['sumtsr'];

                $tongsumdc[] = $tong['sumdc']; $tongsumdd[] = $tong['sumdd'];
                $tongsumdc1[] = $tong['sumdc1']; $tongsumdd1[] = $tong['sumdd1'];
                $tongsumdc2[] = $tong['sumdc2']; $tongsumdd2[] = $tong['sumdd2'];
                $tongsumdc3[] = $tong['sumdc3']; $tongsumdd3[] = $tong['sumdd3'];
                $tongsumdc4[]= $tong['sumdc4']; $tongsumdd4[] = $tong['sumdd4'];

                $tongsumtir[] = $tong['tiris'];$tongsumtir1[] = $tong['tiris1'];$tongsumtir2[] = $tong['tiris2'];$tongsumtir3[] = $tong['tiris3'];$tongsumtir4[] = $tong['tiris4'];
                $tongsumcir[] = $tong['ciris'];$tongsumcir1[] = $tong['ciris1'];$tongsumcir2[] = $tong['ciris2'];$tongsumcir3[] = $tong['ciris3'];$tongsumcir4[] = $tong['ciris4'];

                $tongsumtk[] = $tong['tkhac'];$tongsumtk1[] = $tong['tkhac1'];$tongsumtk2[] = $tong['tkhac2'];$tongsumtk3[] = $tong['tkhac3'];$tongsumtk4[] = $tong['tkhac1'];
                $tongsumck[] = $tong['ckhac'];$tongsumck1[] = $tong['ckhac1'];$tongsumck2[] = $tong['ckhac2'];$tongsumck3[] = $tong['ckhac3'];$tongsumck4[] = $tong['ckhac4'];

                $tongsumtda[] = $tong['tdahlia'];$tongsumtda1[] = $tong['tdahlia1'];$tongsumtda2[] = $tong['tdahlia2'];$tongsumtda3[] = $tong['tdahlia3'];$tongsumtda4[] = $tong['tdahlia4'];
                $tongsumcda[] = $tong['sumdc1'];$tongsumcda1[] = $tong['cdahlia1'];$tongsumcda2[] = $tong['cdahlia2'];$tongsumcda3[] = $tong['cdahlia3'];$tongsumcda4[] = $tong['cdahlia4'];
                
                $tongsumtiv[] = $tong['tivy'];$tongsumtiv1[] = $tong['tivy1'];$tongsumtiv2[] = $tong['tivy2'];$tongsumtiv3[] = $tong['tivy3'];$tongsumtiv4[] = $tong['tivy4'];
                $tongsumciv[] = $tong['civy'];$tongsumciv1[] = $tong['civy1'];$tongsumciv2[] = $tong['civy2'];$tongsumciv3[] = $tong['civy3'];$tongsumciv4[] = $tong['civy4'];
            }    
            if ($request->all == 1) {
                $costcenter_name = 'Tất Cả';
            }else{
                $costcenter_name = Costcenter::whereIn('id',$r_costcenter)->first()['warehouse'];
            }

            $sumSelect = [ 

                'civy' => array_sum($tongsumciv),'civy1'=> array_sum($tongsumciv1),'civy2'=> array_sum($tongsumciv2),'civy3'=> array_sum($tongsumciv3),'civy4'=> array_sum($tongsumciv4), 
                'tivy' => array_sum($tongsumtiv),'tivy1'=> array_sum($tongsumtiv1),'tivy2'=> array_sum($tongsumtiv2),'tivy3'=> array_sum($tongsumtiv3),'tivy4'=> array_sum($tongsumtiv4), 

                'cdahlia' => array_sum($tongsumcda),'cdahlia1'=> array_sum($tongsumcda1),'cdahlia2'=> array_sum($tongsumcda2),'cdahlia3'=> array_sum($tongsumcda3),'cdahlia4'=> array_sum($tongsumcda4), 
                'tdahlia' => array_sum($tongsumtda),'tdahlia1'=> array_sum($tongsumtda1),'tdahlia2'=> array_sum($tongsumtda2),'tdahlia3'=> array_sum($tongsumtda3),'tdahlia4'=> array_sum($tongsumtda4), 

                'ciris' => array_sum($tongsumcir),'ciris1'=> array_sum($tongsumcir1),'ciris2'=> array_sum($tongsumcir2),'ciris3'=> array_sum($tongsumcir3),'ciris4'=> array_sum($tongsumcir4), 
                'tiris' => array_sum($tongsumtir),'tiris1'=> array_sum($tongsumtir1),'tiris2'=> array_sum($tongsumtir2),'tiris3'=> array_sum($tongsumtir3),'tiris4'=> array_sum($tongsumtir4), 

                'ckhac' => array_sum($tongsumck),'ckhac1'=> array_sum($tongsumck1),'ckhac2'=> array_sum($tongsumck2),'ckhac3'=> array_sum($tongsumck3),'ckhac4'=> array_sum($tongsumck4), 
                'tkhac' => array_sum($tongsumtk),'tkhac1'=> array_sum($tongsumtk1),'tkhac2'=> array_sum($tongsumtk2),'tkhac3'=> array_sum($tongsumtk3),'tkhac4'=> array_sum($tongsumtk4), 
                'costcenter' => $costcenter_name,
                'checksales' =>  $this->checksales(\Auth::user()->id),
                'sumc' => array_sum($tongc), 'sumt' => array_sum($tongt),
                't1' => array_sum($tongt1), 't2' => array_sum($tongt2),'t3' => array_sum($tongt3),'t4' => array_sum($tongt4),
                'c1' => array_sum($tongc1), 'c2' => array_sum($tongc2),'c3' => array_sum($tongc3),'c4' => array_sum($tongc4),
                'sumSUt1' => (array_sum($tongsumSUt1) == 0) ? '.' : array_sum($tongsumSUt1), 
                'sumSUt2' => (array_sum($tongsumSUt2) == 0) ? '.' : array_sum($tongsumSUt2),
                'sumSUt3' => (array_sum($tongsumSUt3) == 0) ? '.' : array_sum($tongsumSUt3),
                'sumSUt4' => (array_sum($tongsumSUt4) == 0) ? '.' : array_sum($tongsumSUt4),

                'sumTUt1' => (array_sum($tongsumTUt1) == 0) ? '.' : array_sum($tongsumTUt1), 
                'sumTUt2' => (array_sum($tongsumTUt2) == 0) ? '.' : array_sum($tongsumTUt2),
                'sumTUt3' => (array_sum($tongsumTUt3) == 0) ? '.' : array_sum($tongsumTUt3),
                'sumTUt4' => (array_sum($tongsumTUt4) == 0) ? '.' : array_sum($tongsumTUt4),

                'sumMt1' => (array_sum($tongsumMt1) == 0) ? '.' : array_sum($tongsumMt1),
                'sumMt2' => (array_sum($tongsumMt2) == 0) ? '.' : array_sum($tongsumMt2) ,
                'sumMt3' => (array_sum($tongsumMt3) == 0) ? '.' : array_sum($tongsumMt3) ,
                'sumMt4' => (array_sum($tongsumMt4) == 0) ? '.' : array_sum($tongsumMt4) ,

                'sumWt1' => (array_sum($tongsumWt1) == 0) ? '.' : array_sum($tongsumWt1) ,
                'sumWt2' => (array_sum($tongsumWt2) == 0) ? '.' : array_sum($tongsumWt2),
                'sumWt3' => (array_sum($tongsumWt3) == 0) ? '.' : array_sum($tongsumWt3),
                'sumWt4' => (array_sum($tongsumWt4) == 0) ? '.' : array_sum($tongsumWt4),

                'sumSAt1' => (array_sum($tongsumSAt1) == 0) ? '.' : array_sum($tongsumSAt1), 
                'sumSAt2' => (array_sum($tongsumSAt2) == 0) ? '.' : array_sum($tongsumSAt2),
                'sumSAt3' => (array_sum($tongsumSAt3) == 0) ? '.' : array_sum($tongsumSAt3),
                'sumSAt4' => (array_sum($tongsumSAt4) == 0) ? '.' : array_sum($tongsumSAt4),
                'sumTHt1' => (array_sum($tongsumTHt1) == 0) ? '.' : array_sum($tongsumTHt1), 
                'sumTHt2' => (array_sum($tongsumTHt2) == 0) ? '.' : array_sum($tongsumTHt2),
                'sumTHt3' => (array_sum($tongsumTHt3) == 0) ? '.' : array_sum($tongsumTHt3),
                'sumTHt4' => (array_sum($tongsumTHt4) == 0) ? '.' : array_sum($tongsumTHt4),
                'sumFt1' => (array_sum($tongsumFt1) == 0) ? '.' : array_sum($tongsumFt1) ,
                'sumFt2' => (array_sum($tongsumFt2) == 0) ? '.' : array_sum($tongsumFt2),
                'sumFt3' => (array_sum($tongsumFt3) == 0) ? '.' : array_sum($tongsumFt3),
                'sumFt4' => (array_sum($tongsumFt4) == 0) ? '.' : array_sum($tongsumFt4),

                'tsr1' => array_sum($tongtsr1),'tsr4' => array_sum($tongtsr4),'tsr3' => array_sum($tongtsr3),'tsr2' => array_sum($tongtsr2),'sumtsr' => array_sum($tongsumtsr),
                'sumamount1'=>array_sum($tongsumamount1),'sumamount4'=>array_sum($tongsumamount4),'sumamount2'=>array_sum($tongsumamount2),'sumamount3'=>array_sum($tongsumamount3),

                'sumdc' => array_sum($tongsumdc), 'sumdd'=> array_sum($tongsumdd), 
                'sumdc1' => array_sum($tongsumdc1), 'sumdd1' => array_sum($tongsumdd1) ,
                'sumdc2' => array_sum($tongsumdc2),  'sumdd2' =>array_sum($tongsumdd2),
                'sumdc3' => array_sum($tongsumdc3), 'sumdd3' =>  array_sum($tongsumdd3),
                'sumdc4' => array_sum($tongsumdc4), 'sumdd4' => array_sum($tongsumdd4) ,


            ];

            $target = array_merge([0=>$sumSelect],$target);
        }
        return $target;
    }
    public function targetAmount(Request $request)
    {
        $dangnhap = \Auth::user()->id;
        $dates = $request['dates'];
        $iduserSR = \DB::table('model_has_costcenters')->where('model_id',\Auth::user()->id)->where('model_type','App\User')->pluck('costcenter_id');
        $iduserSR = ($iduserSR->isEmpty()) ? array(1) : json_decode($iduserSR);
        $r_costcenter = isset($request['costcenter']) ? $request['costcenter'] : $iduserSR;
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        if($start_week > 52){
            if($end_week == 3){
                $weeks = collect([$start_week, 1,2,3]);
            }else{
                $weeks = collect([$start_week, 54,1,2]);
            }
            
        }else{
            $weeks = collect(range($start_week, $end_week));
        }
        // return $weeks;
        $orders = Order::filter($request->all())->get();
        $orders = $orders->groupBy(function($contact){
           return $contact->user_id;
        });
        $orders = $orders->map(function($contactsList,$key){
            return $contactsList->countBy(function($contact){
                    $date = new Carbon($contact->created_at);
                    return $date->weekOfYear;
                });
        });

        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $ss1 = strtotime($dates[0]);

        $hienthi = 1;
        if ($dangnhap != 9003 && $dangnhap != 9074) {
            if (strtotime($dt->toDateString()) <  $ss1) { 
                $role_watch = \DB::table('roles_target')->where('d',$dates[0])->where('user_id',$dangnhap)->get();
                if ($role_watch->isEmpty()) {
                    $hienthi = 0;
                }
            }
        }
        
        $year = date('Y',strtotime($dates[1]));
        $idSales =  $this->sales()->map(function($id){
            return $id->id;
        });
        $idcos = Costcenter::where('closed','<',$dates[0])->pluck('id')->toArray();
        $r_costcenter = array_diff( $r_costcenter, $idcos );
        $r_costcenter = array_values($r_costcenter);
        foreach ($r_costcenter as $khoa => $idCostcenter) {
            $user = User::select('id', 'name')->whereIn('id',$idSales)->whereDate('date_off','>',$dates[0])
                ->where(function ($query) use ($idCostcenter){
                    $query->orWhereIn('id', Costcenter::find($idCostcenter)->entries(User::class)->pluck('id'));
                })
            ->distinct()
            ->get();
            $orders = $this->targetByWeekAmount($user, $weeks, $year, $idCostcenter);
            $sales = $user->map(function($user) use ($orders){
                $sr = $user->costcentersList();
                return[
                    'id' => $user->id,
                    'name' => $user->name,
                    'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
                    'id' => $sr->isNotEmpty() ? $sr->first()->id : 'Khac',
                    'contacts' => $orders[$user->id],
                    't' => collect($orders[$user->id])->sum('t'),
                    'c' => collect($orders[$user->id])->sum('c'),
                    'sm' => $this->checksm($user->id),
                    'cdahlia' => collect($orders[$user->id])->sum('cdahlia'),
                    'civy' => collect($orders[$user->id])->sum('civy'),
                    'ciris' => collect($orders[$user->id])->sum('ciris'),
                    'ckhac' => collect($orders[$user->id])->sum('ckhac'),
                    'tdahlia' => collect($orders[$user->id])->sum('tdahlia'),
                    'tivy' => collect($orders[$user->id])->sum('tivy'),
                    'tiris' => collect($orders[$user->id])->sum('tiris'),
                    'tkhac' => collect($orders[$user->id])->sum('tkhac'),
                ];
            });

            $group = array();
            foreach ($sales as $value) { //lấy ra danh sách theo showroom
                $group[$value['id']][] = $value;
            }


            $list = array();
            foreach ($group[$idCostcenter] as $key => $v) { //lấy ra user của showroom theo tìm kiếm
                $list[] = $v;
            }

            $targetnotsum[] = [
                'costcenter' => Costcenter::find($idCostcenter)->name,
                'name' => $list
            ]; // mảng chưa có tổng

            $subArray = array();
            $sumt = array(); $sum1t = array(); $sum2t = array(); $sum3t = array(); $sum4t = array(); 
            $sumc = array(); $sum1c = array(); $sum2c = array(); $sum3c = array(); $sum4c = array(); 
            $sum1dd = array();$sum1dc = array();$sum2dd = array();$sum2dc = array();
            $sum3dd = array();$sum3dc = array();$sum4dd = array();$sum4dc = array();
            $sumM1 = array();$sumM2 = array();$sumM3 = array();$sumM4 = array();
            $sumTU1 = array();$sumTU2 = array();$sumTU3 = array();$sumTU4 = array();
            $sumSU1 = array();$sumSU2 = array();$sumSU3 = array();$sumSU4 = array();
            $sumSA1 = array();$sumSA2 = array();$sumSA3 = array();$sumSA4 = array();
            $sumTH1 = array();$sumTH2 = array();$sumTH3 = array();$sumTH4 = array();
            $sumF1 = array();$sumF2 = array();$sumF3 = array();$sumF4 = array();
            $sumW1 = array();$sumW2 = array();$sumW3 = array();$sumW4 = array();
            $amount1 = array();$amount2 = array();$amount3 = array();$amount4 = array();
            $sumDa1 = array();$sumDa2 = array();$sumDa3 = array();$sumDa4 = array();
            $sumIv1 = array();$sumIv2 = array();$sumIv3 = array();$sumIv4 = array();
            $sumIr1 = array();$sumIr2 = array();$sumIr3 = array();$sumIr4 = array();
            $sumK1 = array();$sumK2 = array();$sumK3 = array();$sumK4 = array();
            $sumDat1 = array();$sumDat2 = array();$sumDat3 = array();$sumDat4 = array();
            $sumIvt1 = array();$sumIvt2 = array();$sumIvt3 = array();$sumIvt4 = array();
            $sumIrt1 = array();$sumIrt2 = array();$sumIrt3 = array();$sumIrt4 = array();
            $sumKt1 = array();$sumKt2 = array();$sumKt3 = array();$sumKt4 = array();
            foreach ($targetnotsum[$khoa]['name'] as $value) {
                $infosum[0]=$value;
                $listcontact = array();
                foreach ($infosum as $k => $v) {
                    $listcontact[$k] = $v['contacts'];
                }
                foreach ($listcontact as $k=>$subArray) {
                    foreach ($subArray as $id=>$getContact) {
                        $sumArray[] = $getContact->w;
                        // return $sumArray;

                        if($getContact->w == $sumArray[0]) {
                            $sum1t[] = $getContact->t; 
                            $sum1c[] = $getContact->c;
                            $sum1dd[] = $getContact->sumd;
                            $sum1dc[] = $getContact->sumc;
                            $amount1[] = $getContact->amount;
                            $sumDa1[] = isset($getContact->cdahlia) ? $getContact->cdahlia : 0;
                            $sumIv1[] = isset($getContact->civy) ? $getContact->civy : 0;
                            $sumIr1[] = isset($getContact->ciris) ? $getContact->ciris : 0;
                            $sumK1[] = isset($getContact->ckhac) ? $getContact->ckhac : 0;
                            $sumDat1[] = isset($getContact->tdahlia) ? $getContact->tdahlia : 0;
                            $sumIvt1[] = isset($getContact->tivy) ? $getContact->tivy : 0;
                            $sumIrt1[] = isset($getContact->tiris) ? $getContact->tiris : 0;
                            $sumKt1[] = isset($getContact->tkhac) ? $getContact->tkhac : 0;
                        }
                        if ($sumArray[0] > 52 ) {
                            $sumArray[0] = 0;
                        }
                        if($getContact->w == $sumArray[0]+1) {
                            $sum2t[] = $getContact->t; 
                            $sum2c[] = $getContact->c;
                            $sum2dd[] = $getContact->sumd;
                            $sum2dc[] = $getContact->sumc;
                            $amount2[] = $getContact->amount;
                            $sumDa2[] = isset($getContact->cdahlia) ? $getContact->cdahlia : 0;
                            $sumIv2[] = isset($getContact->civy) ? $getContact->civy : 0;
                            $sumIr2[] = isset($getContact->ciris) ? $getContact->ciris : 0;
                            $sumK2[] = isset($getContact->ckhac) ? $getContact->ckhac : 0;
                            $sumDat2[] = isset($getContact->tdahlia) ? $getContact->tdahlia : 0;
                            $sumIvt2[] = isset($getContact->tivy) ? $getContact->tivy : 0;
                            $sumIrt2[] = isset($getContact->tiris) ? $getContact->tiris : 0;
                            $sumKt2[] = isset($getContact->tkhac) ? $getContact->tkhac : 0;
                        }
                        if($getContact->w == $sumArray[0]+2) {
                            $sum3t[] = $getContact->t; 
                            $sum3c[] = $getContact->c;
                            $sum3dd[] = $getContact->sumd; 
                            $sum3dc[] = $getContact->sumc;
                            $amount3[] = $getContact->amount;
                            $sumDa3[] = isset($getContact->cdahlia) ? $getContact->cdahlia : 0;
                            $sumIv3[] = isset($getContact->civy) ? $getContact->civy : 0;
                            $sumIr3[] = isset($getContact->ciris) ? $getContact->ciris : 0;
                            $sumK3[] = isset($getContact->ckhac) ? $getContact->ckhac : 0;
                            $sumDat3[] = isset($getContact->tdahlia) ? $getContact->tdahlia : 0;
                            $sumIvt3[] = isset($getContact->tivy) ? $getContact->tivy : 0;
                            $sumIrt3[] = isset($getContact->tiris) ? $getContact->tiris : 0;
                            $sumKt3[] = isset($getContact->tkhac) ? $getContact->tkhac : 0;
                        }
                        if($getContact->w == $sumArray[0]+3) {
                            $sum4t[] = $getContact->t; 
                            $sum4c[] = $getContact->c; 
                            $sum4dc[] = $getContact->sumc;
                            $sum4dd[] = $getContact->sumd;
                            $amount4[] = $getContact->amount;
                            $sumDa4[] = isset($getContact->cdahlia) ? $getContact->cdahlia : 0;
                            $sumIv4[] = isset($getContact->civy) ? $getContact->civy : 0;
                            $sumIr4[] = isset($getContact->ciris) ? $getContact->ciris : 0;
                            $sumK4[] = isset($getContact->ckhac) ? $getContact->ckhac : 0;
                            $sumDat4[] = isset($getContact->tdahlia) ? $getContact->tdahlia : 0;
                            $sumIvt4[] = isset($getContact->tivy) ? $getContact->tivy : 0;
                            $sumIrt4[] = isset($getContact->tiris) ? $getContact->tiris : 0;
                            $sumKt4[] = isset($getContact->tkhac) ? $getContact->tkhac : 0;
                        }
                    }
                    $t1 = array_sum($sum1t);
                    $t2 = array_sum($sum2t);
                    $t3 = array_sum($sum3t);
                    $t4 = array_sum($sum4t);
                    $sumt = $t1 + $t2 + $t3 + $t4;
                    $c1 = array_sum($sum1c);
                    $c2 = array_sum($sum2c);
                    $c3 = array_sum($sum3c);
                    $c4 = array_sum($sum4c);
                    $sumc = $c1 + $c2 + $c3 + $c4; 
                    $sumdc1 = array_sum($sum1dc);
                    $sumdc2 = array_sum($sum2dc);
                    $sumdc3 = array_sum($sum3dc);
                    $sumdc4 = array_sum($sum4dc);
                    $sumdc = $sumdc1 + $sumdc2 + $sumdc3 + $sumdc4;
                    $sumdd1 = array_sum($sum1dd);
                    $sumdd2 = array_sum($sum2dd);
                    $sumdd3 = array_sum($sum3dd);
                    $sumdd4 = array_sum($sum4dd);
                    $sumdd = $sumdd1 + $sumdd2 + $sumdd3 + $sumdd4;
                    $sumamount4 = array_sum($amount4);
                    $sumamount1 = array_sum($amount1);
                    $sumamount2 = array_sum($amount2);
                    $sumamount3 = array_sum($amount3);
                    $sumDah1 = array_sum($sumDa1);$sumDah2 = array_sum($sumDa2);$sumDah3 = array_sum($sumDa3);$sumDah4 = array_sum($sumDa4);
                    $cdahlia = $sumDah1 + $sumDah2 + $sumDah3 + $sumDah4; 
                    $sumivy1 = array_sum($sumIv1);$sumivy2 = array_sum($sumIv2);$sumivy3 = array_sum($sumIv3);$sumivy4 = array_sum($sumIv4);
                    $civy = $sumivy1 + $sumivy2 + $sumivy3 + $sumivy4; 
                    $sumiris1 = array_sum($sumIr1);$sumiris2 = array_sum($sumIr2);$sumiris3 = array_sum($sumIr3);$sumiris4 = array_sum($sumIr4);
                    $ciris = $sumiris1 + $sumiris2 + $sumiris3 + $sumiris4; 
                    $sumkh1 = array_sum($sumK1);$sumkh2 = array_sum($sumK2);$sumkh3 = array_sum($sumK3);$sumkh4 = array_sum($sumK4);
                    $ckhac = $sumkh1 + $sumkh2 + $sumkh3 + $sumkh4;

                    $sumDaht1 = array_sum($sumDat1);$sumDaht2 = array_sum($sumDat2);$sumDaht3 = array_sum($sumDat3);$sumDaht4 = array_sum($sumDat4);
                    $tdahlia = $sumDaht1 + $sumDaht2 + $sumDaht3 + $sumDaht4; 

                    $sumivyt1 = array_sum($sumIvt1);$sumivyt2 = array_sum($sumIvt2);$sumivyt3 = array_sum($sumIvt3);$sumivyt4 = array_sum($sumIvt4);
                    $tivy = $sumivyt1 + $sumivyt2 + $sumivyt3 + $sumivyt4; 

                    $sumirist1 = array_sum($sumIrt1);$sumirist2 = array_sum($sumIrt2);$sumirist3 = array_sum($sumIrt3);$sumirist4 = array_sum($sumIrt4);
                    $tiris = $sumirist1 + $sumirist2 + $sumirist3 + $sumirist4; 

                    $sumkht1 = array_sum($sumKt1);$sumkht2 = array_sum($sumKt2);$sumkht3 = array_sum($sumKt3);$sumkht4 = array_sum($sumKt4);
                    $tkhac = $sumkht1 + $sumkht2 + $sumkht3 + $sumkht4; 
                }
            }
            $tsr1 = $this->targetAllSR($idCostcenter,$weeks[0],$year,4);
            $tsr2 = $this->targetAllSR($idCostcenter,$weeks[1],$year,4);
            $tsr3 = $this->targetAllSR($idCostcenter,$weeks[2],$year,4);
            $tsr4 = $this->targetAllSR($idCostcenter,$weeks[3],$year,4);
            $sumtsr = $tsr1 + $tsr2 + $tsr3 + $tsr4 ;
            $target[] = [   
                'costcenter' => Costcenter::find($idCostcenter)->name,
                'name' => $list,
                'hienthi'=>$hienthi,
                'checksales' => $this->checksales(\Auth::user()->id),
                't1' => $t1,'t2' => $t2,'t3' => $t3,'t4' => $t4,
                'c1' => $c1,'c2' => $c2,'c3' => $c3,'c4' => $c4, 
                'tsr1' => $tsr1,'tsr4' => $tsr4,'tsr3' => $tsr3,'tsr2' => $tsr2,'sumtsr' => $sumtsr,
                'sumc' => $sumc , 'sumt' => $sumt , 
                'sumdc' => $sumdc, 'sumdd' => $sumdd, 
                'sumdc1' => $sumdc1, 'sumdd1' => $sumdd1,
                'sumdc2' => $sumdc2, 'sumdd2' => $sumdd2,
                'sumdc3' => $sumdc3, 'sumdd3' => $sumdd3,
                'sumdc4' => $sumdc4, 'sumdd4' => $sumdd4,
                'sumamount1'=>$sumamount1,'sumamount4'=>$sumamount4,'sumamount2'=>$sumamount2,'sumamount3'=>$sumamount3,
                'cdahlia' =>$cdahlia,'ciris'=>$ciris,'civy'=>$civy,'ckhac'=>$ckhac,
                'cdahlia1' =>$sumDah1,'ciris1'=>$sumiris1,'civy1'=>$sumivy1,'ckhac1'=>$sumkh1,
                'cdahlia2' =>$sumDah2,'ciris2'=>$sumiris2,'civy2'=>$sumivy2,'ckhac2'=>$sumkh2,
                'cdahlia3' =>$sumDah3,'ciris3'=>$sumiris3,'civy3'=>$sumivy3,'ckhac3'=>$sumkh3,
                'cdahlia4' =>$sumDah4,'ciris4'=>$sumiris4,'civy4'=>$sumivy4,'ckhac4'=>$sumkh4,

                'tdahlia' =>$tdahlia,'tiris'=>$tiris,'tivy'=>$tivy,'tkhac'=>$tkhac,
                'tdahlia1' =>$sumDaht1,'tiris1'=>$sumirist1,'tivy1'=>$sumivyt1,'tkhac1'=>$sumkht1,
                'tdahlia2' =>$sumDaht2,'tiris2'=>$sumirist2,'tivy2'=>$sumivyt2,'tkhac2'=>$sumkht2,
                'tdahlia3' =>$sumDaht3,'tiris3'=>$sumirist3,'tivy3'=>$sumivyt3,'tkhac3'=>$sumkht3,
                'tdahlia4' =>$sumDaht4,'tiris4'=>$sumirist4,'tivy4'=>$sumivyt4,'tkhac4'=>$sumkht4,
            ];
        }
            // return $target;

        if(count($r_costcenter) > 2){
            foreach ($target as $tong) {
                $tongc[] = $tong['sumc'];$tongt[] = $tong['sumt']; 
                $tongt1[] = $tong['t1'];$tongt2[] = $tong['t2']; $tongt3[] = $tong['t3']; $tongt4[] = $tong['t4'];    
                $tongc1[] = $tong['c1'];$tongc2[] = $tong['c2']; $tongc3[] = $tong['c3']; $tongc4[] = $tong['c4'];

                $tongsumdc[] = $tong['sumdc']; $tongsumdd[] = $tong['sumdd'];
                $tongsumdc1[] = $tong['sumdc1']; $tongsumdd1[] = $tong['sumdd1'];
                $tongsumdc2[] = $tong['sumdc2']; $tongsumdd2[] = $tong['sumdd2'];
                $tongsumdc3[] = $tong['sumdc3']; $tongsumdd3[] = $tong['sumdd3'];
                $tongsumdc4[] = $tong['sumdc4']; $tongsumdd4[] = $tong['sumdd4'];
                $tongsumamount1[]= $tong['sumamount1']; $tongsumamount4[] = $tong['sumamount4'];$tongsumamount2[]= $tong['sumamount2']; $tongsumamount3[] = $tong['sumamount3'];
                $tongtsr1[] = $tong['tsr1'];$tongtsr4[] = $tong['tsr4'];$tongtsr3[] = $tong['tsr3'];$tongtsr2[] = $tong['tsr2'];$tongsumtsr[] = $tong['sumtsr'];

                $tongsumtir[] = $tong['tiris'];$tongsumtir1[] = $tong['tiris1'];$tongsumtir2[] = $tong['tiris2'];$tongsumtir3[] = $tong['tiris3'];$tongsumtir4[] = $tong['tiris4'];
                $tongsumcir[] = $tong['ciris'];$tongsumcir1[] = $tong['ciris1'];$tongsumcir2[] = $tong['ciris2'];$tongsumcir3[] = $tong['ciris3'];$tongsumcir4[] = $tong['ciris4'];

                $tongsumtk[] = $tong['tkhac'];$tongsumtk1[] = $tong['tkhac1'];$tongsumtk2[] = $tong['tkhac2'];$tongsumtk3[] = $tong['tkhac3'];$tongsumtk4[] = $tong['tkhac1'];
                $tongsumck[] = $tong['ckhac'];$tongsumck1[] = $tong['ckhac1'];$tongsumck2[] = $tong['ckhac2'];$tongsumck3[] = $tong['ckhac3'];$tongsumck4[] = $tong['ckhac4'];

                $tongsumtda[] = $tong['tdahlia'];$tongsumtda1[] = $tong['tdahlia1'];$tongsumtda2[] = $tong['tdahlia2'];$tongsumtda3[] = $tong['tdahlia3'];$tongsumtda4[] = $tong['tdahlia4'];
                $tongsumcda[] = $tong['sumdc1'];$tongsumcda1[] = $tong['cdahlia1'];$tongsumcda2[] = $tong['cdahlia2'];$tongsumcda3[] = $tong['cdahlia3'];$tongsumcda4[] = $tong['cdahlia4'];
                
                $tongsumtiv[] = $tong['tivy'];$tongsumtiv1[] = $tong['tivy1'];$tongsumtiv2[] = $tong['tivy2'];$tongsumtiv3[] = $tong['tivy3'];$tongsumtiv4[] = $tong['tivy4'];
                $tongsumciv[] = $tong['civy'];$tongsumciv1[] = $tong['civy1'];$tongsumciv2[] = $tong['civy2'];$tongsumciv3[] = $tong['civy3'];$tongsumciv4[] = $tong['civy4'];
            } 
               
            if ($request->all == 1) {
                $costcenter_name = 'Tất Cả';
            }else{
                $costcenter_name = Costcenter::whereIn('id',$r_costcenter)->first()['warehouse'];
            }

            $sumSelect = [ 
                'costcenter' => $costcenter_name,
                'checksales' =>  $this->checksales(\Auth::user()->id),
                'sumc' => array_sum($tongc), 'sumt' => array_sum($tongt),
                't1' => array_sum($tongt1), 't2' => array_sum($tongt2),'t3' => array_sum($tongt3),'t4' => array_sum($tongt4),
                'c1' => array_sum($tongc1), 'c2' => array_sum($tongc2),'c3' => array_sum($tongc3),'c4' => array_sum($tongc4),

                'tsr1' => array_sum($tongtsr1),'tsr4' => array_sum($tongtsr4),'tsr3' => array_sum($tongtsr3),'tsr2' => array_sum($tongtsr2),'sumtsr' => array_sum($tongsumtsr),
                'sumamount1'=>array_sum($tongsumamount1),'sumamount4'=>array_sum($tongsumamount4),'sumamount2'=>array_sum($tongsumamount2),'sumamount3'=>array_sum($tongsumamount3),
                'sumdc' => array_sum($tongsumdc), 'sumdd'=> array_sum($tongsumdd), 
                'sumdc1' => array_sum($tongsumdc1), 'sumdd1' => array_sum($tongsumdd1) ,
                'sumdc2' => array_sum($tongsumdc2),  'sumdd2' =>array_sum($tongsumdd2),
                'sumdc3' => array_sum($tongsumdc3), 'sumdd3' =>  array_sum($tongsumdd3),
                'sumdc4' => array_sum($tongsumdc4), 'sumdd4' => array_sum($tongsumdd4) ,

                'cdahlia' =>$cdahlia,'ciris'=>$ciris,'civy'=>$civy,'ckhac'=>$ckhac,
                'cdahlia1' =>$sumDah1,'ciris1'=>$sumiris1,'civy1'=>$sumivy1,'ckhac1'=>$sumkh1,
                'cdahlia2' =>$sumDah2,'ciris2'=>$sumiris2,'civy2'=>$sumivy2,'ckhac2'=>$sumkh2,
                'cdahlia3' =>$sumDah3,'ciris3'=>$sumiris3,'civy3'=>$sumivy3,'ckhac3'=>$sumkh3,
                'cdahlia4' =>$sumDah4,'ciris4'=>$sumiris4,'civy4'=>$sumivy4,'ckhac4'=>$sumkh4,

                'tdahlia' =>$tdahlia,'tiris'=>$tiris,'tivy'=>$tivy,'tkhac'=>$tkhac,
                'tdahlia1' =>$sumDaht1,'tiris1'=>$sumirist1,'tivy1'=>$sumivyt1,'tkhac1'=>$sumkht1,
                'tdahlia2' =>$sumDaht2,'tiris2'=>$sumirist2,'tivy2'=>$sumivyt2,'tkhac2'=>$sumkht2,
                'tdahlia3' =>$sumDaht3,'tiris3'=>$sumirist3,'tivy3'=>$sumivyt3,'tkhac3'=>$sumkht3,
                'tdahlia4' =>$sumDaht4,'tiris4'=>$sumirist4,'tivy4'=>$sumivyt4,'tkhac4'=>$sumkht4,
                'civy' => array_sum($tongsumciv),'civy1'=> array_sum($tongsumciv1),'civy2'=> array_sum($tongsumciv2),'civy3'=> array_sum($tongsumciv3),'civy4'=> array_sum($tongsumciv4), 
                'tivy' => array_sum($tongsumtiv),'tivy1'=> array_sum($tongsumtiv1),'tivy2'=> array_sum($tongsumtiv2),'tivy3'=> array_sum($tongsumtiv3),'tivy4'=> array_sum($tongsumtiv4), 

                'cdahlia' => array_sum($tongsumcda),'cdahlia1'=> array_sum($tongsumcda1),'cdahlia2'=> array_sum($tongsumcda2),'cdahlia3'=> array_sum($tongsumcda3),'cdahlia4'=> array_sum($tongsumcda4), 
                'tdahlia' => array_sum($tongsumtda),'tdahlia1'=> array_sum($tongsumtda1),'tdahlia2'=> array_sum($tongsumtda2),'tdahlia3'=> array_sum($tongsumtda3),'tdahlia4'=> array_sum($tongsumtda4), 

                'ciris' => array_sum($tongsumcir),'ciris1'=> array_sum($tongsumcir1),'ciris2'=> array_sum($tongsumcir2),'ciris3'=> array_sum($tongsumcir3),'ciris4'=> array_sum($tongsumcir4), 
                'tiris' => array_sum($tongsumtir),'tiris1'=> array_sum($tongsumtir1),'tiris2'=> array_sum($tongsumtir2),'tiris3'=> array_sum($tongsumtir3),'tiris4'=> array_sum($tongsumtir4), 

                'ckhac' => array_sum($tongsumck),'ckhac1'=> array_sum($tongsumck1),'ckhac2'=> array_sum($tongsumck2),'ckhac3'=> array_sum($tongsumck3),'ckhac4'=> array_sum($tongsumck4), 
                'tkhac' => array_sum($tongsumtk),'tkhac1'=> array_sum($tongsumtk1),'tkhac2'=> array_sum($tongsumtk2),'tkhac3'=> array_sum($tongsumtk3),'tkhac4'=> array_sum($tongsumtk4),
            ];

            $target = array_merge([0=>$sumSelect],$target);
        }
        return $target;
    }
    protected function targetByWeekAmount($items, $weeks,$year, $costcenterid)
    {
        $dto = new DateTime();
        $name_costcenter = Costcenter::where('id',$costcenterid)->first();
        $keyId[] = $items->map(function($item,$key){
            return $item->id;
        });
        $targetNew[] = $items->map(function($item,$key) use ($weeks,$dto,$year,$name_costcenter){
            return $weeks->map(function($week) use ($item,$dto,$year,$key,$name_costcenter){
                if($week > 52){
                    $year = $year - 1;
                }else{
                    $year = $year;
                }
                $dto->setISODate($year, $week);
                $ret['week_start'] = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $ret['week_end'] = $dto->format('Y-m-d');
                $targetUser = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$item->id)->whereDate('close',$ret['week_end'])->first('amount_number');
                if($targetUser == null) {
                    $target = 0000000;
                }else{
                    $target = json_decode($targetUser)->amount_number;
                }
                $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);
                $countor = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereBetween('start_date',[$ret['week_start'], $ret['week_end']])->count();
               
                $c = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereBetween('start_date',[$ret['week_start'], $ret['week_end']])->pluck('amount');

                $targetsp = SalesTarget::where('targetable_id',$item->id)->where('targetable_type','App\User')->whereDate('close',$ret['week_end'])->first();
                if (empty($targetsp)) {
                    $targetsp['dahlia'] = 1;
                    $targetsp['ivy'] = 1;
                    $targetsp['iris'] = 1;
                    $targetsp['khac'] = 1;
                }

                $dal = 0;$iri = 0;$ivy = 0;$khac = 0;$idP = array();
                $totalOrder = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereBetween('start_date',[$ret['week_start'], $ret['week_end']])->pluck('id');

                foreach ($totalOrder as $key => $value) {
                    $getId = \DB::table('order_lines')->where('order_id',$value)->first();
                    if(!empty($getId)){
                        $idP[] = $getId->product_id;
                    }
                }
                if (count($idP) > 0) {
                    foreach ($idP as $v) {
                        $checkd = \DB::table('products')->where('id',$v)->where('groups',1)->get();
                        if($checkd->isNotEmpty()){
                            $dal += 1;
                        }
                        $checki = \DB::table('products')->where('id',$v)->where('groups',2)->get();
                        if($checki->isNotEmpty()){
                            $iri += 1;
                        }
                        $checkiv = \DB::table('products')->where('id',$v)->where('groups',29)->get();
                        if($checkiv->isNotEmpty()){
                            $ivy += 1;
                        }
                    }
                }

                $khac = count($idP) - $dal - $iri - $ivy;

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'costcenter' => $name_costcenter->code, 
                    'w' => $week,
                    't' => $target,
                    'c'=> array_sum(json_decode($c)),
                    'sumd' => 0,
                    'sumc' => 0,
                    'amount' => $countor,
                    'tdahlia' => $targetsp['dahlia'],
                    'tivy' => $targetsp['ivy'],
                    'tiris' => $targetsp['iris'],
                    'tkhac' => $targetsp['khac'],
                    'cdahlia' => $dal,
                    'civy' => $ivy,
                    'ciris' => $iri,
                    'ckhac' => $khac,
                ];
            });
        });
        return array_combine(json_decode($keyId[0]) ,json_decode($targetNew[0]));
    }
    protected function format_amount($value) {
        $amount = array_sum(json_decode($value));
        $amountformat =  number_format($amount);
        $amountformat = ($amountformat == 0) ? '0000000' : $amountformat;
        $amount = substr($amountformat , -100, -6);
        return $amount;
    }
    protected function format_amount1($value) {
        $amountformat =  number_format($value);
        $amountformat = ($amountformat == 0) ? '0000000' : $amountformat;
        $amount = substr($amountformat , -100, -6);
        return $amount;
    }
    protected function targetByWeekOrder($items, $weeks,$year, $costcenterid)
    {
        $dto = new DateTime();
        $name_costcenter = Costcenter::where('id',$costcenterid)->first();
        $keyId[] = $items->map(function($item,$key){
            return $item->id;
        });
        $targetNew[] = $items->map(function($item,$key) use ($weeks,$dto,$year,$name_costcenter){
            return $weeks->map(function($week) use ($item,$dto,$year,$key,$name_costcenter){
                if($week > 52){
                    $year = $year - 1;
                }else{
                    $year = $year;
                }
                $dto->setISODate($year, $week);
                $ret['week_start'] = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $ret['week_end'] = $dto->format('Y-m-d');
                $targetUser = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$item->id)->whereDate('close',$ret['week_end'])->first('order_number');
                if($targetUser == null) {
                    $target = 0;
                }else{
                    $target = json_decode($targetUser)->order_number;
                }
                $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);
                $monday = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereDate('start_date',$date[0])->count();
                $tuesday = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereDate('start_date',$date[1])->count();
                $wednesday = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereDate('start_date',$date[2])->count();
                $thursday = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereDate('start_date',$date[3])->count();
                $friday = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereDate('start_date',$date[4])->count();
                $saturday = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereDate('start_date',$date[5])->count();
                $sunday = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereDate('start_date',$date[6])->count();
                $arramount = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereBetween('start_date',[$ret['week_start'], $ret['week_end']])->pluck('amount');
                $amount = array_sum(json_decode($arramount));
                $amountformat =  number_format($amount);
                $amountformat = ($amountformat == 0) ? '0000000' : $amountformat;
                $amount = substr($amountformat , -100, -6);
                $sumd = $monday + $tuesday + $wednesday + $thursday;
                $sumc = $friday + $saturday + $sunday;

                $targetsp = SalesTarget::where('targetable_id',$item->id)->where('targetable_type','App\User')->whereDate('close',$ret['week_end'])->first();
                if (empty($targetsp)) {
                    $targetsp['dahlia'] = 1;
                    $targetsp['ivy'] = 1;
                    $targetsp['iris'] = 1;
                    $targetsp['khac'] = 1;
                }

                $dal = 0;$iri = 0;$ivy = 0;$khac = 0;$idP = array();
                $totalOrder = Order::where('user_id',$item->id)->where('costcenter',$name_costcenter->code)->whereBetween('start_date',[$ret['week_start'], $ret['week_end']])->pluck('id');

                foreach ($totalOrder as $key => $value) {
                    $getId = \DB::table('order_lines')->where('order_id',$value)->first();
                    if(!empty($getId)){
                        $idP[] = $getId->product_id;
                    }
                }
                if (count($idP) > 0) {
                    foreach ($idP as $v) {
                        $checkd = \DB::table('products')->where('id',$v)->where('groups',1)->get();
                        if($checkd->isNotEmpty()){
                            $dal += 1;
                        }
                        $checki = \DB::table('products')->where('id',$v)->where('groups',2)->get();
                        if($checki->isNotEmpty()){
                            $iri += 1;
                        }
                        $checkiv = \DB::table('products')->where('id',$v)->where('groups',29)->get();
                        if($checkiv->isNotEmpty()){
                            $ivy += 1;
                        }
                    }
                }

                $khac = count($idP) - $dal - $iri - $ivy;
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'costcenter' => $name_costcenter->code, 
                    'w' => $week,
                    't' => $target,
                    'c'=> $sumd + $sumc ,
                    'monday' => ($monday == 0) ? '.' : $monday,
                    'tuesday' => ($tuesday == 0) ? '.' : $tuesday,
                    'wednesday' => ($wednesday == 0) ? '.' : $wednesday,
                    'thursday' => ($thursday == 0) ? '.' : $thursday,
                    'friday' => ($friday == 0) ? '.' : $friday,
                    'saturday' => ($saturday == 0) ? '.' : $saturday,
                    'sunday' => ($sunday == 0) ? '.' : $sunday,
                    'sumd' => $sumd,
                    'sumc' => $sumc,
                    'amount' => $amount,
                    'tdahlia' => $targetsp['dahlia'],
                    'tivy' => $targetsp['ivy'],
                    'tiris' => $targetsp['iris'],
                    'tkhac' => $targetsp['khac'],
                    'cdahlia' => $dal,
                    'civy' => $ivy,
                    'ciris' => $iri,
                    'ckhac' => $khac,
                ];
            });
        });
        return array_combine(json_decode($keyId[0]) ,json_decode($targetNew[0]));
    }
 
    public function targetSRlead(Request $request)
    {
        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        if($start_week > 52){
            if($end_week == 3){
                $weeks = collect([$start_week, 1,2,3]);
            }else{
                $weeks = collect([$start_week, 54,1,2]);
            }
            
        }else{
            $weeks = collect(range($start_week, $end_week));
        }

        $dangnhap = \Auth::user()->id;
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $ss1 = strtotime($dates[0]);

        if (strtotime($dt->toDateString()) <  $ss1) { 
            if ($dangnhap != 9003 && $dangnhap != 9074) {
                $role_watch = \DB::table('roles_target')->whereDate('d',$dates[0])->where('user_id',$dangnhap)->get();
                if ($role_watch->isEmpty()) {
                    return response()->json([
                        'message' => 'Bạn không có quyền xem'
                    ],400);
                }
            }
        }

        $contacts = Lead::filter($request->all())->get();
        $contacts = $contacts->groupBy(function($contact){
           return $contact->user_id;
        });
        $contacts = $contacts->map(function($contactsList,$key){
            return $contactsList->countBy(function($contact){
                    $date = new Carbon($contact->start_date);
                    return $date->weekOfYear;
                });
        });
        
        $year = date('Y',strtotime($dates[1]));
        $contacts = $this->targetByWeekLeads($contacts, $weeks, $year);
     
        $sales = User::all()->filter(function($user){
            return $user->hasRole('sales');
        });
        $noContact = $weeks->map(function($week){
            return [ 'w' => $week, 't' => 1, 'c' => 0, 'monday' => 0,'tuesday' => 0,'wednesday' => 0,'thursday' => 0,'friday' => 0,'saturday' => 0,'sunday' => 0, 'sumc' => 0, 'sumd' => 0 ];
        });
        $costcenter = Costcenter::whereDate('closed','>',$dates[0])->select('id','name','warehouse')->get();
        foreach ($costcenter as $k => $v) {
            $find = $this->findUser($v->id,$sales->pluck('id'),$dates[0]);
            foreach ($find as $key => $value) {
                $infouser = isset($contacts[json_decode($find)[$key]->id]) ? $contacts[json_decode($find)[$key]->id] : $noContact;
                
                $merge[]= array(
                    'costcenter' => $v->name,
                        'name' =>  [
                            'warehouse' => $v->warehouse,
                            'name' => json_decode($find)[$key]->name ,
                            'contacts' => $infouser,
                            't' => $infouser->sum('t'),
                            'c' => $infouser->sum('c')
                        ]
                    );
            }
        }
        foreach ($merge as $key1 => $value1) {
            $a[$value1['costcenter']][] = $value1['name'];
        }
        foreach ($a as $k1 => $v1) {
            $c[0] = $v1;
            $i = array();
            foreach ($c as $key22 => $value22) {
                foreach ($value22 as $key23 => $value23) {
                    $i[$key23] = $value23['contacts'];
                }
            }
            $sumArray = array();$sum4dd = array();$sum4dc = array();
            $sum1c = array();$sum2t = array();$sum2c = array();$sum3c = array();$sum4c = array();
            $sum1dd = array();$sum1dc = array();$sum2dd = array();$sum2dc = array();$sum3dd = array();$sum3dc = array();
            
            $idCostcenter = Costcenter::where('name',$k1)->first('id');
            foreach ($i as $k=>$subArray) {
                foreach ($subArray as $id=>$value12) {
                    $sumArray[] = $value12['w'];
                    if($value12['w'] == $sumArray[0]) {
                        $sum1c[] = $value12['c'];
                        $sum1dd[] = $value12['sumd'];
                        $sum1dc[] = $value12['sumc'];
                    }
                    if($value12['w'] == $sumArray[0]+1) {
                        $sum2c[] = $value12['c'];
                        $sum2dd[] = $value12['sumd'];
                        $sum2dc[] = $value12['sumc'];
                    }
                    if($value12['w'] == $sumArray[0]+2) {
                        $sum3c[] = $value12['c'];
                        $sum3dd[] = $value12['sumd']; 
                        $sum3dc[] = $value12['sumc'];
                    }
                    if($value12['w'] == $sumArray[0]+3) {
                        $sum4c[] = $value12['c']; 
                        $sum4dc[] = $value12['sumc'];
                        $sum4dd[] = $value12['sumd'];
                    }
                }
                $t1 = $this->targetAllSR($idCostcenter->id,$weeks[0],$year,2);
                $t2 = $this->targetAllSR($idCostcenter->id,$weeks[1],$year,2);
                $t3 = $this->targetAllSR($idCostcenter->id,$weeks[2],$year,2);
                $t4 = $this->targetAllSR($idCostcenter->id,$weeks[3],$year,2);
                $sumt = $t1 + $t2 + $t3 + $t4;
                $c1 = array_sum($sum1c);
                $c2 = array_sum($sum2c);
                $c3 = array_sum($sum3c);
                $c4 = array_sum($sum4c);
                $sumc = $c1 + $c2 + $c3 + $c4; 
                $sumdc1 = array_sum($sum1dc);
                $sumdc2 = array_sum($sum2dc);
                $sumdc3 = array_sum($sum3dc);
                $sumdc4 = array_sum($sum4dc);
                $sumdc = $sumdc1 + $sumdc2 + $sumdc3 + $sumdc4;
                $sumdd1 = array_sum($sum1dd);
                $sumdd2 = array_sum($sum2dd);
                $sumdd3 = array_sum($sum3dd);
                $sumdd4 = array_sum($sum4dd);
                $sumdd = $sumdd1 + $sumdd2 + $sumdd3 + $sumdd4;
            }
            
            $warehouse = Costcenter::where('name',$k1)->first('warehouse');
            $target[] = [   'costcenter'=>$k1,
                            'name'=>$v1,
                            'warehouse' => $warehouse->warehouse,
                            't1' => $t1,'t2' => $t2,'t3' => $t3,'t4' => $t4,
                            'c1' => $c1,'c2' => $c2,'c3' => $c3,'c4' => $c4, 
                            'sumc' => $sumc , 'sumt' => $sumt, 
                            'sumdc' => $sumdc, 'sumdd' => $sumdd, 
                            'sumdc1' => $sumdc1, 'sumdd1' => $sumdd1,
                            'sumdc2' => $sumdc2, 'sumdd2' => $sumdd2,
                            'sumdc3' => $sumdc3, 'sumdd3' => $sumdd3,
                            'sumdc4' => $sumdc4, 'sumdd4' => $sumdd4,
                        ];
            foreach ($target as $khoa => $item) {
                $showroom[$item['warehouse']][$khoa] = [
                    'costcenter' => $item['costcenter'],
                    'warehouse' => $item['warehouse'],
                    't1' => $item['t1'] ,'t2' => $item['t2'],'t3' => $item['t3'],'t4' => $item['t4'],
                    'c1' => $item['c1'],'c2' => $item['c2'],'c3' => $item['c3'],'c4' => $item['c4'], 
                    'sumc' => $item['sumc'] , 'sumt' => $item['sumt'], 
                    'sumdc' => $item['sumdc'], 'sumdd' => $item['sumdd'] ,
                    'sumdc1' => $item['sumdc1'], 'sumdd1' => $item['sumdd1'],
                    'sumdc2' => $item['sumdc2'], 'sumdd2' => $item['sumdd2'],
                    'sumdc3' => $item['sumdc3'], 'sumdd3' => $item['sumdd3'],
                    'sumdc4' => $item['sumdc4'], 'sumdd4' => $item['sumdd4'],
                ];
            }
            foreach ($showroom as $warehousename => $infoshowroom) {
                $laytongt1 = array();$laytongc1 = array();
                $laytongt2 = array();$laytongc2 = array();
                $laytongt3 = array();$laytongc3 = array();
                $laytongt4 = array();$laytongc4 = array();
                $laytongdt1 = array();$laytongdc1 = array();
                $laytongdt2 = array();$laytongdc2 = array();
                $laytongdt3 = array();$laytongdc3 = array();
                $laytongdt4 = array();$laytongdc4 = array();
                $laytongpt = array();$laytongpc = array();
                foreach ($infoshowroom as $getinfo) {
                    $laytongt1[$getinfo['warehouse']][] = $getinfo['t1'];$laytongc1[$getinfo['warehouse']][] = $getinfo['c1'];
                    $laytongt2[$getinfo['warehouse']][] = $getinfo['t2'];$laytongc2[$getinfo['warehouse']][] = $getinfo['c2'];
                    $laytongt3[$getinfo['warehouse']][] = $getinfo['t3'];$laytongc3[$getinfo['warehouse']][] = $getinfo['c3'];
                    $laytongt4[$getinfo['warehouse']][] = $getinfo['t4'];$laytongc4[$getinfo['warehouse']][] = $getinfo['c4'];
                    $laytongdt1[$getinfo['warehouse']][] = $getinfo['sumdd1'];$laytongdc1[$getinfo['warehouse']][] = $getinfo['sumdc1'];
                    $laytongdt2[$getinfo['warehouse']][] = $getinfo['sumdd2'];$laytongdc2[$getinfo['warehouse']][] = $getinfo['sumdc2'];
                    $laytongdt3[$getinfo['warehouse']][] = $getinfo['sumdd3'];$laytongdc3[$getinfo['warehouse']][] = $getinfo['sumdc3'];
                    $laytongdt4[$getinfo['warehouse']][] = $getinfo['sumdd4'];$laytongdc4[$getinfo['warehouse']][] = $getinfo['sumdc4'];
                    $laytongpt[$getinfo['warehouse']][] = $getinfo['sumt'];$laytongpc[$getinfo['warehouse']][] = $getinfo['sumc'];
                }
                $targetcost[$warehousename] = ['warehouse' => $warehousename , 'costcenter' => array_values($infoshowroom), 
                    't1' => array_sum($laytongt1[$warehousename]),'c1' => array_sum($laytongc1[$warehousename]),
                    't2' => array_sum($laytongt2[$warehousename]),'c2' => array_sum($laytongc2[$warehousename]),
                    't3' => array_sum($laytongt3[$warehousename]),'c3' => array_sum($laytongc3[$warehousename]),
                    't4' => array_sum($laytongt4[$warehousename]),'c4' => array_sum($laytongc4[$warehousename]),
                    'sumdc1' => array_sum($laytongdc1[$warehousename]), 'sumdd1' => array_sum($laytongdt1[$warehousename]),
                    'sumdc2' => array_sum($laytongdc2[$warehousename]), 'sumdd2' => array_sum($laytongdt2[$warehousename]),
                    'sumdc3' => array_sum($laytongdc3[$warehousename]), 'sumdd3' => array_sum($laytongdt3[$warehousename]),
                    'sumdc4' => array_sum($laytongdc4[$warehousename]), 'sumdd4' => array_sum($laytongdt4[$warehousename]),
                    'sumc' => array_sum($laytongpc[$warehousename]), 'sumt' => array_sum($laytongpt[$warehousename]), 
                ];
            }
        }
        $targetcost = array_values($targetcost);
        $sumZip = $this->sumZip($targetcost);
        
        return array_merge($sumZip,$targetcost);
    }
    public function targetSROrder(Request $request) 
    {
        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        if($start_week > 52){
            if($end_week == 3){
                $weeks = collect([$start_week, 1,2,3]);
            }else{
                $weeks = collect([$start_week, 54,1,2]);
            }
            
        }else{
            $weeks = collect(range($start_week, $end_week));
        }

        $dangnhap = \Auth::user()->id;
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $ss1 = strtotime($dates[0]);

        if (strtotime($dt->toDateString()) <  $ss1) { 
            if ($dangnhap != 9003 && $dangnhap != 9074) {
                $role_watch = \DB::table('roles_target')->whereDate('d',$dates[0])->where('user_id',$dangnhap)->get();
                if ($role_watch->isEmpty()) {
                    return response()->json([
                        'message' => 'Bạn không có quyền xem'
                    ],400);
                }
            }
        }

        $orders = Order::filter($request->all())->get();
        $orders = $orders->groupBy(function($contact){
            return $contact->user_id;
        });
        $orders = $orders->map(function($contactsList,$key){
            return $contactsList->countBy(function($contact){
                $date = new Carbon($contact->start_date);
                return $date->weekOfYear;
            });
        });
        $year = date('Y',strtotime($dates[1]));
        $user = User::all()->filter(function($user){
            return $user->hasRole('sales');
        });
        $orders = $this->targetByOrderSR($orders,$weeks, $year);
        $noContact = $weeks->map(function($week){
            return [ 'w' => $week, 't' => 1, 'c' => 0, 'monday' => '.','tuesday' => '.','wednesday' => '.','thursday' => '.','friday' => '.','saturday' => '.','sunday' => '.', 'sumc' => 0, 'sumd' => 0 ];
        });
        $sales = $user->map(function($user) use ($orders,$noContact){
            $sr = $user->costcentersList();
            if($sr->isNotEmpty()){
                $nameSR = $sr->first()->name;
                $warehouse = Costcenter::where('name',$nameSR)->first('warehouse');
                $warehouse->warehouse;
            }
            else {
                $nameSR = 'khác';
                $warehouse = 'khác';
            }
            $infouser = isset($orders[$user->id]) ? $orders[$user->id] : $noContact;
            return[
                'id' => $user->id,
                'warehouse' => $warehouse,
                'name' => $user->name,
                'showroom' => $nameSR ,
                'contacts' => $infouser,
                'c' => collect($infouser)->sum('c')
            ];
        });
        foreach ($sales as $key => $value) {
            $sr[$value['showroom']][] = $value; 
        }
        unset($sr['khác']);
        foreach ($sr as $k1 => $v1) {
                $c[0] = $v1;
                $i = array();
                foreach ($c as $key22 => $value22) {
                    foreach ($value22 as $key23 => $value23) {
                        $i[$key23] = $value23['contacts'];
                    }
                }
                $sumArray = array();
                $sum1dd = array();
                $sum1dc = array();
                $sum2dd = array();
                $sum2dc = array();
                $sum3dd = array();
                $sum3dc = array();
                $sum4dd = array();
                $sum4dc = array();
                $idCostcenter = Costcenter::where('name',$k1)->first('id');
                foreach ($i as $k=>$subArray) {
                    foreach ($subArray as $id=>$value12) {
                        $sumArray[] = $value12['w'];
                        if($value12['w'] == $sumArray[0]) {
                            $sum1dd[] = $value12['sumd'];
                            $sum1dc[] = $value12['sumc'];
                        }
                        if($value12['w'] == $sumArray[0]+1) {
                            $sum2dd[] = $value12['sumd'];
                            $sum2dc[] = $value12['sumc'];
                        }
                        if($value12['w'] == $sumArray[0]+2) {
                            $sum3dd[] = $value12['sumd']; 
                            $sum3dc[] = $value12['sumc'];
                        }
                        if($value12['w'] == $sumArray[0]+3) {
                            $sum4dc[] = $value12['sumc'];
                            $sum4dd[] = $value12['sumd'];
                        }
                    }
                $t1 = $this->targetAllSR($idCostcenter->id,$weeks[0],$year,3);
                $t2 = $this->targetAllSR($idCostcenter->id,$weeks[1],$year,3);
                $t3 = $this->targetAllSR($idCostcenter->id,$weeks[2],$year,3);
                $t4 = $this->targetAllSR($idCostcenter->id,$weeks[3],$year,3);
                $sumt = $t1 + $t2 + $t3 + $t4;
                $sumdc1 = array_sum($sum1dc);
                $sumdc2 = array_sum($sum2dc);
                $sumdc3 = array_sum($sum3dc);
                $sumdc4 = array_sum($sum4dc);
                $sumdc = $sumdc1 + $sumdc2 + $sumdc3 + $sumdc4;
                $sumdd1 = array_sum($sum1dd);
                $sumdd2 = array_sum($sum2dd);
                $sumdd3 = array_sum($sum3dd);
                $sumdd4 = array_sum($sum4dd);
                $sumdd = $sumdd1 + $sumdd2 + $sumdd3 + $sumdd4;
                }
                
                $warehouse = Costcenter::where('name',$k1)->first('warehouse');
                $target[] = [   'costcenter'=>$k1,
                                'name'=>$v1,
                                'warehouse' => $warehouse->warehouse,
                                't1' => $t1,'t2' => $t2,'t3' => $t3,'t4' => $t4,
                                'c1' => $sumdd1 + $sumdc1,'c2' => $sumdd2 + $sumdc2,'c3' => $sumdd3 + $sumdc3,'c4' => $sumdd4 + $sumdc4, 
                                'sumc' => $sumdd + $sumdc , 'sumt' => $sumt, 
                                'sumdc' => $sumdc, 'sumdd' => $sumdd, 
                                'sumdc1' => $sumdc1, 'sumdd1' => $sumdd1,
                                'sumdc2' => $sumdc2, 'sumdd2' => $sumdd2,
                                'sumdc3' => $sumdc3, 'sumdd3' => $sumdd3,
                                'sumdc4' => $sumdc4, 'sumdd4' => $sumdd4,
                            ];
                foreach ($target as $khoa => $item) {
                    $showroom[$item['warehouse']][$khoa] = [
                        'costcenter' => $item['costcenter'],
                        'warehouse' => $item['warehouse'],
                        't1' => $item['t1'] ,'t2' => $item['t2'],'t3' => $item['t3'],'t4' => $item['t4'],
                        'c1' => $item['c1'],'c2' => $item['c2'],'c3' => $item['c3'],'c4' => $item['c4'], 
                        'sumc' => $item['sumc'] , 'sumt' => $item['sumt'], 
                        'sumdc' => $item['sumdc'], 'sumdd' => $item['sumdd'] ,
                        'sumdc1' => $item['sumdc1'], 'sumdd1' => $item['sumdd1'],
                        'sumdc2' => $item['sumdc2'], 'sumdd2' => $item['sumdd2'],
                        'sumdc3' => $item['sumdc3'], 'sumdd3' => $item['sumdd3'],
                        'sumdc4' => $item['sumdc4'], 'sumdd4' => $item['sumdd4'],
                    ];
                }
                foreach ($showroom as $warehousename => $infoshowroom) {
                    $laytongt1 = array();$laytongc1 = array();
                    $laytongt2 = array();$laytongc2 = array();
                    $laytongt3 = array();$laytongc3 = array();
                    $laytongt4 = array();$laytongc4 = array();
                    $laytongdt1 = array();$laytongdc1 = array();
                    $laytongdt2 = array();$laytongdc2 = array();
                    $laytongdt3 = array();$laytongdc3 = array();
                    $laytongdt4 = array();$laytongdc4 = array();
                    $laytongpt = array();$laytongpc = array();
                    foreach ($infoshowroom as $getinfo) {
                        $laytongt1[$getinfo['warehouse']][] = $getinfo['t1'];$laytongc1[$getinfo['warehouse']][] = $getinfo['c1'];
                        $laytongt2[$getinfo['warehouse']][] = $getinfo['t2'];$laytongc2[$getinfo['warehouse']][] = $getinfo['c2'];
                        $laytongt3[$getinfo['warehouse']][] = $getinfo['t3'];$laytongc3[$getinfo['warehouse']][] = $getinfo['c3'];
                        $laytongt4[$getinfo['warehouse']][] = $getinfo['t4'];$laytongc4[$getinfo['warehouse']][] = $getinfo['c4'];
                        $laytongdt1[$getinfo['warehouse']][] = $getinfo['sumdd1'];$laytongdc1[$getinfo['warehouse']][] = $getinfo['sumdc1'];
                        $laytongdt2[$getinfo['warehouse']][] = $getinfo['sumdd2'];$laytongdc2[$getinfo['warehouse']][] = $getinfo['sumdc2'];
                        $laytongdt3[$getinfo['warehouse']][] = $getinfo['sumdd3'];$laytongdc3[$getinfo['warehouse']][] = $getinfo['sumdc3'];
                        $laytongdt4[$getinfo['warehouse']][] = $getinfo['sumdd4'];$laytongdc4[$getinfo['warehouse']][] = $getinfo['sumdc4'];
                        $laytongpt[$getinfo['warehouse']][] = $getinfo['sumt'];$laytongpc[$getinfo['warehouse']][] = $getinfo['sumc'];
                    }
                    $targetcost[$warehousename] = ['warehouse' => $warehousename , 'costcenter' => array_values($infoshowroom), 
                        't1' => array_sum($laytongt1[$warehousename]),'c1' => array_sum($laytongc1[$warehousename]),
                        't2' => array_sum($laytongt2[$warehousename]),'c2' => array_sum($laytongc2[$warehousename]),
                        't3' => array_sum($laytongt3[$warehousename]),'c3' => array_sum($laytongc3[$warehousename]),
                        't4' => array_sum($laytongt4[$warehousename]),'c4' => array_sum($laytongc4[$warehousename]),
                        'sumdc1' => array_sum($laytongdc1[$warehousename]), 'sumdd1' => array_sum($laytongdt1[$warehousename]),
                        'sumdc2' => array_sum($laytongdc2[$warehousename]), 'sumdd2' => array_sum($laytongdt2[$warehousename]),
                        'sumdc3' => array_sum($laytongdc3[$warehousename]), 'sumdd3' => array_sum($laytongdt3[$warehousename]),
                        'sumdc4' => array_sum($laytongdc4[$warehousename]), 'sumdd4' => array_sum($laytongdt4[$warehousename]),
                        'sumc' => array_sum($laytongpc[$warehousename]), 'sumt' => array_sum($laytongpt[$warehousename]), 
                    ];
                }
            }
        $targetcost = array_values($targetcost);
        $sumZip = $this->sumZip($targetcost);

        return array_merge($sumZip,$targetcost);
    }
    protected function targetByOrderSR($items, $weeks,$year)
    {
        $dto = new DateTime();
        
        return $items->map(function($item,$key) use ($weeks,$dto,$year){
            return $weeks->map(function($week) use ($item,$dto,$year,$key){
                if($week > 52){
                    $year = $year - 1;
                }else{
                    $year = $year;
                }
                $dto->setISODate($year, $week);
                $ret['week_start'] = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $ret['week_end'] = $dto->format('Y-m-d');
                $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);
                $monday = Order::where('user_id',$key)->whereDate('created_at',$date[0])->count();
                $tuesday = Order::where('user_id',$key)->whereDate('created_at',$date[1])->count();
                $wednesday = Order::where('user_id',$key)->whereDate('created_at',$date[2])->count();
                $thursday = Order::where('user_id',$key)->whereDate('created_at',$date[3])->count();
                $friday = Order::where('user_id',$key)->whereDate('created_at',$date[4])->count();
                $saturday = Order::where('user_id',$key)->whereDate('created_at',$date[5])->count();
                $sunday = Order::where('user_id',$key)->whereDate('created_at',$date[6])->count();
                $sumd = $monday + $tuesday + $wednesday + $thursday;
                $sumc = $friday + $saturday +$sunday;
                return [
                    'c'=> isset($item[$week]) ? $item[$week] : 0 ,
                    'sumd' => $sumd,
                    'sumc' => $sumc,
                    'w' => $week,
                ];
            });
        });
    }
    public function targetSRAmount(Request $request) 
    {
        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        if($start_week > 52){
            if($end_week == 3){
                $weeks = collect([$start_week, 1,2,3]);
            }else{
                $weeks = collect([$start_week, 54,1,2]);
            }
            
        }else{
            $weeks = collect(range($start_week, $end_week));
        }
       
       
        
        
        $dangnhap = \Auth::user()->id;
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $ss1 = strtotime($dates[0]);

        if (strtotime($dt->toDateString()) <  $ss1) { 
            if ($dangnhap != 9003 && $dangnhap != 9074) {
                $role_watch = \DB::table('roles_target')->whereDate('d',$dates[0])->where('user_id',$dangnhap)->get();
                if ($role_watch->isEmpty()) {
                    return response()->json([
                        'message' => 'Bạn không có quyền xem'
                    ],400);
                }
            }
        }

        $orders = Order::filter($request->all())->get();
        $orders = $orders->groupBy(function($contact){
            return $contact->user_id;
        });
        $orders = $orders->map(function($contactsList,$key){
            return $contactsList->countBy(function($contact){
                $date = new Carbon($contact->start_date);
                return $date->weekOfYear;
            });
        });
        $year = date('Y',strtotime($dates[1]));
        $user = User::all()->filter(function($user){
            return $user->hasRole('sales');
        });
       
        $orders = $this->targetByAmountSR($orders,$weeks, $year);

        $dto = new DateTime();
        $sales = $user->map(function($user) use ($orders,$weeks,$year,$dto){
            $noContact = $weeks->map(function($week) use ($user,$year,$dto){
                $dto->setISODate($year, $week);
                $ret['week_start'] = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $ret['week_end'] = $dto->format('Y-m-d');
                $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);
                $sumd = Order::where('user_id',$user->id)->whereBetween('start_date',[$date[0], $date[3]])->sum('amount');
                $sumc = Order::where('user_id',$user->id)->whereBetween('start_date',[$date[4], $date[6]])->sum('amount');
                return [ 'w' => $week, 't' => 25, 'c' => 0, 'monday' => '.','tuesday' => '.','wednesday' => '.','thursday' => '.','friday' => '.','saturday' => '.','sunday' => '.', 'sumc' => 0, 'sumd' => $sumd , 'sumc' => $sumc ];
            });
            $sr = $user->costcentersList();
            if($sr->isNotEmpty()){
                $nameSR = $sr->first()->name;
                $warehouse = Costcenter::where('name',$nameSR)->first('warehouse');
                $warehouse->warehouse;
            }
            else {
                $nameSR = 'khác';
                $warehouse = 'khác';
            }
            $infouser = isset($orders[$user->id]) ? $orders[$user->id] : $noContact;
            return[
                'id' => $user->id,
                'warehouse' => $warehouse,
                'name' => $user->name,
                'showroom' => $nameSR ,
                'contacts' => $infouser,
                'c' => collect($infouser)->sum('c')
            ];
        });
        foreach ($sales as $key => $value) {
            $sr[$value['showroom']][] = $value; 
        }
        // $d1 = $dto->setISODate($year, $weeks[0])->format('Y-m-d');
        // $d_week = date('Y-m-d',strtotime($d1 . " +3 day"));
        // $c_week = date('Y-m-d',strtotime($d1 . " +6 day"));
        // dd($c_week);
        unset($sr['khác']);
        foreach ($sr as $k1 => $v1) {
            $c[0] = $v1;
            $i = array();
            foreach ($c as $key22 => $value22) {
                foreach ($value22 as $key23 => $value23) {
                    $i[$key23] = $value23['contacts'];
                }
            }
            $sumArray = array();
            $sum1dd = array();
            $sum1dc = array();
            $sum2dd = array();
            $sum2dc = array();
            $sum3dd = array();
            $sum3dc = array();
            $sum4dd = array();
            $sum4dc = array();
            $idCostcenter = Costcenter::where('name',$k1)->first('id');
            foreach ($i as $k=>$subArray) {
                foreach ($subArray as $id=>$value12) {
                    $sumArray[] = $value12['w'];
                    if($value12['w'] == $weeks[0]) {
                        $sum1dd[] = $value12['sumd'];
                        $sum1dc[] = $value12['sumc'];
                    }
                    // if ($sumArray[0] > 52) {
                    //     $sumArray[0] = 0;
                    // }
                    if($value12['w'] == $weeks[1]) {
                        $sum2dd[] = $value12['sumd'];
                        $sum2dc[] = $value12['sumc'];
                    }
                    if($value12['w'] == $weeks[2]) {
                        $sum3dd[] = $value12['sumd']; 
                        $sum3dc[] = $value12['sumc'];
                    }
                    if($value12['w'] == $weeks[3]) {
                        $sum4dc[] = $value12['sumc'];
                        $sum4dd[] = $value12['sumd'];
                    }
                }
                $t1 = $this->targetAllSR($idCostcenter->id,$weeks[0],$year,4);
                $t2 = $this->targetAllSR($idCostcenter->id,$weeks[1],$year,4);
                $t3 = $this->targetAllSR($idCostcenter->id,$weeks[2],$year,4);
                $t4 = $this->targetAllSR($idCostcenter->id,$weeks[3],$year,4);
                $sumt = $t1 + $t2 + $t3 + $t4;
                $sumdc1 = array_sum($sum1dc);
                $sumdc2 = array_sum($sum2dc);
                $sumdc3 = array_sum($sum3dc);
                $sumdc4 = array_sum($sum4dc);
                $sumdc = $sumdc1 + $sumdc2 + $sumdc3 + $sumdc4;
                $sumdd1 = array_sum($sum1dd);
                $sumdd2 = array_sum($sum2dd);
                $sumdd3 = array_sum($sum3dd);
                $sumdd4 = array_sum($sum4dd);
                $sumdd = $sumdd1 + $sumdd2 + $sumdd3 + $sumdd4;
            }
            
            $warehouse = Costcenter::where('name',$k1)->where('id','<',39)->first('warehouse');
            if (isset($warehouse)) {
                $target[] = [   'costcenter'=>$k1,
                                'name'=>$v1,
                                'warehouse' => $warehouse->warehouse,
                                't1' => $t1,
                                't2' => $t2,
                                't3' => $t3,
                                't4' => $t4,
                                'c1' => $sumdd1 + $sumdc1,'c2' => $sumdd2 + $sumdc2,'c3' => $sumdd3 + $sumdc3,'c4' => $sumdd4 + $sumdc4, 
                                'sumc' => $sumdd + $sumdc , 'sumt' => $sumt, 
                                'sumdc' => $sumdc, 'sumdd' => $sumdd, 
                                'sumdc1' => $sumdc1, 'sumdd1' => $sumdd1,
                                'sumdc2' => $sumdc2, 'sumdd2' => $sumdd2,
                                'sumdc3' => $sumdc3, 'sumdd3' => $sumdd3,
                                'sumdc4' => $sumdc4, 'sumdd4' => $sumdd4,
                            ];
                foreach ($target as $khoa => $item) {
                    $showroom[$item['warehouse']][$khoa] = [
                        'costcenter' => $item['costcenter'],
                        'warehouse' => $item['warehouse'],
                        't1' => $item['t1'],'t2' => $item['t2'],'t3' => $item['t3'],'t4' => $item['t4'],
                        'c1' => $item['c1'],'c2' => $item['c2'],'c3' => $item['c3'],'c4' => $item['c4'], 
                        'sumc' => $item['sumc'] , 'sumt' => $item['sumt'], 
                        'sumdc' => $item['sumdc'], 'sumdd' => $item['sumdd'] ,
                        'sumdc1' => $item['sumdc1'], 'sumdd1' => $item['sumdd1'],
                        'sumdc2' => $item['sumdc2'], 'sumdd2' => $item['sumdd2'],
                        'sumdc3' => $item['sumdc3'], 'sumdd3' => $item['sumdd3'],
                        'sumdc4' => $item['sumdc4'], 'sumdd4' => $item['sumdd4'],
                    ];
                }
                foreach ($showroom as $warehousename => $infoshowroom) {
                    $laytongt1 = array();$laytongc1 = array();
                    $laytongt2 = array();$laytongc2 = array();
                    $laytongt3 = array();$laytongc3 = array();
                    $laytongt4 = array();$laytongc4 = array();
                    $laytongdt1 = array();$laytongdc1 = array();
                    $laytongdt2 = array();$laytongdc2 = array();
                    $laytongdt3 = array();$laytongdc3 = array();
                    $laytongdt4 = array();$laytongdc4 = array();
                    $laytongpt = array();$laytongpc = array();
                    foreach ($infoshowroom as $getinfo) {
                        $laytongt1[$getinfo['warehouse']][] = $getinfo['t1'];$laytongc1[$getinfo['warehouse']][] = $getinfo['c1'];
                        $laytongt2[$getinfo['warehouse']][] = $getinfo['t2'];$laytongc2[$getinfo['warehouse']][] = $getinfo['c2'];
                        $laytongt3[$getinfo['warehouse']][] = $getinfo['t3'];$laytongc3[$getinfo['warehouse']][] = $getinfo['c3'];
                        $laytongt4[$getinfo['warehouse']][] = $getinfo['t4'];$laytongc4[$getinfo['warehouse']][] = $getinfo['c4'];
                        $laytongdt1[$getinfo['warehouse']][] = $getinfo['sumdd1'];$laytongdc1[$getinfo['warehouse']][] = $getinfo['sumdc1'];
                        $laytongdt2[$getinfo['warehouse']][] = $getinfo['sumdd2'];$laytongdc2[$getinfo['warehouse']][] = $getinfo['sumdc2'];
                        $laytongdt3[$getinfo['warehouse']][] = $getinfo['sumdd3'];$laytongdc3[$getinfo['warehouse']][] = $getinfo['sumdc3'];
                        $laytongdt4[$getinfo['warehouse']][] = $getinfo['sumdd4'];$laytongdc4[$getinfo['warehouse']][] = $getinfo['sumdc4'];
                        $laytongpt[$getinfo['warehouse']][] = $getinfo['sumt'];$laytongpc[$getinfo['warehouse']][] = $getinfo['sumc'];
                    }
                    $asmt = $this->sumasm($warehousename,$dates[0], $dates[1]);
                    $targetcost[$warehousename] = ['warehouse' => $warehousename , 'costcenter' => array_values($infoshowroom), 
                        't1' => array_sum($laytongt1[$warehousename]),'c1' => array_sum($laytongc1[$warehousename]) + $asmt[0] - $asmt[4] ,
                        't2' => array_sum($laytongt2[$warehousename]),'c2' => array_sum($laytongc2[$warehousename]) + $asmt[1] - $asmt[5] ,
                        't3' => array_sum($laytongt3[$warehousename]),'c3' => array_sum($laytongc3[$warehousename]) + $asmt[2] - $asmt[6] ,
                        't4' => array_sum($laytongt4[$warehousename]),'c4' => array_sum($laytongc4[$warehousename]) + $asmt[3] - $asmt[7] ,
                        'sumdc1' => array_sum($laytongdc1[$warehousename]), 'sumdd1' => array_sum($laytongdt1[$warehousename]),
                        'sumdc2' => array_sum($laytongdc2[$warehousename]), 'sumdd2' => array_sum($laytongdt2[$warehousename]),
                        'sumdc3' => array_sum($laytongdc3[$warehousename]), 'sumdd3' => array_sum($laytongdt3[$warehousename]),
                        'sumdc4' => array_sum($laytongdc4[$warehousename]), 'sumdd4' => array_sum($laytongdt4[$warehousename]),
                        'sumc' => array_sum($laytongpc[$warehousename]) + $asmt[0] + $asmt[1] + $asmt[2] + $asmt[3] - $asmt[7] - $asmt[6] - $asmt[5] - $asmt[4], 'sumt' => array_sum($laytongpt[$warehousename]), 
                        'asmdon1' => $asmt[0],'asmdon2' => $asmt[1],'asmdon3' => $asmt[2],'asmdon4' => $asmt[3],
                        'cold1' => array_sum($laytongc1[$warehousename]),'cold2' => array_sum($laytongc2[$warehousename]),
                        'cold3' => array_sum($laytongc3[$warehousename]),'cold4' => array_sum($laytongc4[$warehousename]),
                        'sumasm' => $asmt[0] + $asmt[1] + $asmt[2] + $asmt[3],
                        'huydon1' => $asmt[4],'huydon2' => $asmt[5],'huydon3' => $asmt[6],'huydon4' => $asmt[7],
                    ];
                }
            }
        }
        $targetcost = array_values($targetcost);
        $sumZip = $this->sumZipNew($targetcost,$weeks,$year);
        $return = array_merge($sumZip,$targetcost);
        if($year == 2020){
            if($weeks[0] > 42 ) {
               unset($return[8]);
               unset($return[4]);
            
               unset($return[6]);
               unset($return[2]);
            }
        }
        return $return;
    }

    public function targetSRAmountnew(Request $request){
        $date = $request['dates'][0]; // monday
        $start = date("Y-m-d", strtotime('monday this week', strtotime($date)));   
        $dates = [$start,$request['dates'][1]];
        $queryCalendar = \DB::table('calendar')->whereDate('end',$dates[1])->first();
        $queryWeek = \DB::table('calendar_t')->where('id_p',$queryCalendar->id)->get();
        $sr = Costcenter::whereBetween('closed',[$dates[0],'2100-01-01'])->get()->groupBy('warehouse');
        $tong['Tổng'][0]['ds'] = 0;
        $tong['Tổng'][0]['chia'] = 0;
        $tong['Tổng'][0]['tong'] = 0;
        $tong['Tổng'][0]['targettong'] = 0;
        $tong['Tổng'][1]['ds'] = 0;
        $tong['Tổng'][1]['chia'] = 0;
        $tong['Tổng'][1]['tong'] = 0;
        $tong['Tổng'][1]['targettong'] = 0;
        $tong['Tổng'][2]['ds'] = 0;
        $tong['Tổng'][2]['chia'] = 0;
        $tong['Tổng'][2]['tong'] = 0;
        $tong['Tổng'][2]['targettong'] = 0;
        $tong['Tổng'][3]['ds'] = 0;
        $tong['Tổng'][3]['chia'] = 0;
        $tong['Tổng'][3]['tong'] = 0;
        $tong['Tổng'][3]['targettong'] = 0;
        $tong['Tổng'][4]['ds'] = 0;
        $tong['Tổng'][4]['chia'] = 0;
        $tong['Tổng'][4]['tong'] = 0;
        $tong['Tổng'][4]['targettong'] = 0;
// dd($queryWeek);
        foreach ($sr as $cos => $value) {
            if ($cos !== 'Online') {
                $orders[$cos][0]['ds'] = Order::whereBetween('start_date',$dates)->whereIn('costcenter', $value->pluck('code'))->sum('amount');
                $orders[$cos][0]['chia'] = Order::whereBetween('start_date',$dates)->whereIn('cos_chia', $value->pluck('code'))->sum('amount') / 2;
                $orders[$cos][0]['tong'] = $orders[$cos][0]['ds'] + $orders[$cos][0]['chia'];
                $orders[$cos][0]['type'] = 1;

                $orders[$cos][1]['ds'] = Order::whereBetween('start_date',[$queryWeek[0]->start_t,$queryWeek[0]->end_t])->whereIn('costcenter', $value->pluck('code'))->sum('amount');
                $orders[$cos][1]['chia'] = Order::whereBetween('start_date',[$queryWeek[0]->start_t,$queryWeek[0]->end_t])->whereIn('cos_chia', $value->pluck('code'))->sum('amount') / 2 ;
                $orders[$cos][1]['tong'] = $orders[$cos][1]['ds'] + $orders[$cos][1]['chia'];

                $orders[$cos][2]['ds'] = Order::whereBetween('start_date',[$queryWeek[1]->start_t,$queryWeek[1]->end_t])->whereIn('costcenter', $value->pluck('code'))->sum('amount');
                $orders[$cos][2]['chia'] = Order::whereBetween('start_date',[$queryWeek[1]->start_t,$queryWeek[1]->end_t])->whereIn('cos_chia', $value->pluck('code'))->sum('amount') / 2;
                $orders[$cos][2]['tong'] = $orders[$cos][2]['ds'] + $orders[$cos][2]['chia'];
                        
                $orders[$cos][3]['ds'] = Order::whereBetween('start_date',[$queryWeek[2]->start_t,$queryWeek[2]->end_t])->whereIn('costcenter', $value->pluck('code'))->sum('amount');
                $orders[$cos][3]['chia'] = Order::whereBetween('start_date',[$queryWeek[2]->start_t,$queryWeek[2]->end_t])->whereIn('cos_chia', $value->pluck('code'))->sum('amount') / 2;
                $orders[$cos][3]['tong'] = $orders[$cos][3]['ds'] + $orders[$cos][3]['chia'];

                $orders[$cos][4]['ds'] = Order::whereBetween('start_date',[$queryWeek[3]->start_t,$queryWeek[3]->end_t])->whereIn('costcenter', $value->pluck('code'))->sum('amount');
                $orders[$cos][4]['chia'] = Order::whereBetween('start_date',[$queryWeek[3]->start_t,$queryWeek[3]->end_t])->whereIn('cos_chia', $value->pluck('code'))->sum('amount') / 2;
                $orders[$cos][4]['tong'] = $orders[$cos][4]['ds'] + $orders[$cos][4]['chia'];

                $t1 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$value->pluck('id'))->whereDate('close',$queryWeek[0]->end_t)->sum('amount_number');
                $t2 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$value->pluck('id'))->whereDate('close',$queryWeek[1]->end_t)->sum('amount_number');
                $t3 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$value->pluck('id'))->whereDate('close',$queryWeek[2]->end_t)->sum('amount_number');
                $t4 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$value->pluck('id'))->whereDate('close',$queryWeek[3]->end_t)->sum('amount_number');

                $orders[$cos][0]['targettong'] = $t1 + $t2 + $t3 + $t4;
                $orders[$cos][1]['targettong'] = $t1;
                $orders[$cos][2]['targettong'] = $t2;
                $orders[$cos][3]['targettong'] = $t3;
                $orders[$cos][4]['targettong'] = $t4;

                $tong['Tổng'][0]['ds'] += $orders[$cos][0]['ds'];
                $tong['Tổng'][1]['ds'] += $orders[$cos][1]['ds'];
                $tong['Tổng'][2]['ds'] += $orders[$cos][2]['ds'];
                $tong['Tổng'][3]['ds'] += $orders[$cos][3]['ds'];
                $tong['Tổng'][4]['ds'] += $orders[$cos][4]['ds'];
                $tong['Tổng'][0]['type'] = 1;
                $tong['Tổng'][0]['chia'] += $orders[$cos][0]['chia'];
                $tong['Tổng'][1]['chia'] += $orders[$cos][1]['chia'];
                $tong['Tổng'][2]['chia'] += $orders[$cos][2]['chia'];
                $tong['Tổng'][3]['chia'] += $orders[$cos][3]['chia'];
                $tong['Tổng'][4]['chia'] += $orders[$cos][4]['chia'];
                $tong['Tổng'][0]['tong'] += $orders[$cos][0]['tong'];
                $tong['Tổng'][1]['tong'] += $orders[$cos][1]['tong'];
                $tong['Tổng'][2]['tong'] += $orders[$cos][2]['tong'];
                $tong['Tổng'][3]['tong'] += $orders[$cos][3]['tong'];
                $tong['Tổng'][4]['tong'] += $orders[$cos][4]['tong'];
                $tong['Tổng'][0]['targettong'] += $t1 + $t2 + $t3 + $t4;
                $tong['Tổng'][1]['targettong'] += $t1;
                $tong['Tổng'][2]['targettong'] += $t2;
                $tong['Tổng'][3]['targettong'] += $t3;
                $tong['Tổng'][4]['targettong'] += $t4;
                
                if ($cos !== 'Hà Nội') {
                    $groups[$cos.'  '][0]['ds'] = $orders[$cos][0]['ds'];
                    $groups[$cos.'  '][0]['chia'] = $orders[$cos][0]['chia'];
                    $groups[$cos.'  '][0]['tong'] = $orders[$cos][0]['tong'];
                    $groups[$cos.'  '][0]['targettong'] = $orders[$cos][0]['targettong'];
                    $groups[$cos.'  '][1]['ds'] = $orders[$cos][1]['ds'];
                    $groups[$cos.'  '][1]['chia'] = $orders[$cos][1]['chia'];
                    $groups[$cos.'  '][1]['tong'] = $orders[$cos][1]['tong'];
                    $groups[$cos.'  '][1]['targettong'] = $orders[$cos][1]['targettong'];
                    $groups[$cos.'  '][2]['ds'] = $orders[$cos][2]['ds'];
                    $groups[$cos.'  '][2]['chia'] = $orders[$cos][2]['chia'];
                    $groups[$cos.'  '][2]['tong'] = $orders[$cos][2]['tong'];
                    $groups[$cos.'  '][2]['targettong'] = $orders[$cos][2]['targettong'];
                    $groups[$cos.'  '][3]['ds'] = $orders[$cos][3]['ds'];
                    $groups[$cos.'  '][3]['chia'] = $orders[$cos][3]['chia'];
                    $groups[$cos.'  '][3]['tong'] = $orders[$cos][3]['tong'];
                    $groups[$cos.'  '][3]['targettong'] = $orders[$cos][3]['targettong'];
                    $groups[$cos.'  '][4]['ds'] = $orders[$cos][4]['ds'];
                    $groups[$cos.'  '][4]['chia'] = $orders[$cos][4]['chia'];
                    $groups[$cos.'  '][4]['tong'] = $orders[$cos][4]['tong'];
                    $groups[$cos.'  '][4]['targettong'] = $orders[$cos][4]['targettong'];
                    $groups[$cos.'  '][0]['type'] = 1;
                }

            }else{
                $orders[$cos][0]['ds'] = Order::whereBetween('start_date',$dates)->whereIn('costcenter', $value->pluck('code'))->whereNull('cos_chia')->sum('amount');
                $orders[$cos][0]['chia'] = Order::whereBetween('start_date',$dates)->whereIn('costcenter', $value->pluck('code'))->whereNotNull ('cos_chia')->sum('amount') / 2;
                $orders[$cos][0]['tong'] = $orders[$cos][0]['ds'] + $orders[$cos][0]['chia'];
                $orders[$cos][0]['type'] = 1;
                $orders[$cos][0]['typetarget'] = 1;
                $orders[$cos][1]['target'] = \DB::table('target_online')->whereDate('date',$queryWeek[0]->end_t)->sum('amount');
                $orders[$cos][2]['target'] = \DB::table('target_online')->whereDate('date',$queryWeek[1]->end_t)->sum('amount');
                $orders[$cos][3]['target'] = \DB::table('target_online')->whereDate('date',$queryWeek[2]->end_t)->sum('amount');
                $orders[$cos][4]['target'] = \DB::table('target_online')->whereDate('date',$queryWeek[3]->end_t)->sum('amount');
                $orders[$cos][0]['target'] = $orders[$cos][1]['target'] + $orders[$cos][2]['target'] + $orders[$cos][3]['target'] + $orders[$cos][4]['target'];

                $orders[$cos][1]['ds'] = Order::whereBetween('start_date',[$queryWeek[0]->start_t,$queryWeek[0]->end_t])->whereIn('costcenter', $value->pluck('code'))->whereNull('cos_chia')->sum('amount');
                $orders[$cos][1]['chia'] = Order::whereBetween('start_date',[$queryWeek[0]->start_t,$queryWeek[0]->end_t])->whereIn('costcenter', $value->pluck('code'))->whereNotNull('cos_chia')->sum('amount') / 2 ;
                $orders[$cos][1]['tong'] = $orders[$cos][1]['ds'] + $orders[$cos][1]['chia'];

                $orders[$cos][2]['ds'] = Order::whereBetween('start_date',[$queryWeek[1]->start_t,$queryWeek[1]->end_t])->whereIn('costcenter', $value->pluck('code'))->whereNull('cos_chia')->sum('amount');
                $orders[$cos][2]['chia'] = Order::whereBetween('start_date',[$queryWeek[1]->start_t,$queryWeek[1]->end_t])->whereIn('costcenter', $value->pluck('code'))->whereNotNull('cos_chia')->sum('amount') / 2;
                $orders[$cos][2]['tong'] = $orders[$cos][2]['ds'] + $orders[$cos][2]['chia'];
                        
                $orders[$cos][3]['ds'] = Order::whereBetween('start_date',[$queryWeek[2]->start_t,$queryWeek[2]->end_t])->whereIn('costcenter', $value->pluck('code'))->whereNull('cos_chia')->sum('amount');
                $orders[$cos][3]['chia'] = Order::whereBetween('start_date',[$queryWeek[2]->start_t,$queryWeek[2]->end_t])->whereIn('costcenter', $value->pluck('code'))->whereNotNull('cos_chia')->sum('amount') / 2;
                $orders[$cos][3]['tong'] = $orders[$cos][3]['ds'] + $orders[$cos][3]['chia'];

                $orders[$cos][4]['ds'] = Order::whereBetween('start_date',[$queryWeek[3]->start_t,$queryWeek[3]->end_t])->whereIn('costcenter', $value->pluck('code'))->whereNull('cos_chia')->sum('amount');
                $orders[$cos][4]['chia'] = Order::whereBetween('start_date',[$queryWeek[3]->start_t,$queryWeek[3]->end_t])->whereIn('costcenter', $value->pluck('code'))->whereNotNull('cos_chia')->sum('amount') / 2;
                $orders[$cos][4]['tong'] = $orders[$cos][4]['ds'] + $orders[$cos][4]['chia'];

                $t1 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$value->pluck('id'))->whereDate('close',$queryWeek[0]->end_t)->sum('amount_number');
                $t2 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$value->pluck('id'))->whereDate('close',$queryWeek[1]->end_t)->sum('amount_number');
                $t3 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$value->pluck('id'))->whereDate('close',$queryWeek[2]->end_t)->sum('amount_number');
                $t4 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$value->pluck('id'))->whereDate('close',$queryWeek[3]->end_t)->sum('amount_number');

                $orders[$cos][0]['targettong'] = $t1 + $t2 + $t3 + $t4;
                $orders[$cos][1]['targettong'] = $t1;
                $orders[$cos][2]['targettong'] = $t2;
                $orders[$cos][3]['targettong'] = $t3;
                $orders[$cos][4]['targettong'] = $t4;

                $tong['Tổng'][0]['ds'] += $orders[$cos][0]['ds'];
                $tong['Tổng'][1]['ds'] += $orders[$cos][1]['ds'];
                $tong['Tổng'][2]['ds'] += $orders[$cos][2]['ds'];
                $tong['Tổng'][3]['ds'] += $orders[$cos][3]['ds'];
                $tong['Tổng'][4]['ds'] += $orders[$cos][4]['ds'];
                $tong['Tổng'][0]['type'] = 1;
                $tong['Tổng'][0]['chia'] += $orders[$cos][0]['chia'];
                $tong['Tổng'][1]['chia'] += $orders[$cos][1]['chia'];
                $tong['Tổng'][2]['chia'] += $orders[$cos][2]['chia'];
                $tong['Tổng'][3]['chia'] += $orders[$cos][3]['chia'];
                $tong['Tổng'][4]['chia'] += $orders[$cos][4]['chia'];
                $tong['Tổng'][0]['tong'] += $orders[$cos][0]['tong'];
                $tong['Tổng'][1]['tong'] += $orders[$cos][1]['tong'];
                $tong['Tổng'][2]['tong'] += $orders[$cos][2]['tong'];
                $tong['Tổng'][3]['tong'] += $orders[$cos][3]['tong'];
                $tong['Tổng'][4]['tong'] += $orders[$cos][4]['tong'];
                $tong['Tổng'][0]['targettong'] += $t1 + $t2 + $t3 + $t4;
                $tong['Tổng'][1]['targettong'] += $t1;
                $tong['Tổng'][2]['targettong'] += $t2;
                $tong['Tổng'][3]['targettong'] += $t3;
                $tong['Tổng'][4]['targettong'] += $t4;

                $groups[$cos.'  '][0]['ds'] = $orders[$cos][0]['ds'];
                $groups[$cos.'  '][0]['chia'] = $orders[$cos][0]['chia'];
                $groups[$cos.'  '][0]['tong'] = $orders[$cos][0]['tong'];
                $groups[$cos.'  '][0]['targettong'] = $orders[$cos][0]['targettong'];
                $groups[$cos.'  '][1]['ds'] = $orders[$cos][1]['ds'];
                $groups[$cos.'  '][1]['chia'] = $orders[$cos][1]['chia'];
                $groups[$cos.'  '][1]['tong'] = $orders[$cos][1]['tong'];
                $groups[$cos.'  '][1]['targettong'] = $orders[$cos][1]['targettong'];
                $groups[$cos.'  '][2]['ds'] = $orders[$cos][2]['ds'];
                $groups[$cos.'  '][2]['chia'] = $orders[$cos][2]['chia'];
                $groups[$cos.'  '][2]['tong'] = $orders[$cos][2]['tong'];
                $groups[$cos.'  '][2]['targettong'] = $orders[$cos][2]['targettong'];
                $groups[$cos.'  '][3]['ds'] = $orders[$cos][3]['ds'];
                $groups[$cos.'  '][3]['chia'] = $orders[$cos][3]['chia'];
                $groups[$cos.'  '][3]['tong'] = $orders[$cos][3]['tong'];
                $groups[$cos.'  '][3]['targettong'] = $orders[$cos][3]['targettong'];
                $groups[$cos.'  '][4]['ds'] = $orders[$cos][4]['ds'];
                $groups[$cos.'  '][4]['chia'] = $orders[$cos][4]['chia'];
                $groups[$cos.'  '][4]['tong'] = $orders[$cos][4]['tong'];
                $groups[$cos.'  '][4]['targettong'] = $orders[$cos][4]['targettong'];
                $groups[$cos.'  '][0]['type'] = 1;
                $groups[$cos.'  '][0]['target'] = $orders[$cos][0]['target'];
                $groups[$cos.'  '][1]['target'] = $orders[$cos][1]['target'];
                $groups[$cos.'  '][2]['target'] = $orders[$cos][2]['target'];
                $groups[$cos.'  '][3]['target'] = $orders[$cos][3]['target'];
                $groups[$cos.'  '][4]['target'] = $orders[$cos][4]['target'];
            }
            foreach ($value as $v) {
                if ($v->code !== 'ON_MB') {
                    $t1 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$v->id)->whereDate('close',$queryWeek[0]->end_t)->sum('amount_number');
                    $t2 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$v->id)->whereDate('close',$queryWeek[1]->end_t)->sum('amount_number');
                    $t3 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$v->id)->whereDate('close',$queryWeek[2]->end_t)->sum('amount_number');
                    $t4 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$v->id)->whereDate('close',$queryWeek[3]->end_t)->sum('amount_number');  

                    $orders[$v->name][0]['ds'] = Order::whereBetween('start_date',$dates)->where('costcenter', $v->code)->sum('amount');
                    $orders[$v->name][0]['chia'] = Order::whereBetween('start_date',$dates)->where('cos_chia', $v->code)->sum('amount') / 2;
                    $orders[$v->name][0]['tong'] = $orders[$v->name][0]['ds'] + $orders[$v->name][0]['chia'];
                    $orders[$v->name][0]['type'] = 0;
                    $orders[$v->name][0]['targettong'] = $t1 + $t2 + $t3 + $t4;

                    $orders[$v->name][1]['ds'] = Order::whereBetween('start_date',[$queryWeek[0]->start_t,$queryWeek[0]->end_t])->where('costcenter', $v->code)->sum('amount');
                    $orders[$v->name][1]['chia'] = Order::whereBetween('start_date',[$queryWeek[0]->start_t,$queryWeek[0]->end_t])->where('cos_chia', $v->code)->sum('amount') / 2;
                    $orders[$v->name][1]['tong'] = $orders[$v->name][1]['ds'] + $orders[$v->name][1]['chia'];
                    $orders[$v->name][1]['targettong'] = $t1;

                    $orders[$v->name][2]['ds'] = Order::whereBetween('start_date',[$queryWeek[1]->start_t,$queryWeek[1]->end_t])->where('costcenter', $v->code)->sum('amount');
                    $orders[$v->name][2]['chia'] = Order::whereBetween('start_date',[$queryWeek[1]->start_t,$queryWeek[1]->end_t])->where('cos_chia', $v->code)->sum('amount') / 2;
                    $orders[$v->name][2]['tong'] = $orders[$v->name][2]['ds'] + $orders[$v->name][2]['chia'];
                    $orders[$v->name][2]['targettong'] = $t2;

                    $orders[$v->name][3]['ds'] = Order::whereBetween('start_date',[$queryWeek[2]->start_t,$queryWeek[2]->end_t])->where('costcenter', $v->code)->sum('amount');
                    $orders[$v->name][3]['chia'] = Order::whereBetween('start_date',[$queryWeek[2]->start_t,$queryWeek[2]->end_t])->where('cos_chia', $v->code)->sum('amount') / 2;
                    $orders[$v->name][3]['targettong'] = $t3;
                    $orders[$v->name][3]['tong'] = $orders[$v->name][3]['ds'] + $orders[$v->name][3]['chia'];

                    $orders[$v->name][4]['ds'] = Order::whereBetween('start_date',[$queryWeek[3]->start_t,$queryWeek[3]->end_t])->where('costcenter', $v->code)->sum('amount');
                    $orders[$v->name][4]['chia'] = Order::whereBetween('start_date',[$queryWeek[3]->start_t,$queryWeek[3]->end_t])->where('cos_chia', $v->code)->sum('amount') / 2;
                    $orders[$v->name][4]['targettong'] = $t4;
                    $orders[$v->name][4]['tong'] = $orders[$v->name][4]['ds'] + $orders[$v->name][4]['chia'];
                }

            }
            // dd($value->pluck('code'));
        }
        // dd($orders);
        return array_merge($tong,$groups,$orders);
    }

    function sumasm($warehouse,$start_week, $end_week)
    {
        $end_week1 = date('Y-m-d',strtotime($start_week . " +6 day"));
        $start_week2 = date('Y-m-d',strtotime($start_week . " +7 day"));
        $end_week2 = date('Y-m-d',strtotime($start_week . " +13 day"));
        $start_week3 = date('Y-m-d',strtotime($start_week . " +14 day"));
        $end_week3 = date('Y-m-d',strtotime($start_week . " +20 day"));
        $start_week4 = date('Y-m-d',strtotime($start_week . " +21 day"));

        $costcenter = Costcenter::where('warehouse',$warehouse)->pluck('code');
        $asmdon1 = Order::whereIn('user_id',[272,273,9015])->whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week,$end_week1])->sum('amount');
        $asmdon2 = Order::whereIn('user_id',[272,273,9015])->whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week2,$end_week2])->sum('amount');
        $asmdon3 = Order::whereIn('user_id',[272,273,9015])->whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week3,$end_week3])->sum('amount');
        $asmdon4 = Order::whereIn('user_id',[272,273,9015])->whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week4,$end_week])->sum('amount');

        $huydon1 = Order::whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week,$end_week1])->whereIn('status_order',[0,1])->sum('status_amout');
        $huydon2 = Order::whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week2,$end_week2])->whereIn('status_order',[0,1])->sum('status_amout');
        $huydon3 = Order::whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week3,$end_week3])->whereIn('status_order',[0,1])->sum('status_amout');
        $huydon4 = Order::whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week4,$end_week])->whereIn('status_order',[0,1])->sum('status_amout');

        $h1 = Order::whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week,$end_week1])->where('status_order','>',10)->pluck('status_order');
        $h2 = Order::whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week2,$end_week2])->where('status_order','>',10)->pluck('status_order');
        $h3 = Order::whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week3,$end_week3])->where('status_order','>',10)->pluck('status_order');
        $h4 = Order::whereIn('costcenter',$costcenter)->whereBetween('start_date',[$start_week4,$end_week])->where('status_order','>',10)->pluck('status_order');

        $huychenh1 = Order::whereIn('so_number',$h1)->sum('amount');
        $huychenh2 = Order::whereIn('so_number',$h2)->sum('amount');
        $huychenh3 = Order::whereIn('so_number',$h3)->sum('amount');
        $huychenh4 = Order::whereIn('so_number',$h4)->sum('amount');


        return [$asmdon1,$asmdon2,$asmdon3,$asmdon4,$huydon1 + $huychenh1,$huydon2 +$huychenh2,$huydon3 + $huychenh3,$huydon4 + $huychenh4];
    }

    protected function sumZipNew($targetcost,$weeks,$year) {
        
        $dto = new DateTime();
        if ($weeks[0] > 52) {
            $year = $year - 1;
            
        }
        
        $d1 = $dto->setISODate($year, $weeks[0])->format('Y-m-d');
        $d_week = date('Y-m-d',strtotime($d1 . " +3 day"));
        $c_week = date('Y-m-d',strtotime($d1 . " +6 day"));

        if ($weeks[0] > 52) {
           
            if ($weeks[1] < 52) {
                $year = $year + 1;
            }
        }
        $d2 = $dto->setISODate($year, $weeks[1])->format('Y-m-d');
        $d_week1 = date('Y-m-d',strtotime($d2 . " +3 day"));
        $c_week1 = date('Y-m-d',strtotime($d_week1 . " +3 day"));

        $d3 = $dto->setISODate($year, $weeks[2])->format('Y-m-d');
        $d_week2 = date('Y-m-d',strtotime($d3 . " +3 day"));
        $c_week2 = date('Y-m-d',strtotime($d_week2 . " +3 day"));

        $d4 = $dto->setISODate($year, $weeks[3])->format('Y-m-d');
        $d_week3 = date('Y-m-d',strtotime($d4 . " +3 day"));
        $c_week3 = date('Y-m-d',strtotime($d_week3 . " +3 day"));
      
        $c1 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d1,$c_week])->sum('amount');
        $c2 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d2,$c_week1])->sum('amount');
        $c3 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d3,$c_week2])->sum('amount');
        $c4 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d4,$c_week3])->sum('amount');
        
        $ch1 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d1,$c_week])->pluck('status_order');
        $ch2 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d2,$c_week1])->pluck('status_order');
        $ch3 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d3,$c_week2])->pluck('status_order');
        $ch4 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d4,$c_week3])->pluck('status_order');

        $t1 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',39)->whereDate('close',$c_week)->sum('amount_number');
        $t2 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',39)->whereDate('close',$c_week1)->sum('amount_number');
        $t3 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',39)->whereDate('close',$c_week2)->sum('amount_number');
        $t4 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',39)->whereDate('close',$c_week3)->sum('amount_number');

        $dt1 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d1,$d_week])->sum('amount');
        $dt2 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d2,$d_week1])->sum('amount');
        $dt3 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d3,$d_week2])->sum('amount');
        $dt4 = Order::where('costcenter','ON_MB')->whereBetween('start_date',[$d4,$d_week3])->sum('amount');

        $mnc1 = Order::where('costcenter','ON_MN')->whereBetween('start_date',[$d1,$c_week])->sum('amount');
        $mnc2 = Order::where('costcenter','ON_MN')->whereBetween('start_date',[$d2,$c_week1])->sum('amount');
        $mnc3 = Order::where('costcenter','ON_MN')->whereBetween('start_date',[$d3,$c_week2])->sum('amount');
        $mnc4 = Order::where('costcenter','ON_MN')->whereBetween('start_date',[$d4,$c_week3])->sum('amount');

        $mndt1 = Order::where('costcenter','ON_MN')->whereBetween('start_date',[$d1,$d_week])->sum('amount');
        $mndt2 = Order::where('costcenter','ON_MN')->whereBetween('start_date',[$d2,$d_week1])->sum('amount');
        $mndt3 = Order::where('costcenter','ON_MN')->whereBetween('start_date',[$d3,$d_week2])->sum('amount');
        $mndt4 = Order::where('costcenter','ON_MN')->whereBetween('start_date',[$d4,$d_week3])->sum('amount');

        $mnt1 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',40)->whereDate('close',$c_week)->sum('amount_number');
        $mnt2 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',40)->whereDate('close',$c_week1)->sum('amount_number');
        $mnt3 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',40)->whereDate('close',$c_week2)->sum('amount_number');
        $mnt4 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',40)->whereDate('close',$c_week3)->sum('amount_number');

        $HoChiMinh[] = [ 
            'warehouse' => $targetcost[1]['warehouse'],
            'c1' => $targetcost[1]['c1'],
            'c2' => $targetcost[1]['c2'],
            'c3' => $targetcost[1]['c3'],
            'c4' => $targetcost[1]['c4'],
            'sumc' => $targetcost[1]['sumc'],
            'sumdc1' => $targetcost[1]['sumdc1'],
            'sumdc2' => $targetcost[1]['sumdc2'],
            'sumdc3' => $targetcost[1]['sumdc3'],
            'sumdc4' => $targetcost[1]['sumdc4'],
            'sumdd1' => $targetcost[1]['sumdd1'],
            'sumdd2' => $targetcost[1]['sumdd2'],
            'sumdd3' => $targetcost[1]['sumdd3'],
            'sumdd4' => $targetcost[1]['sumdd4'],
            'sumt'=> $targetcost[1]['sumt'],
            't1' => $targetcost[1]['t1'],
            't2' => $targetcost[1]['t2'],
            't3' => $targetcost[1]['t3'],
            't4' => $targetcost[1]['t4'],

            'asmdon1' => $targetcost[1]['asmdon1'],
            'asmdon2' => $targetcost[1]['asmdon2'],
            'asmdon3' => $targetcost[1]['asmdon3'],
            'asmdon4' => $targetcost[1]['asmdon4'],

            'huydon1' => $targetcost[1]['huydon1'],
            'huydon2' => $targetcost[1]['huydon2'],
            'huydon3' => $targetcost[1]['huydon3'],
            'huydon4' => $targetcost[1]['huydon4'],

            'cold1' => $targetcost[1]['cold1'],
            'cold2' => $targetcost[1]['cold2'],
            'cold3' => $targetcost[1]['cold3'],
            'cold4' => $targetcost[1]['cold4'],
        ];
        $MienNam[] = [ 
            'warehouse' => $targetcost[2]['warehouse'],
            'c1' => $targetcost[2]['c1'],
            'c2' => $targetcost[2]['c2'],
            'c3' => $targetcost[2]['c3'],
            'c4' => $targetcost[2]['c4'],
            'sumc' => $targetcost[2]['sumc'],
            'sumdc1' => $targetcost[2]['sumdc1'],
            'sumdc2' => $targetcost[2]['sumdc2'],
            'sumdc3' => $targetcost[2]['sumdc3'],
            'sumdc4' => $targetcost[2]['sumdc4'],
            'sumdd1' => $targetcost[2]['sumdd1'],
            'sumdd2' => $targetcost[2]['sumdd2'],
            'sumdd3' => $targetcost[2]['sumdd3'],
            'sumdd4' => $targetcost[2]['sumdd4'],
            'sumt'=> $targetcost[2]['sumt'],
            't1' => $targetcost[2]['t1'],
            't2' => $targetcost[2]['t2'],
            't3' => $targetcost[2]['t3'],
            't4' => $targetcost[2]['t4'],

            'asmdon1' => $targetcost[2]['asmdon1'],
            'asmdon2' => $targetcost[2]['asmdon2'],
            'asmdon3' => $targetcost[2]['asmdon3'],
            'asmdon4' => $targetcost[2]['asmdon4'],

            'huydon1' => $targetcost[2]['huydon1'],
            'huydon2' => $targetcost[2]['huydon2'],
            'huydon3' => $targetcost[2]['huydon3'],
            'huydon4' => $targetcost[2]['huydon4'],

            'cold1' => $targetcost[2]['cold1'],
            'cold2' => $targetcost[2]['cold2'],
            'cold3' => $targetcost[2]['cold3'],
            'cold4' => $targetcost[2]['cold4'],
        ];
        $MienBac[] = [ 
            'warehouse' => $targetcost[3]['warehouse'],
            'c1' => $targetcost[3]['c1'],
            'c2' => $targetcost[3]['c2'],
            'c3' => $targetcost[3]['c3'],
            'c4' => $targetcost[3]['c4'],
            'sumc' => $targetcost[3]['sumc'],
            'sumdc1' => $targetcost[3]['sumdc1'],
            'sumdc2' => $targetcost[3]['sumdc2'],
            'sumdc3' => $targetcost[3]['sumdc3'],
            'sumdc4' => $targetcost[3]['sumdc4'],
            'sumdd1' => $targetcost[3]['sumdd1'],
            'sumdd2' => $targetcost[3]['sumdd2'],
            'sumdd3' => $targetcost[3]['sumdd3'],
            'sumdd4' => $targetcost[3]['sumdd4'],
            'sumt'=> $targetcost[3]['sumt'],
            't1' => $targetcost[3]['t1'],
            't2' => $targetcost[3]['t2'],
            't3' => $targetcost[3]['t3'],
            't4' => $targetcost[3]['t4'],

            'huydon1' => $targetcost[3]['huydon1'],
            'huydon2' => $targetcost[3]['huydon2'],
            'huydon3' => $targetcost[3]['huydon3'],
            'huydon4' => $targetcost[3]['huydon4'],

            'asmdon1' => $targetcost[3]['asmdon1'],
            'asmdon2' => $targetcost[3]['asmdon2'],
            'asmdon3' => $targetcost[3]['asmdon3'],
            'asmdon4' => $targetcost[3]['asmdon4'],

            'cold1' => $targetcost[3]['cold1'],
            'cold2' => $targetcost[3]['cold2'],
            'cold3' => $targetcost[3]['cold3'],
            'cold4' => $targetcost[3]['cold4'],
        ];

        $sumMienBac[] = [ 
            'warehouse' => 'Khu Vực Miền Bắc',
            'c1' => $targetcost[0]['c1'] + $targetcost[3]['c1'],
            'c2' => $targetcost[0]['c2'] + $targetcost[3]['c2'],
            'c3' => $targetcost[0]['c3'] + $targetcost[3]['c3'],
            'c4' => $targetcost[0]['c4'] + $targetcost[3]['c4'],
            'sumc' => $targetcost[0]['sumc'] + $targetcost[3]['sumc'],
            'sumdc1' => $targetcost[0]['sumdc1'] + $targetcost[3]['sumdc1'],
            'sumdc2' => $targetcost[0]['sumdc2'] + $targetcost[3]['sumdc2'],
            'sumdc3' => $targetcost[0]['sumdc3'] + $targetcost[3]['sumdc3'],
            'sumdc4' => $targetcost[0]['sumdc4'] + $targetcost[3]['sumdc4'],
            'sumdd1' => $targetcost[0]['sumdd1'] + $targetcost[3]['sumdd1'],
            'sumdd2' => $targetcost[0]['sumdd2'] + $targetcost[3]['sumdd2'],
            'sumdd3' => $targetcost[0]['sumdd3'] + $targetcost[3]['sumdd3'],
            'sumdd4' => $targetcost[0]['sumdd4'] + $targetcost[3]['sumdd4'],
            'sumt'=> $targetcost[0]['sumt'] + $targetcost[3]['sumt'],
            't1' => $targetcost[0]['t1'] + $targetcost[3]['t1'],
            't2' => $targetcost[0]['t2'] + $targetcost[3]['t2'],
            't3' => $targetcost[0]['t3'] + $targetcost[3]['t3'],
            't4' => $targetcost[0]['t4'] + $targetcost[3]['t4'],
        ];
        $sumMienNam[] = [ 
            'warehouse' => 'Khu Vực Miền Nam',
            'c1' => $targetcost[1]['c1'] + $targetcost[2]['c1'],
            'c2' => $targetcost[1]['c2'] + $targetcost[2]['c2'],
            'c3' => $targetcost[1]['c3'] + $targetcost[2]['c3'],
            'c4' => $targetcost[1]['c4'] + $targetcost[2]['c4'],
            'sumc' => $targetcost[1]['sumc'] + $targetcost[2]['sumc'],
            'sumdc1' => $targetcost[1]['sumdc1'] + $targetcost[2]['sumdc1'],
            'sumdc2' => $targetcost[1]['sumdc2'] + $targetcost[2]['sumdc2'],
            'sumdc3' => $targetcost[1]['sumdc3'] + $targetcost[2]['sumdc3'],
            'sumdc4' => $targetcost[1]['sumdc4'] + $targetcost[2]['sumdc4'],
            'sumdd1' => $targetcost[1]['sumdd1'] + $targetcost[2]['sumdd1'],
            'sumdd2' => $targetcost[1]['sumdd2'] + $targetcost[2]['sumdd2'],
            'sumdd3' => $targetcost[1]['sumdd3'] + $targetcost[2]['sumdd3'],
            'sumdd4' => $targetcost[1]['sumdd4'] + $targetcost[2]['sumdd4'],
            'sumt'=> $targetcost[1]['sumt'] + $targetcost[2]['sumt'],
            't1' => $targetcost[1]['t1'] + $targetcost[2]['t1'],
            't2' => $targetcost[1]['t2'] + $targetcost[2]['t2'],
            't3' => $targetcost[1]['t3'] + $targetcost[2]['t3'],
            't4' => $targetcost[1]['t4'] + $targetcost[2]['t4'],
        ];

        $sumOnlineMienBac[] = [ 
            'warehouse' => 'Online',
            'c1' => $c1 - Order::whereIn('so_number', $ch1)->sum('amount'),
            'c2' => $c2 - Order::whereIn('so_number', $ch2)->sum('amount'),
            'c3' => $c3 - Order::whereIn('so_number', $ch3)->sum('amount'),
            'c4' => $c4 - Order::whereIn('so_number', $ch4)->sum('amount'),
            'sumc' => $c1 + $c2 + $c3 + $c4 - Order::whereIn('so_number', $ch1)->sum('amount') - Order::whereIn('so_number', $ch3)->sum('amount') - Order::whereIn('so_number', $ch2)->sum('amount') - Order::whereIn('so_number', $ch4)->sum('amount'),
            'sumdc1' => $c1 - $dt1,
            'sumdc2' => $c2 - $dt2,
            'sumdc3' => $c3 - $dt3,
            'sumdc4' => $c4 - $dt4,
            'sumdd1' => $dt1,
            'sumdd2' => $dt2,
            'sumdd3' => $dt3,
            'sumdd4' => $dt4,
            'sumt'=> $t1 + $t2 + $t3 + $t4,
            't1' => $t1,
            't2' => $t2,
            't3' => $t3,
            't4' => $t4,
        ];

        $sumOnlineMienNam[] = [ 
            'warehouse' => 'Khu Vực Online Miền Nam',
            'c1' => $mnc1,
            'c2' => $mnc2,
            'c3' => $mnc3,
            'c4' => $mnc4,
            'sumc' => $mnc1 + $mnc2 + $mnc3 + $mnc4,
            'sumdc1' => $mnc1 - $mndt1,
            'sumdc2' => $mnc2 - $mndt2,
            'sumdc3' => $mnc3 - $mndt3,
            'sumdc4' => $mnc4 - $mndt4,
            'sumdd1' => $mndt1,
            'sumdd2' => $mndt2,
            'sumdd3' => $mndt3,
            'sumdd4' => $mndt4,
            'sumt'=> $mnt1 + $mnt2 + $mnt3 + $mnt4,
            't1' => $mnt1,
            't2' => $mnt2,
            't3' => $mnt3,
            't4' => $mnt4,
        ];

        $sumZip[] = [ 
            'warehouse' => 'Zip',
            'c1' => $sumMienBac[0]['c1'] + $sumMienNam[0]['c1'] + $mnc1 + $c1 - Order::whereIn('so_number', $ch1)->sum('amount'),
            'c2' => $sumMienBac[0]['c2'] + $sumMienNam[0]['c2'] + $mnc2 + $c2 - Order::whereIn('so_number', $ch2)->sum('amount') ,
            'c3' => $sumMienBac[0]['c3'] + $sumMienNam[0]['c3'] + $mnc3 + $c3 - Order::whereIn('so_number', $ch3)->sum('amount'),
            'c4' => $sumMienBac[0]['c4'] + $sumMienNam[0]['c4'] + $mnc4 + $c4 - Order::whereIn('so_number', $ch4)->sum('amount'),
            'sumc' => $sumMienBac[0]['sumc'] + $sumMienNam[0]['sumc'] + $c1 + $c2 + $c3 + $c4 + $mnc1 + $mnc2 + $mnc3 + $mnc4 - Order::whereIn('so_number', $ch1)->sum('amount') - Order::whereIn('so_number', $ch3)->sum('amount') - Order::whereIn('so_number', $ch2)->sum('amount') - Order::whereIn('so_number', $ch4)->sum('amount'),
            'sumdc1' => $sumMienBac[0]['sumdc1'] + $sumMienNam[0]['sumdc1'] + $mnc1 - $mndt1 + $c1 - $dt1,
            'sumdc2' => $sumMienBac[0]['sumdc2'] + $sumMienNam[0]['sumdc2'] + $mnc2 - $mndt2 + $c2 - $dt2,
            'sumdc3' => $sumMienBac[0]['sumdc3'] + $sumMienNam[0]['sumdc3'] + $mnc3 - $mndt3 + $c3 - $dt3,
            'sumdc4' => $sumMienBac[0]['sumdc4'] + $sumMienNam[0]['sumdc4'] + $mnc4 - $mndt4 + $c4 - $dt4,
            'sumdd1' => $sumMienBac[0]['sumdd1'] + $sumMienNam[0]['sumdd1'] + $mndt1 + $dt1,
            'sumdd2' => $sumMienBac[0]['sumdd2'] + $sumMienNam[0]['sumdd2'] + $mndt2 + $dt2,
            'sumdd3' => $sumMienBac[0]['sumdd3'] + $sumMienNam[0]['sumdd3'] + $mndt3 + $dt3,
            'sumdd4' => $sumMienBac[0]['sumdd4'] + $sumMienNam[0]['sumdd4'] + $mndt4 + $dt4,
            'sumt'=> $sumMienBac[0]['sumt'] + $sumMienNam[0]['sumt'] + $mnt1 + $mnt2 + $mnt3 + $mnt4 + $t1 + $t2 + $t3 + $t4,
            't1' => $sumMienBac[0]['t1'] + $sumMienNam[0]['t1'] + $mnt1 + $t1,
            't2' => $sumMienBac[0]['t2'] + $sumMienNam[0]['t2'] + $mnt2 + $t2,
            't3' => $sumMienBac[0]['t3'] + $sumMienNam[0]['t3'] + $mnt3 + $t3,
            't4' => $sumMienBac[0]['t4'] + $sumMienNam[0]['t4'] + $mnt4 + $t4,
        ];

        return array_merge($sumZip,$sumOnlineMienBac,$HoChiMinh,$MienNam,$MienBac);
    }

    protected function targetByAmountSR($items, $weeks,$year)
    {
       $dto = new DateTime();
        
         // if($weeks > 52){ // if($weeks > 52){
        //     $year = $year - 1;
        // }
        return $items->map(function($item,$key) use ($weeks,$dto,$year){
            return $weeks->map(function($week) use ($item,$dto,$year,$key){
                if($week > 52){
                    $year = $year - 1;
                }else{
                    $year = $year;
                }
                $dto->setISODate($year, $week);
                $ret['week_start'] = $dto->format('Y-m-d');
                $dto->modify('+6 days');
                $ret['week_end'] = $dto->format('Y-m-d');
                $targetUser = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$key)->first('amount_number');
                $date  = $this->getDatesFromRange($ret['week_start'],$ret['week_end']);
                $sumd = Order::where('costcenter','!=','ON_MB')->where('user_id',$key)->whereBetween('start_date',[$date[0], $date[3]])->get();
                $sumc = Order::where('costcenter','!=','ON_MB')->where('user_id',$key)->whereBetween('start_date',[$date[4], $date[6]])->get();
                return [
                    'name' => $item,
                    'w' => $week,
                    'c'=> isset($item[$week]) ? $item[$week] : 0 ,
                    'sumd' => $sumd->sum('amount'),
                    'sumc' => $sumc->sum('amount'),
                ];
            });
        });
    }
    protected function targetAllSR($key,$weeks,$year,$type)
    {
        $dto = new DateTime();
        if($weeks > 52){
            $year = $year - 1;
        }else{
            $year = $year;
        }
        $dto->setISODate($year, $weeks);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        if($type == 1) {
            $targetshowroom = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('number');
            if($targetshowroom == null) {
                $target = 0 ;
            }else{
                $target = json_decode($targetshowroom)->number;
            }
        }
        if($type == 2) {
            $targetshowroom = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('kh15_number');
            if($targetshowroom == null) {
                $target = 0 ;
            }else{
                $target = json_decode($targetshowroom)->kh15_number;
            }
        }
        if($type == 3) {
            $targetshowroom = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('order_number');
            if($targetshowroom == null) {
                $target = 0 ;
            }else{
                $target = json_decode($targetshowroom)->order_number;
            }
        }
        if($type == 4) {
            $targetshowroom = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('amount_number');
            if($targetshowroom == null) {
                $target = 00000000 ;
            }else{
                $target = json_decode($targetshowroom)->amount_number;
            }
        }
        return $target;
    }
    protected function targetNocontact($key,$weeks,$year,$type)
    {
        $dto = new DateTime();
        
        $dto->setISODate($year, $weeks);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        if($type == 1) {
            $targetshowroom = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('number');
            if($targetshowroom == null) {
                $target = 0 ;
            }else{
                $target = json_decode($targetshowroom)->number;
            }
        }
        if($type == 2) {
            $targetshowroom = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('kh15_number');
            if($targetshowroom == null) {
                $target = 0 ;
            }else{
                $target = json_decode($targetshowroom)->kh15_number;
            }
        }
        if($type == 3) {
            $targetshowroom = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('order_number');
            if($targetshowroom == null) {
                $target = 0 ;
            }else{
                $target = json_decode($targetshowroom)->order_number;
            }
        }
        if($type == 4) {
            $targetshowroom = SalesTarget::where('targetable_type','App\User')->where('targetable_id',$key)->whereDate('close',$ret['week_end'])->first('amount_number');
            if($targetshowroom == null) {
                $target = 00000000 ;
            }else{
                $target = json_decode($targetshowroom)->amount_number;
            }
        }
        return $target;
    }

    protected function sumZip($targetcost) {
        $sumMienBac[] = [ 
            'warehouse' => 'Khu Vực Miền Bắc',
            'c1' => $targetcost[0]['c1'] + $targetcost[3]['c1'],
            'c2' => $targetcost[0]['c2'] + $targetcost[3]['c2'],
            'c3' => $targetcost[0]['c3'] + $targetcost[3]['c3'],
            'c4' => $targetcost[0]['c4'] + $targetcost[3]['c4'],
            'sumc' => $targetcost[0]['sumc'] + $targetcost[3]['sumc'],
            'sumdc1' => $targetcost[0]['sumdc1'] + $targetcost[3]['sumdc1'],
            'sumdc2' => $targetcost[0]['sumdc2'] + $targetcost[3]['sumdc2'],
            'sumdc3' => $targetcost[0]['sumdc3'] + $targetcost[3]['sumdc3'],
            'sumdc4' => $targetcost[0]['sumdc4'] + $targetcost[3]['sumdc4'],
            'sumdd1' => $targetcost[0]['sumdd1'] + $targetcost[3]['sumdd1'],
            'sumdd2' => $targetcost[0]['sumdd2'] + $targetcost[3]['sumdd2'],
            'sumdd3' => $targetcost[0]['sumdd3'] + $targetcost[3]['sumdd3'],
            'sumdd4' => $targetcost[0]['sumdd4'] + $targetcost[3]['sumdd4'],
            'sumt'=> $targetcost[0]['sumt'] + $targetcost[3]['sumt'],
            't1' => $targetcost[0]['t1'] + $targetcost[3]['t1'],
            't2' => $targetcost[0]['t2'] + $targetcost[3]['t2'],
            't3' => $targetcost[0]['t3'] + $targetcost[3]['t3'],
            't4' => $targetcost[0]['t4'] + $targetcost[3]['t4'],
        ];
        $sumMienNam[] = [ 
            'warehouse' => 'Khu Vực Miền Nam',
            'c1' => $targetcost[1]['c1'] + $targetcost[2]['c1'],
            'c2' => $targetcost[1]['c2'] + $targetcost[2]['c2'],
            'c3' => $targetcost[1]['c3'] + $targetcost[2]['c3'],
            'c4' => $targetcost[1]['c4'] + $targetcost[2]['c4'],
            'sumc' => $targetcost[1]['sumc'] + $targetcost[2]['sumc'],
            'sumdc1' => $targetcost[1]['sumdc1'] + $targetcost[2]['sumdc1'],
            'sumdc2' => $targetcost[1]['sumdc2'] + $targetcost[2]['sumdc2'],
            'sumdc3' => $targetcost[1]['sumdc3'] + $targetcost[2]['sumdc3'],
            'sumdc4' => $targetcost[1]['sumdc4'] + $targetcost[2]['sumdc4'],
            'sumdd1' => $targetcost[1]['sumdd1'] + $targetcost[2]['sumdd1'],
            'sumdd2' => $targetcost[1]['sumdd2'] + $targetcost[2]['sumdd2'],
            'sumdd3' => $targetcost[1]['sumdd3'] + $targetcost[2]['sumdd3'],
            'sumdd4' => $targetcost[1]['sumdd4'] + $targetcost[2]['sumdd4'],
            'sumt'=> $targetcost[1]['sumt'] + $targetcost[2]['sumt'],
            't1' => $targetcost[1]['t1'] + $targetcost[2]['t1'],
            't2' => $targetcost[1]['t2'] + $targetcost[2]['t2'],
            't3' => $targetcost[1]['t3'] + $targetcost[2]['t3'],
            't4' => $targetcost[1]['t4'] + $targetcost[2]['t4'],
        ];
        $sumZip[] = [ 
            'warehouse' => 'Zip',
            'c1' => $sumMienBac[0]['c1'] + $sumMienNam[0]['c1'],
            'c2' => $sumMienBac[0]['c2'] + $sumMienNam[0]['c2'],
            'c3' => $sumMienBac[0]['c3'] + $sumMienNam[0]['c3'],
            'c4' => $sumMienBac[0]['c4'] + $sumMienNam[0]['c4'],
            'sumc' => $sumMienBac[0]['sumc'] + $sumMienNam[0]['sumc'],
            'sumdc1' => $sumMienBac[0]['sumdc1'] + $sumMienNam[0]['sumdc1'],
            'sumdc2' => $sumMienBac[0]['sumdc2'] + $sumMienNam[0]['sumdc2'],
            'sumdc3' => $sumMienBac[0]['sumdc3'] + $sumMienNam[0]['sumdc3'],
            'sumdc4' => $sumMienBac[0]['sumdc4'] + $sumMienNam[0]['sumdc4'],
            'sumdd1' => $sumMienBac[0]['sumdd1'] + $sumMienNam[0]['sumdd1'],
            'sumdd2' => $sumMienBac[0]['sumdd2'] + $sumMienNam[0]['sumdd2'],
            'sumdd3' => $sumMienBac[0]['sumdd3'] + $sumMienNam[0]['sumdd3'],
            'sumdd4' => $sumMienBac[0]['sumdd4'] + $sumMienNam[0]['sumdd4'],
            'sumt'=> $sumMienBac[0]['sumt'] + $sumMienNam[0]['sumt'],
            't1' => $sumMienBac[0]['t1'] + $sumMienNam[0]['t1'],
            't2' => $sumMienBac[0]['t2'] + $sumMienNam[0]['t2'],
            't3' => $sumMienBac[0]['t3'] + $sumMienNam[0]['t3'],
            't4' => $sumMienBac[0]['t4'] + $sumMienNam[0]['t4'],
        ];
        $HoChiMinh[] = [ 
            'warehouse' => $targetcost[1]['warehouse'],
            'c1' => $targetcost[1]['c1'],
            'c2' => $targetcost[1]['c2'],
            'c3' => $targetcost[1]['c3'],
            'c4' => $targetcost[1]['c4'],
            'sumc' => $targetcost[1]['sumc'],
            'sumdc1' => $targetcost[1]['sumdc1'],
            'sumdc2' => $targetcost[1]['sumdc2'],
            'sumdc3' => $targetcost[1]['sumdc3'],
            'sumdc4' => $targetcost[1]['sumdc4'],
            'sumdd1' => $targetcost[1]['sumdd1'],
            'sumdd2' => $targetcost[1]['sumdd2'],
            'sumdd3' => $targetcost[1]['sumdd3'],
            'sumdd4' => $targetcost[1]['sumdd4'],
            'sumt'=> $targetcost[1]['sumt'],
            't1' => $targetcost[1]['t1'],
            't2' => $targetcost[1]['t2'],
            't3' => $targetcost[1]['t3'],
            't4' => $targetcost[1]['t4'],
        ];
        $MienNam[] = [ 
            'warehouse' => $targetcost[2]['warehouse'],
            'c1' => $targetcost[2]['c1'],
            'c2' => $targetcost[2]['c2'],
            'c3' => $targetcost[2]['c3'],
            'c4' => $targetcost[2]['c4'],
            'sumc' => $targetcost[2]['sumc'],
            'sumdc1' => $targetcost[2]['sumdc1'],
            'sumdc2' => $targetcost[2]['sumdc2'],
            'sumdc3' => $targetcost[2]['sumdc3'],
            'sumdc4' => $targetcost[2]['sumdc4'],
            'sumdd1' => $targetcost[2]['sumdd1'],
            'sumdd2' => $targetcost[2]['sumdd2'],
            'sumdd3' => $targetcost[2]['sumdd3'],
            'sumdd4' => $targetcost[2]['sumdd4'],
            'sumt'=> $targetcost[2]['sumt'],
            't1' => $targetcost[2]['t1'],
            't2' => $targetcost[2]['t2'],
            't3' => $targetcost[2]['t3'],
            't4' => $targetcost[2]['t4'],
        ];
        $MienBac[] = [ 
            'warehouse' => $targetcost[3]['warehouse'],
            'c1' => $targetcost[3]['c1'],
            'c2' => $targetcost[3]['c2'],
            'c3' => $targetcost[3]['c3'],
            'c4' => $targetcost[3]['c4'],
            'sumc' => $targetcost[3]['sumc'],
            'sumdc1' => $targetcost[3]['sumdc1'],
            'sumdc2' => $targetcost[3]['sumdc2'],
            'sumdc3' => $targetcost[3]['sumdc3'],
            'sumdc4' => $targetcost[3]['sumdc4'],
            'sumdd1' => $targetcost[3]['sumdd1'],
            'sumdd2' => $targetcost[3]['sumdd2'],
            'sumdd3' => $targetcost[3]['sumdd3'],
            'sumdd4' => $targetcost[3]['sumdd4'],
            'sumt'=> $targetcost[3]['sumt'],
            't1' => $targetcost[3]['t1'],
            't2' => $targetcost[3]['t2'],
            't3' => $targetcost[3]['t3'],
            't4' => $targetcost[3]['t4'],
        ];

        return array_merge($sumZip);
    }

    public function yearAmount(Request $request)
    {
        if($request->zip == null){
            $calender = DB::table('calendar')->where('p_i',$request->sale)->orderBy('i','asc')->get();
        }else{
            $calender = DB::table('calendar')->where('z',$request->zip)->get();
        }
        $costcenter = DB::table('costcenters')->orderBy('bc','asc')->get();
        $sumHaNoi = 0;$targetHaNoi = 0;
        $sumMienBac = 0;$targetMienBac = 0;
        $sumHoChiMinh = 0;$targetHoChiMinh = 0;
        $sumMienNam = 0;$targetMienNam = 0;
        foreach ($costcenter as $code) {
            $targetSR = 0;$targetZip = 0;
            $sumZip = 0;$sumSR = 0;
            if ($code->warehouse == 'Hà Nội') {
                $hanoi_id[] = $code->id;
                $hanoi_code[] = $code->code;
            }
            if ($code->warehouse == 'Hồ Chí Minh') {
                $hochiminh_id[] = $code->id;
                $hochiminh_code[] = $code->code;
            }
            if ($code->warehouse == 'Miền Bắc') {
                $mienbac_id[] = $code->id;
                $mienbac_code[] = $code->code;
            }
            if ($code->warehouse == 'Miền Nam') {
                $miennam_id[] = $code->id;
                $miennam_code[] = $code->code;
            }

            $dangnhap = \Auth::user()->id;
            $dt = Carbon::now('Asia/Ho_Chi_Minh');

            foreach ($calender as $key => $value) {
                $order['Zip'][$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->sum('amount');
                $sumZip += $order['Zip'][$key+1][0];
                $order['Zip'][0][0] = $sumZip;

                $ss1 = strtotime($value->start);
                if ($dangnhap != 9003 && $dangnhap != 9074) {
                    if (strtotime($dt->toDateString()) <  $ss1) { 
                        $role_watch = \DB::table('roles_target')->where('p',$value->p)->where('z',$value->z)->where('user_id',$dangnhap)->get();
                        if ($role_watch->isEmpty()) {
                            $order['Zip'][$key+1][1] = 0;
                        }else{
                            $order['Zip'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                        }
                  
                    }else{
                        $order['Zip'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                    }
                }else{
                    $order['Zip'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                }
                $targetZip += $order['Zip'][$key+1][1];
                $order['Zip'][0][1] = $targetZip;

                if ($code->warehouse == 'Hà Nội') {
                    $hanoi_order[$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->where('costcenter',$code->code)->sum('amount');
                    $hanoi_order[$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$code->id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');
                    $order['Hà Nội'][$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->whereIn('costcenter',$hanoi_code)->sum('amount');

                    
                    $ss1 = strtotime($value->start);
                    if ($dangnhap != 9003 && $dangnhap != 9074) {
                        if (strtotime($dt->toDateString()) <  $ss1) { 
                            $role_watch = \DB::table('roles_target')->where('p',$value->p)->where('z',$value->z)->where('user_id',$dangnhap)->get();
                            if ($role_watch->isEmpty()) {
                                $order['Hà Nội'][$key+1][1] = 0;
                            }else{
                                $order['Hà Nội'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$hanoi_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                            }
                      
                        }else{
                            $order['Hà Nội'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$hanoi_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                        }
                    }else{
                        $order['Hà Nội'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$hanoi_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                    }
                    $targetHaNoi += $hanoi_order[$key+1][1];
                    $sumHaNoi += $hanoi_order[$key+1][0];
                }

                $order['Hà Nội'][0][1] = $targetHaNoi;
                $order['Hà Nội'][0][0] = $sumHaNoi;

                if ($code->warehouse == 'Hồ Chí Minh') {
                    $hochiminh_order[$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->where('costcenter',$code->code)->sum('amount');
                    $hochiminh_order[$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$code->id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');
                    $order['Hồ Chí Minh'][$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->whereIn('costcenter',$hochiminh_code)->sum('amount');

                    $ss1 = strtotime($value->start);
                    if ($dangnhap != 9003 && $dangnhap != 9074) {
                        if (strtotime($dt->toDateString()) <  $ss1) { 
                            $role_watch = \DB::table('roles_target')->where('p',$value->p)->where('z',$value->z)->where('user_id',$dangnhap)->get();
                            if ($role_watch->isEmpty()) {
                                $order['Hồ Chí Minh'][$key+1][1] = 0;
                            }else{
                                $order['Hồ Chí Minh'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$hochiminh_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                            }
                      
                        }else{
                            $order['Hồ Chí Minh'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$hochiminh_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                        }
                    }else{
                        $order['Hồ Chí Minh'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$hochiminh_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                    }
                    
                    $targetHoChiMinh += $hochiminh_order[$key+1][1];
                    $sumHoChiMinh += $hochiminh_order[$key+1][0];
                }

                $order['Hồ Chí Minh'][0][1] = $targetHoChiMinh;
                $order['Hồ Chí Minh'][0][0] = $sumHoChiMinh;

                if ($code->warehouse == 'Miền Nam') {
                    $miennam_order[$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->where('costcenter',$code->code)->sum('amount');
                    $miennam_order[$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$code->id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');
                    $order['Miền Nam'][$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->whereIn('costcenter',$miennam_code)->sum('amount');

                    $ss1 = strtotime($value->start);
                    if ($dangnhap != 9003 && $dangnhap != 9074) {
                        if (strtotime($dt->toDateString()) <  $ss1) { 
                            $role_watch = \DB::table('roles_target')->where('p',$value->p)->where('z',$value->z)->where('user_id',$dangnhap)->get();
                            if ($role_watch->isEmpty()) {
                                $order['Miền Nam'][$key+1][1] = 0;
                            }else{
                                $order['Miền Nam'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$miennam_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                            }
                      
                        }else{
                            $order['Miền Nam'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$miennam_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                        }
                    }else{
                        $order['Miền Nam'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$miennam_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                    }
                    $targetMienNam += $miennam_order[$key+1][1];
                    $sumMienNam += $miennam_order[$key+1][0];
                }

                $order['Miền Nam'][0][1] = $targetMienNam;
                $order['Miền Nam'][0][0] = $sumMienNam;

                if ($code->warehouse == 'Miền Bắc') {
                    $mienbac_order[$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->where('costcenter',$code->code)->sum('amount');
                    $mienbac_order[$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$code->id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');
                    $order['Miền Bắc'][$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->whereIn('costcenter',$mienbac_code)->sum('amount');

                    $ss1 = strtotime($value->start);
                    if ($dangnhap != 9003 && $dangnhap != 9074) {
                        if (strtotime($dt->toDateString()) <  $ss1) { 
                            $role_watch = \DB::table('roles_target')->where('p',$value->p)->where('z',$value->z)->where('user_id',$dangnhap)->get();
                            if ($role_watch->isEmpty()) {
                                $order['Miền Bắc'][$key+1][1] = 0;
                            }else{
                                $order['Miền Bắc'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$mienbac_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                            }
                      
                        }else{
                            $order['Miền Bắc'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$mienbac_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                        }
                    }else{
                        $order['Miền Bắc'][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereIn('targetable_id',$mienbac_id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                    }
                    $targetMienBac += $mienbac_order[$key+1][1];
                    $sumMienBac += $mienbac_order[$key+1][0];
                }

                $order['Miền Bắc'][0][1] = $targetMienBac;
                $order['Miền Bắc'][0][0] = $sumMienBac;

                $order[$code->name][$key+1][0] = Order::whereBetween('start_date',[$value->start,$value->end])->where('costcenter',$code->code)->sum('amount');
                $sumSR += $order[$code->name][$key+1][0];
                $order[$code->name][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$code->id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                $ss1 = strtotime($value->start);
                if ($dangnhap != 9003 && $dangnhap != 9074) {
                    if (strtotime($dt->toDateString()) <  $ss1) { 
                        $role_watch = \DB::table('roles_target')->where('p',$value->p)->where('z',$value->z)->where('user_id',$dangnhap)->get();
                        if ($role_watch->isEmpty()) {
                            $order[$code->name][$key+1][1] = 0;
                        }else{
                            $order[$code->name][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereBetween('close',[$value->start,$value->end])->where('targetable_id',$code->id)->sum('amount_number');

                        }
                  
                    }else{
                        $order[$code->name][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                    }
                }else{
                    $order[$code->name][$key+1][1] = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$code->id)->whereBetween('close',[$value->start,$value->end])->sum('amount_number');

                }

                $targetSR += $order[$code->name][$key+1][1];
            }
            if ($sumSR == 0) {
                unset($order[$code->name]);
            }else{
                $order[$code->name][0][0] = $sumSR;
                $order[$code->name][0][1] = $targetSR;
            }
        }
        return $order;
    }

    public function showtotalproduct(Request $request)
    {
        // dd($request->all());
        $idP1=[];$idP2=[];$idP3=[];$idP4=[];
        $dal1 = 0;$ivy1 = 0;$iri1 = 0;$khact1=0;$targetiv4 = 1;$targetiv2 = 1;$targetiv3 = 1;$targetiv1 = 1;
        $dal2 = 0;$ivy2 = 0;$iri2 = 0;$khact2=0;$targetda1 = 1;$targetda2 = 1;$targetda3 = 1;$targetda4 = 1; 
        $dal3 = 0;$ivy3 = 0;$iri3 = 0;$khact3=0;$targetir1 = 1;$targetir2 = 1;$targetir3 = 1;$targetir4 = 1; 
        $dal4 = 0;$ivy4 = 0;$iri4 = 0;$khact4=0;$targetk1 = 1;$targetk2 = 1;$targetk3 = 1;$targetk4 = 1; 
        $e1 = date('Y-m-d', strtotime($request->data['date'][0]. ' + 6 days'));
        $t2 = date('Y-m-d', strtotime($request->data['date'][0]. ' + 7 days'));
        $t3 = date('Y-m-d', strtotime($t2. ' + 7 days'));
        $e2 = date('Y-m-d', strtotime($t2. ' + 6 days'));
        $t4 = date('Y-m-d', strtotime($t3. ' + 7 days'));
        $e3 = date('Y-m-d', strtotime($t3. ' + 6 days'));
        $e4 = date('Y-m-d', strtotime($t4. ' + 6 days'));

        $idCostcenter = Costcenter::where('name',$request->data['costcenter'])->first();
        $target1 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$idCostcenter['id'])->where('close',$e1)->first();
        if(!empty($target1)){
            $target2 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$idCostcenter['id'])->where('close',$e2)->first();
            $target3 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$idCostcenter['id'])->where('close',$e3)->first();
            $target4 = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$idCostcenter['id'])->where('close',$e4)->first();
            $targetiv4 = $target4['ivy'];$targetiv2 = $target2['ivy'];$targetiv3 = $target3['ivy'];$targetiv1 = $target1['ivy'];
            $targetda1 = $target1['dahlia'];$targetda2 = $target2['dahlia'];$targetda3 = $target3['dahlia'];$targetda4 = $target4['dahlia']; 
            $targetir1 = $target1['iris'];$targetir2 = $target2['iris'];$targetir3 = $target3['iris'];$targetir4 = $target4['iris']; 
            $targetk1 = $target1['khac'];$targetk2 = $target2['khac'];$targetk3 = $target3['khac'];$targetk4 = $target4['khac']; 
        }
        $idcontacts = \DB::table('model_has_costcenters')->where('costcenter_id',$idCostcenter['id'])->where('model_type','NoiThatZip\Contact\Models\Contact')->pluck('model_id');
        $idContact = Contact::where('loai',0)->whereBetween('start_date',[$request->data['date'][0],$e4])->whereIn('id',$idcontacts)->pluck('id');
        $idProducs = \DB::table('product_lines')->where('productable_type','NoiThatZip\Contact\Models\Contact')->whereIn('productable_id',$idContact)->pluck('product_id');

        $idContactt1 = Contact::where('loai',0)->whereBetween('start_date',[$request->data['date'][0],$e1])->whereIn('id',$idcontacts)->pluck('id');
        foreach ($idContactt1 as $k => $v) {
            $getId = \DB::table('product_lines')->where('productable_type','NoiThatZip\Contact\Models\Contact')->where('productable_id',$v)->first();
            $idP1[] = $getId->product_id;
        }
        if (count($idP1) > 0) {
            foreach ($idP1 as $key => $value) {
                $checkd = \DB::table('products')->where('id',$value)->where('groups',1)->get();
                if($checkd->isNotEmpty()){
                    $dal1 += 1;
                }
                $checki = \DB::table('products')->where('id',$value)->where('groups',2)->get();
                if($checki->isNotEmpty()){
                    $iri1 += 1;
                }
                $checkiv = \DB::table('products')->where('id',$value)->where('groups',29)->get();
                if($checkiv->isNotEmpty()){
                    $ivy1 += 1;
                }
            }
        }
        $khact1 = count($idP1) - $dal1 - $iri1 -$ivy1;

        $idContactt2 = Contact::where('loai',0)->whereBetween('start_date',[$t2,$e2])->whereIn('id',$idcontacts)->pluck('id');
        foreach ($idContactt2 as $k => $v) {
            $getId = \DB::table('product_lines')->where('productable_type','NoiThatZip\Contact\Models\Contact')->where('productable_id',$v)->first();
            $idP2[] = $getId->product_id;
        }
        if (count($idP2) > 0) {
            foreach ($idP2 as $key => $value) {
                $checkd = \DB::table('products')->where('id',$value)->where('groups',1)->get();
                if($checkd->isNotEmpty()){
                    $dal2 += 1;
                }
                $checki = \DB::table('products')->where('id',$value)->where('groups',2)->get();
                if($checki->isNotEmpty()){
                    $iri2 += 1;
                }
                $checkiv = \DB::table('products')->where('id',$value)->where('groups',29)->get();
                if($checkiv->isNotEmpty()){
                    $ivy2 += 1;
                }
            }
        }
        $khact2 = count($idP2) - $dal2 - $iri2 - $ivy2;

        $idContactt3 = Contact::where('loai',0)->whereBetween('start_date',[$t3,$e3])->whereIn('id',$idcontacts)->pluck('id');
        foreach ($idContactt3 as $k => $v) {
            $getId = \DB::table('product_lines')->where('productable_type','NoiThatZip\Contact\Models\Contact')->where('productable_id',$v)->first();
            $idP3[] = $getId->product_id;
        }
        if (count($idP3) > 0) {
            foreach ($idP3 as $key => $value) {
                $checkd = \DB::table('products')->where('id',$value)->where('groups',1)->get();
                if($checkd->isNotEmpty()){
                    $dal3 += 1;
                }
                $checki = \DB::table('products')->where('id',$value)->where('groups',2)->get();
                if($checki->isNotEmpty()){
                    $iri3 += 1;
                }
                $checkiv = \DB::table('products')->where('id',$value)->where('groups',29)->get();
                if($checkiv->isNotEmpty()){
                    $ivy3 += 1;
                }
            }
            $khact3 = count($idP3) - $dal3 - $iri3 - $ivy3;
        }

        $idContactt4 = Contact::where('loai',0)->whereBetween('start_date',[$t4,$e4])->whereIn('id',$idcontacts)->pluck('id');
        foreach ($idContactt4 as $k => $v) {
            $getId = \DB::table('product_lines')->where('productable_type','NoiThatZip\Contact\Models\Contact')->where('productable_id',$v)->first();
            $idP4[] = $getId->product_id;
        }
        if (count($idP4) > 0) {
            foreach ($idP4 as $key => $value) {
                $checkd = \DB::table('products')->where('id',$value)->where('groups',1)->get();
                if($checkd->isNotEmpty()){
                    $dal4 += 1;
                }
                $checki = \DB::table('products')->where('id',$value)->where('groups',2)->get();
                if($checki->isNotEmpty()){
                    $iri4 += 1;
                }
                $checkiv = \DB::table('products')->where('id',$value)->where('groups',29)->get();
                if($checkiv->isNotEmpty()){
                    $ivy4 += 1;
                }
            }
        }
        $khact4 = count($idP4) - $dal4 - $iri4 - $ivy4;
        $khacp = $khact4 + $khact3 + $khact2 + $khact1;

        return [
            'daliap' => $dal4 + $dal3 + $dal2 + $dal1,'ivyp' => $ivy1 + $ivy3 + $ivy2 + $ivy4,'irisp' => $iri4 + $iri3 + $iri2 + $iri1,
            'khacp' => $khacp,'targetda' => $targetda1 + $targetda2 + $targetda3 + $targetda4,
            'targetiv' => $targetiv1 + $targetiv2 + $targetiv3 + $targetiv4,
            'targetir' => $targetir1 + $targetir2 + $targetir3 + $targetir4,
            'targetk' => $targetk1 + $targetk2 + $targetk3 + $targetk4,
            'daliat1' => $dal1,'ivt1' => $ivy1,'irist1' => $iri1,'khact1' => $khact1,'targetda1' => $targetda1,'targetiv1' => $targetiv1,'targetir1' => $targetir1,'targetk4' => $targetk4,
            'daliat2' => $dal2,'ivt2' => $ivy2,'irist2' => $iri2,'khact2' => $khact2,'targetda2' => $targetda2,'targetiv2' => $targetiv2,'targetir3' => $targetir3,'targetk3' => $targetk3,
            'daliat3' => $dal3,'ivt3' => $ivy3,'irist3' => $iri3,'khact3' => $khact3,'targetda3' => $targetda3,'targetiv3' => $targetiv3,'targetir2' => $targetir2,'targetk2' => $targetk2,
            'daliat4' => $dal4,'ivt4' => $ivy4,'irist4' => $iri4,'khact4' => $khact4,'targetda4' => $targetda4,'targetiv4' => $targetiv4,'targetir4' => $targetir4,'targetk1' => $targetk1,
        ];
    }

    public function inputtarget(Request $request)
    {
        $calender = \DB::table('calendar')->whereDate('end',$request->dates[1])->first();
        $queryWeek = \DB::table('calendar_t')->where('id_p',$calender->id)->get();
        if(\Auth::user()->id == 9003 || \Auth::user()->id == 9365){
            $new = new Target;
            $new->date = $queryWeek[$request->week - 1]->end_t;
            $new->amount = $request->amount;
            $new->id_showroom = 39;
            $new->save();
        }
        
    }
}