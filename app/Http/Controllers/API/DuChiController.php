<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\DuChi;
use App\DongChi;
use App\DaChi;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use App\Http\Resources\DuChi as DuChiResource;

class DuChiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dc = DuChi::filter($request->all())->latest()->paginateFilter();
        return DuChiResource::collection($dc);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        // dd($r->all());
        if(empty($r->pos)){
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Chưa Chọn Khối'
            ),400,[]);
        }
        // if($r->hasFile('file')){
        //     $file = $r->file('file');
        //     $check_file = $file->extension();
        //     $duoi = $file->getClientOriginalExtension();
        //     $name = $file->getClientOriginalName();
        //     $files = str_random(2)."_".$name;
        //     while (file_exists("uploads/chamcong/".$files)) {
        //         $files = str_random(2)."_".$name;
        //     }
        //     $file->move("uploads/chamcong",$files);
        // }else{
        //     $files = null;
        // }
        if ($r->pos == 4 && $r->showroom == "undefined") {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Chưa Chọn Showroom'
            ),400,[]);
        }
        if ($r->pos == 1) {
            $bophan = 'Văn Phòng Miền Bắc';
        }
        if ($r->pos == 2) {
            $bophan = 'Văn Phòng Miền Nam';
        }
        if ($r->pos == 3) {
            $bophan = 'Nhà Máy';
        }
        if ($r->pos == 4) {
            $bophan = $r->showroom;
        }
        $new = new DuChi;
        $new->start_chi = $r->start_chi; 
        $new->end_chi = $r->end_chi; 
        $new->amount = $r->amount; 
        $new->note = $r->note; 
        $new->date_dx = $r->date_dx; 
        $new->user_id = \Auth::user()->id; 
        $new->bophan = $bophan; 
        $new->name = $r->name; 
        $new->type_bophan = $r->pos;
        // $new->tai_lieu= $files;
        $new->save();

        if($r->hasFile('file')){
            $user = auth('api')->user();
            foreach ($r->file('file') as $key => $value) {
                $media = $new->addMedia($value)
                ->toMediaCollection('images','public');
                $image = $new->attach([
                    'src' => $media->getUrl(),
                ], $user);
            }
        }

        $period = CarbonPeriod::create($r->start_chi, $r->end_chi);
        foreach ($period as $date) {
            $vdate = $date->format('Y-m-d');
            $chi = new DongChi;
            $chi->date = $vdate;
            $chi->money = $r->amount / $period->count();
            $chi->id_duchi = $new['id'];
            $chi->save();
        }
        
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $r)
    {
        $period = CarbonPeriod::create($r->start_chi, $r->end_chi);
        foreach ($period as $date) {
            $vdate = $date->format('Y-m-d');
            $chi = new DaChi;
            $chi->date = $vdate;
            $chi->note = $r->note;
            $chi->money = $r->money / $period->count();
            $chi->id_duchi = $r->id;
            $chi->save();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r)
    {
        // dd($r->all());
        if(empty($r->pos)){
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Chưa Chọn Khối'
            ),400,[]);
        }
        // if($r->hasFile('file')){
        //     $file = $r->file('file');
        //     $check_file = $file->extension();
        //     $duoi = $file->getClientOriginalExtension();
        //     $name = $file->getClientOriginalName();
        //     $files = str_random(2)."_".$name;
        //     while (file_exists("uploads/chamcong/".$files)) {
        //         $files = str_random(2)."_".$name;
        //     }
        //     $file->move("uploads/chamcong",$files);
        // }else{
        //     $files = null;
        // }
        if ($r->pos == 4 && $r->showroom == "undefined") {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Chưa Chọn Showroom'
            ),400,[]);
        }
        if ($r->pos == 1) {
            $bophan = 'Văn Phòng Miền Bắc';
        }
        if ($r->pos == 2) {
            $bophan = 'Văn Phòng Miền Nam';
        }
        if ($r->pos == 3) {
            $bophan = 'Nhà Máy';
        }
        if ($r->pos == 4) {
            $bophan = $r->showroom;
        }
        $new = DuChi::find($r->id);
        $new->start_chi = $r->start_chi; 
        $new->end_chi = $r->end_chi; 
        $new->amount = $r->amount; 
        $new->note = $r->note; 
        $new->date_dx = $r->date_dx; 
        $new->bophan = $bophan; 
        $new->name = $r->name; 
        $new->type_bophan = $r->pos;
        // $new->tai_lieu= $files;
        $new->save();

        if($r->hasFile('file')){
            $user = auth('api')->user();
            foreach ($r->file('file') as $key => $value) {
                $media = $new->addMedia($value)
                ->toMediaCollection('images','public');
                $image = $new->attach([
                    'src' => $media->getUrl(),
                ], $user);
            }
        }
        DongChi::where('id_duchi',$r->id)->delete();

        $period = CarbonPeriod::create($r->start_chi, $r->end_chi);
        foreach ($period as $date) {
            $vdate = $date->format('Y-m-d');
            $chi = new DongChi;
            $chi->date = $vdate;
            $chi->money = $r->amount / $period->count();
            $chi->id_duchi = $new['id'];
            $chi->save();
        }
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
        DuChi::findOrFail($id)->delete();
        DongChi::where('id_duchi',$id)->delete();
        return ['message' => 'User Deleted'];
    }

    public function baocao(Request $r)
    {
        $date = $r->sdates;
        $list = DongChi::whereBetween('dongchi.date',$date)->leftJoin('duchi', 'dongchi.id_duchi', '=', 'duchi.id')->where('type_bophan','!=',4)->get();
        $showroom = DongChi::whereBetween('dongchi.date',$date)->leftJoin('duchi', 'dongchi.id_duchi', '=', 'duchi.id')->where('type_bophan',4)->get();
        $grouped = $list->groupBy('bophan')
        ->map(function ($item,$key) use ($date){
                return (object)[
                    'name' => $key,
                    'money' => $item->sum('money'),
                    'type' => 1,
                    'chi' => DaChi::whereBetween('dachi.date',$date)->leftJoin('duchi', 'dachi.id_duchi', '=', 'duchi.id')->where('bophan',$key)->sum('money'),
                ];
        });

        $group = $showroom->groupBy('bophan')
        ->map(function ($item,$key) use ($date){
            return (object)[
                'name' => $key,
                'money' => $item->sum('money'),
                'type' => 0,
                'chi' => DaChi::whereBetween('dachi.date',$date)->leftJoin('duchi', 'dachi.id_duchi', '=', 'duchi.id')->where('bophan',$key)->sum('money'),
            ];
        });

        $sr = $group->sum('money');
        $tong = $sr + $grouped->sum('money');
        $tongchi = $group->sum('chi') + $grouped->sum('chi');

        $array = array_merge([['name'=>'Tổng','money'=>$tong,'type' => 2,'chi'=>$tongchi]],[['name'=>'Showroom','money'=>$sr,'type' => 1,'chi'=>$group->sum('chi')]],$group->values()->toArray(),$grouped->values()->toArray());
        return $array;
 
    }

    public function duyetchi(Request $r)
    {
        $e = DuChi::where('id',$r->id)->update(['duyet' => $r->duyet]);
    }
}
