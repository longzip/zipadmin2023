<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TrungGian;
use App\TuyenDung;
use App\User;
use Carbon\Carbon;
use App\Http\Resources\TuyenDung as TuyenDungResource;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\NghiPhep;

class TuyenDungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tuyendung = TuyenDung::filter($request->all())->latest()->paginateFilter();
        return TuyenDungResource::collection($tuyendung);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	// dd($request->costcenters['id']);
        $new = new TuyenDung;
        if ($request->roles['name'] == 'sales' || $request->roles['name'] == 'sm' || $request->roles['name'] == 'Sale Leader') {
	        $new->showroom = count($request->costcenters) > 0 ? $request->costcenters['id'] : null;
        }
        $new->vitri = $request->roles['name'];
        $new->start = $request->start;
        $new->end = $request->end;
        $new->soluong = $request->soluong;
        $new->note = $request->chuy;
        $new->user_id = \Auth::user()->id;
        // dd($new);
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
    public function update(Request $request, $id)
    {
        $new = TuyenDung::find($id);
        if ($request->roles['name'] == 'sales' || $request->roles['name'] == 'sm') {
	        $new->showroom = count($request->costcenters) > 0 ? $request->costcenters['id'] : null;
        }
        $new->vitri = $request->roles['name'];
        $new->start = $request->start;
        $new->end = $request->end;
        $new->soluong = $request->soluong;
        $new->note = $request->chuy;
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
        TuyenDung::find($id)->delete();
    }

    public function phanquyen(Request $request)
    {
    	// $new = TuyenDung::find($request->id);
     //    $new->ctv = $request->idnv;
     //    $new->save();
        $new = new TrungGian;
        $new->id_user = $request->idnv;
        $new->model = 'App\TrungGian';
        $new->id_trung = $request->id;
        $new->save();
    }

    public function tuyendung(Request $request)
    {
        $id = TrungGian::where('id_user',\Auth::user()->id)->pluck('id_trung');
    	$new = TuyenDung::whereIn('id',$id)->get();
        // dd($id);
    	return $new->map(function ($value){
    		$showroom['name'] = '';
    		if ($value->showroom > 0) {
    			$showroom = Costcenter::where('id',$value->showroom)->first();
    		}
    		return (object)[
    			'id' => $value->id,
    			'name' => $value->vitri.' '.$showroom['name'],
    		];
    	});
    }

    public function adddx(Request $request)
    {
    	$idCos = Costcenter::where('name',$request->name)->first()->id;
        $resources = Costcenter::find($idCos)->entries(User::class)->pluck('id');
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
    	if ($request->sm > 0) {
	        $datenghi = NghiPhep::whereIn('ten_nhan_vien',$resources)->where('loai_nghi',3)->where('vi_tri',6)->orderBy('id','desc')->first(); 
	    	$new = new TuyenDung;
	        $new->showroom = $idCos;
	        $new->vitri = 'sm';
	        $new->start = $dt->toDateString();
	        $new->end = $datenghi->end_date;
	        $new->soluong = $request->sm;
	        $new->note = null;
	        $new->user_id = \Auth::user()->id;
	        $new->save();
    	}
    	if ($request->sales > 0) {
    		$datenghi = NghiPhep::whereIn('ten_nhan_vien',$resources)->where('loai_nghi',3)->where('vi_tri',2)->orderBy('id','desc')->first(); 
	    	$new = new TuyenDung;
	        $new->showroom = $idCos;
	        $new->vitri = 'sales';
	        $new->start = $dt->toDateString();
	        $new->end = $datenghi->end_date;
	        $new->soluong = $request->sales;
	        $new->note = null;
	        $new->user_id = \Auth::user()->id;
	        $new->save();
    	}
    }
}
