<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\ChamCong;
use App\ChotCongNew;
use NoiThatZip\News\Models\News;
use Carbon\Carbon;
use Input;
use App\Imports\ChamCongImport;
use App\Http\Resources\ChamCong as ChamCongResource;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show()
    {
    	$news = \DB::table('news')->orderBy('id','desc')->first();
    	$n['name'] = $news->name;
    	$n['sapo'] = $news->sapo;
        $n['content'] = $news->content;
        $n['created_at'] = $news->created_at;
    	$n['user_name'] = $news->user_name;

    	return $n;
    }

    public function showt()
    {
        $news = \DB::table('news')->orderBy('id','desc')->skip(1)->first();
        $n['name'] = $news->name;
        $n['sapo'] = $news->sapo;
        $n['content'] = $news->content;
        $n['created_at'] = $news->created_at;
        $n['user_name'] = $news->user_name;

        return $n;
    }

    public function find(Request $request)
    {
        $all = ChamCong::where('type',$request->type)->get();
        $unique = $all->unique('name');
        return collect($unique->values()->all());
    }

    public function fill(Request $request)
    {
        $all = ChamCong::all();
        $unique = $all->unique('name');
        return collect($unique->values()->all());
    }

    public function chamcong(Request $r)
    {
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        if(empty($r->pos)){
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Chưa Vị Chọn Trí'
            ),400,[]);
        }
        if($r->hasFile('file'))
        {
            $file = $r->file('file');
            $check_file = $file->extension();
            if ($check_file === 'xlsx') {
                Excel::import(new ChamCongImport($r->pos),$r->file('file'));
                
            }else{
                $duoi = $file->getClientOriginalExtension();
                $name = $file->getClientOriginalName();
                $files = str_random(2)."_".$name;
                while (file_exists("uploads/chamcong/".$files)) {
                    $files = str_random(2)."_".$name;
                }
                $file->move("uploads/chamcong",$files);
                if ($r->pos == 4 && $r->showroom == "undefined") {
                    return response(array(
                        'success' => false,
                        'data' => [],
                        'message' => 'Chưa Chọn Showroom'
                    ),400,[]);
                }
                ChamCong::where('name',\Auth::user()->name)->where('date',$dt->toDateString())->delete();
                $new = new ChamCong;
                $new->name = \Auth::user()->name;  
                $new->ma_nv = \Auth::user()->id; 
                $new->info = $r->showroom; 
                $new->in = $dt->format('h:i');
                $new->date = $dt->toDateString();    
                $new->type = $r->pos;
                $new->img= $files;
                $new->save();

                $calendar = \DB::table('calendar')->where('start','<=',$dt->toDateString())->where('end','>=',$dt->toDateString())->first();
                $checkcc = ChotCongNew::where('showroom',$r->showroom)->where('id_nv',\Auth::user()->id)->where('id_calendar',$calendar->id)->get();
                if ($checkcc->isEmpty()) {
                    $cc = new ChotCongNew;
                    $cc->name = \Auth::user()->name;
                    $cc->username = \Auth::user()->username;
                    $cc->id_nv = \Auth::user()->id;
                    $cc->showroom = $r->showroom;
                    $cc->sm = \Auth::user()->hasRole('sm') ? 6 : 2;
                    $cc->id_calendar = $calendar->id;
                    $cc->save();
                }

                return 'ảnh '.$files;
            }
        }
        else{
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Chưa Chọn File Excel hoặc Ảnh'
            ),400,[]);
        }
    }

    public function getchamcong(Request $request)
    {
        $chamcong = ChamCong::filter($request->all())->latest()->paginateFilter();
        return ChamCongResource::collection($chamcong);
    }

    public function baocao(Request $request)
    {
        $period = CarbonPeriod::create($request->sdates[0], $request->sdates[1]);
        $chamcong = ChamCong::filter($request->all())->get();
        // dd($chamcong);

        $list = array();
        $listdate = array('Tổng');

        foreach ($chamcong as $key => $v) {
            foreach ($period as $date) {
                $vdate = $date->format('Y-m-d');
                if (strtotime($vdate) == strtotime(date('Y-m-d',strtotime($v->date)))) {
                    $list1[$v->info][$v->name][$vdate] = 1;
                }
            }
        }

        foreach ($chamcong as $key => $v) {
            foreach ($period as $date) {
                $vdate = $date->format('Y-m-d');
                $list2[$v->info][$v->name][$vdate] = 0;
            }
        }

        foreach ($chamcong as $key => $v) {
            $list3[$v->info][$v->name] = array_replace($list2[$v->info][$v->name],$list1[$v->info][$v->name]);
            $list3[$v->info][$v->name][] = count($list1[$v->info][$v->name]);
        }

        // $count = 0;
        foreach ($period as $date) {
            $vdate = $date->format('l');
            $listdate[$date->format('Y-m-d')] = substr($vdate,0,3);
            $lich = \DB::table('calendar_t')->whereDate('start_t','<=',$date->format('Y-m-d'))->whereDate('end_t','>=',$date->format('Y-m-d'))->first();
            $group[$lich->t][] = 1; 
            $sum[$lich->t] = array_sum($group[$lich->t]);
        }

        if (isset($list3)) {
            return ['date' => $listdate, 'data' => $list3 , 'group' => $sum];
        }else{
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Chưa Có Data Chấm Công'
            ),400,[]);
        }
    }

    public function bophan(Request $request)
    {
        $all = ChamCong::all();
        $unique = $all->unique('info');
        return collect($unique->values()->all());
    }

    public function chamcongout(Request $r)
    {
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        if($r->hasFile('file'))
        {
            $file = $r->file('file');
            $check_file = $file->extension();
           
            $duoi = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            $files = str_random(2)."_".$name;
            while (file_exists("uploads/chamcong/".$files)) {
                $files = str_random(2)."_".$name;
            }
            $file->move("uploads/chamcong",$files);
            if ($r->pos == 4 && $r->showroom == "undefined") {
                return response(array(
                    'success' => false,
                    'data' => [],
                    'message' => 'Chưa Chọn Showroom'
                ),400,[]);
            }
            $new =  ChamCong::find($r->id);
            $new->out = $dt->format('h:i');
            $new->img_out = $files;
            $new->save();

            return 'ảnh '.$files;
        }
        else{
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Chưa Chọn Ảnh'
            ),400,[]);
        }
    }
}