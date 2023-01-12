<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ThiCong;
use App\CSVC;
use Carbon\Carbon;

class CSVCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $csvc = CSVC::filter($request->all())->get()->groupBy(function ($value){
            return $value->showroom;
        });
        return $csvc;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $csvc = new CSVC;
        $csvc->showroom = $request->costcenters['name'];
        $csvc->code = $request->sanpham['code'];
        $csvc->sanpham = $request->sanpham['name'];
        $csvc->so_luong = $request->soluong;
        $csvc->save();
    }

    public function addmacsvc(Request $request)
    {
        // dd($request->all());
        \DB::table('ma_csvc')->insert([
            'name' => $request->note,
            'code' => $request->code,
        ]);
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
        // dd($request->all());
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $new = new ThiCong;
        $new->so_number = 'BD_'.$request->showroom;        
        $new->address = $request->showroom;
        $new->phone = '0974008002';
        $new->amount = 0;
        $new->type = 1;
        $new->thicong = '[]';
        $new->product = '[]';
        $new->start_date = $dt->toDateString();
        $new->delivery_date = $dt->toDateString();
        $new->note = $request->note;
        $new->costcenter = $request->showroom;
        $new->name = \Auth::user()->name;
        // dd($new)
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
        //
    }
}
