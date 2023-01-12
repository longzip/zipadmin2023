<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\CV;
use App\User;
use Carbon\Carbon;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\TuyenDung;
use App\NghiPhep;

class CVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (\Auth::user()->hasRole('ctv')) {
            $cv = CV::whereBetween('time',$request->dates)->where('user_id',\Auth::user()->id)->get();
        }else{
            $cv = CV::whereBetween('time',$request->dates)->get();
        }
        
        return $cv->map(function ($value){
        	$td = TuyenDung::find($value->tuyendung);
    		$showroom['name'] = '';
        	if ($td->showroom > 0) {
    			$showroom = Costcenter::find($td->showroom)->first();
    		}
            $mnv = '';
    		if($value->status == 3){
                $mnv = User::where('email',$value->email)->first()->username;
            }
			$tuyendung['id'] = $td->id;
            $date1 = Carbon::now('Asia/Ho_Chi_Minh')->toDateString(); 
            $date2 = date('Y-m-d',strtotime($value->time)); 
    		$tuyendung['name'] = $td->vitri.' '.$showroom['name'];
        	return (object)[
        		'bir' => $value->bir,
				'email' => $value->email,
				'bophan' => $value->bophan,
				'file' => $value->file,
                'id' => $value->id,
				'date' => $date1 === $date2 ? 1 : 0,
				'name' => $value->name,
				'note' => $value->note,
				'phone' => $value->phone,
				'time' => $value->time,
                'tuyendung' => $tuyendung,
				'status' => $value->status,
                'td' => $value->tuyendung,
                'hen' => $value->hen,
                'dilam' => $value->dilam,
                'nghilam' => $value->nghilam,
                'khong' => $value->khong,
                'mnv' => $mnv,
                'create' => $value->user_id,
				'dx' => $td->user_id,
                'ctv' => User::find($value->user_id)->name,
				'login' => \Auth::user()->id,
        	];
        })->groupBy(function ($value){
        	return $value->bophan;
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
    	if($r->hasFile('file')){
            $file = $r->file('file');
            $check_file = $file->extension();
            $duoi = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            $files = str_random(2)."_".$name;
            while (file_exists("uploads/ctv/".$files)) {
                $files = str_random(2)."_".$name;
            }
            $file->move("uploads/ctv",$files);
        }else{
            $files = null;
        }
        $new = new CV;
        $new->tuyendung = $r->tuyendung; 
        $new->time = $r->time; 
        $new->bophan = $r->bophan; 
        $new->phone = $r->phone; 
        $new->bir = $r->bir; 
        $new->note = $r->note; 
        $new->email = $r->email; 
        $new->name = $r->name; 
        $new->user_id = \Auth::user()->id;
        $new->file = $files;
        $new->save();
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
    public function update(Request $r, $id)
    {
        $new = CV::find($id);
        $new->tuyendung = $r->tuyendung['id']; 
        $new->time = $r->time; 
        $new->bophan = $r->bophan; 
        $new->phone = $r->phone; 
        $new->bir = $r->bir; 
        $new->note = $r->note; 
        $new->email = $r->email; 
        if($r->hasFile('file')){
            $file = $r->file('file');
            $check_file = $file->extension();
            $duoi = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            $files = str_random(2)."_".$name;
            while (file_exists("uploads/ctv/".$files)) {
                $files = str_random(2)."_".$name;
            }
            $file->move("uploads/ctv",$files);
	        $new->file = $files;
        }
        $new->name = $r->name; 
        // dd($new);
        $new->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CV::find($id)->delete();
    }

    public function sttcv(Request $r)
    {
        $new = CV::find($r['id']);
        $tuyendung = TuyenDung::find($new['tuyendung']);
        if ($r->stt < 5) {
            $new->status = $r->stt; 
        }
        if ($r->stt == 5) {
            $new->hen = $r->date; 
        }
        if ($r->stt == 6) {
            $new->nghilam = $r->date; 
        }
        if ($r->stt == 8) {
            $new->khong = $r->date; 
        }
        if ($r->stt == 7) {
            $new->dilam = $r->date; 
            // $user = User::create([
            //     'name' => $new->name,
            //     'email' => $r['email'],
            //     'password' => Hash::make(123456),
            //     'username' => User::all()->filter(function ($value, $key) {
            //         return $value->username > 2;
            //     })->pluck('username')->max() + 1,
            // ]);
            // $new->email = $r['email'];
            // $user->syncRoles([$tuyendung->vitri]);
            // $user->syncCostcenters(Costcenter::where('id',$tuyendung->showroom)->pluck('id'));
        }
        $new->save();
    }

    public function baocaocv(Request $request)
    {
        if($request->week == 0){
            $dt = Carbon::now('Asia/Ho_Chi_Minh');
            $date = \DB::table('calendar_t')->whereDate('start_t','<=',$dt->toDateString())->whereDate('end_t','>=',$dt->toDateString())->first();
        }else{
            $date = \DB::table('calendar_t')->where('id_table',$request->week)->first();
        }
        $dates = [$date->start_t,$date->end_t];
// return $dates;
        $t2 = $date->start_t;
        $cn = $date->end_t;
        $t3 = date ( 'Y-m-j' , strtotime('+1 day' , strtotime($date->start_t)) );
        $t4 = date ( 'Y-m-j' , strtotime('+2 day' , strtotime($date->start_t)) );
        $t5 = date ( 'Y-m-j' , strtotime('+3 day' , strtotime($date->start_t)) );
        $t6 = date ( 'Y-m-j' , strtotime('+4 day' , strtotime($date->start_t)) );
        $t7 = date ( 'Y-m-j' , strtotime('+5 day' , strtotime($date->start_t)) );
        $cv = CV::whereBetween('time',$dates)->get();

        $baocao = $cv->map(function ($value,$key) use ($t2,$t3,$t4,$t5,$t6,$t7,$cn){
            $dilam = 0; $khongdat = 0; $khongden = 0;$nghi = 0;

            return (object)[
                'bophan' => $value->bophan,
                'id' => $value->id,
                'name' => $value->name,

                'cv2' => CV::whereDate('time',$t2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'cv4' => CV::whereDate('time',$t4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'cv3' => CV::whereDate('time',$t3)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'cv5' => CV::whereDate('time',$t5)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'cv6' => CV::whereDate('time',$t6)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'cv7' => CV::whereDate('time',$t7)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'cvcn' => CV::whereDate('time',$cn)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'cv' => CV::whereBetween('time',[$t2,$cn])->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),

                'ctv' => User::find($value->user_id)->name,


                'khongdat2' => CV::whereDate('time',$t2)->where('status',2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'khongdat3' => CV::whereDate('time',$t3)->where('status',2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'khongdat4' => CV::whereDate('time',$t4)->where('status',2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'khongdat5' => CV::whereDate('time',$t5)->where('status',2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'khongdat6' => CV::whereDate('time',$t6)->where('status',2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'khongdat7' => CV::whereDate('time',$t7)->where('status',2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'khongdatcn' => CV::whereDate('time',$cn)->where('status',2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'khongdat' => CV::whereBetween('time',[$t2,$cn])->where('status',2)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),

                'dat2' => CV::whereDate('time',$t2)->where('status',4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dat3' => CV::whereDate('time',$t3)->where('status',4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dat4' => CV::whereDate('time',$t4)->where('status',4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dat5' => CV::whereDate('time',$t5)->where('status',4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dat6' => CV::whereDate('time',$t6)->where('status',4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dat7' => CV::whereDate('time',$t7)->where('status',4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'datcn' => CV::whereDate('time',$cn)->where('status',4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dat' => CV::whereBetween('time',[$t2,$cn])->where('status',4)->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),

                'hen2' => CV::whereDate('time',$t2)->whereNotNull('hen')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'hen3' => CV::whereDate('time',$t3)->whereNotNull('hen')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'hen4' => CV::whereDate('time',$t4)->whereNotNull('hen')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'hen5' => CV::whereDate('time',$t5)->whereNotNull('hen')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'hen6' => CV::whereDate('time',$t6)->whereNotNull('hen')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'hen7' => CV::whereDate('time',$t7)->whereNotNull('hen')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'hencn' => CV::whereDate('time',$cn)->whereNotNull('hen')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'hen' => CV::whereBetween('time',[$t2,$cn])->whereNotNull('hen')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),

                // 'khongden' => $khongden,
                // 'dilam' => $dilam,
                'dilam2' => CV::whereDate('time',$t2)->whereNotNull('dilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dilam3' => CV::whereDate('time',$t3)->whereNotNull('dilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dilam4' => CV::whereDate('time',$t4)->whereNotNull('dilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dilam5' => CV::whereDate('time',$t5)->whereNotNull('dilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dilam6' => CV::whereDate('time',$t6)->whereNotNull('dilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dilam7' => CV::whereDate('time',$t7)->whereNotNull('dilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dilamcn' => CV::whereDate('time',$cn)->whereNotNull('dilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'dilam' => CV::whereBetween('time',[$t2,$cn])->whereNotNull('dilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),


                'nghilam2' => CV::whereDate('time',$t2)->whereNotNull('nghilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'nghilam3' => CV::whereDate('time',$t3)->whereNotNull('nghilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'nghilam4' => CV::whereDate('time',$t4)->whereNotNull('nghilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'nghilam5' => CV::whereDate('time',$t5)->whereNotNull('nghilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'nghilam6' => CV::whereDate('time',$t6)->whereNotNull('nghilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'nghilam7' => CV::whereDate('time',$t7)->whereNotNull('nghilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'nghilamcn' => CV::whereDate('time',$cn)->whereNotNull('nghilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
                'nghilam' => CV::whereBetween('time',[$t2,$cn])->whereNotNull('nghilam')->where('user_id',$value->user_id)->where('tuyendung',$value->tuyendung)->count(),
            ];
        })->groupBy(['bophan','ctv']);
        return $baocao;
        // dd($test);
    }
}
