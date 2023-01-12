<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DinhMuc;
use App\SanXuat;
use App\HistorySX;
use Carbon\Carbon;

class DinhMucController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->week == 0){
            $dt = Carbon::now('Asia/Ho_Chi_Minh');
            $date = \DB::table('calendar_t')->whereDate('start_t','<=',$dt->toDateString())->whereDate('end_t','>=',$dt->toDateString())->first();
        }else{
            $date = \DB::table('calendar_t')->where('id_table',$request->week)->first();
        }
        $mystuff = SanXuat::whereBetween('date',[$date->start_t,$date->end_t])->get();
        
        // $sp = $mystuff->groupBy('date')->map(function ($row) {
        //     return $row->groupBy('product')->map(function ($r) {
        //         $a = $r->groupBy('so_number')->map(function ($k) {
        //             return $k->sum('soluong') / $k->count();
        //         });
        //         $sl = $r->unique('soluong')->sum('soluong');
        //         return [
        //             'soluong' => $sl,
        //             'order' => $a,
        //         ];
        //     });
        // });
        $sp = $mystuff->groupBy('date')->map(function ($row) {
            return $row->groupBy('product')->map(function ($r) {
                return $r->unique('soluong')->sum('soluong');
            });
        });
        $sumSP = array();

        foreach ($sp as $k => $subArray) {
            foreach ($subArray as $id=>$value) {
                if (isset($sumSP[$id])) {
                    $sumSP[$id]+=$value;
                }else{
                    $sumSP[$id]=$value;
                }
            }
        }
       
        //level bán thành phẩm
        $level = $mystuff->max('type');
        for ($i=1; $i < $level ; $i++) { 
            $check = SanXuat::whereBetween('date',[$date->start_t,$date->end_t])->where(function($query) use ($i){
                $query->where('type', $i)
                ->orWhereIn('type',range(1,$i))->where('nvl',1);
            })->get();
            $chuanhap['Bán Thành Phẩm '.$i] = $check->groupBy('code')->map(function ($row) {
                return $row->sum('quantity');
            });
        }

        // level nguyên vật liệu
        $chuanhap['Nguyên Vật Liệu'] = $mystuff->where('nvl',1)->groupBy('code')->map(function ($row) {
            return $row->sum('quantity');
        });

        return ['nvl' => $chuanhap,'sp' => $sumSP];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->so);
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $list = DinhMuc::where('product', 'LIKE', "%$request->name%")->get();
        foreach ($list as $key => $value) {
            if ($request->so == 'undefined') {
                $so_number = 'Lô '.$value->product;
            }else{
                $so_number = $request->so;
            }
            $new = new SanXuat;
            $new->soluong = $request->soluong;
            $new->code = $value->code;
            $new->product = trim($value->product);
            $new->donvi = $value->donvi;
            $new->type = $value->type;
            $new->nvl = $value->nvl;
            $new->quantity = round($request->soluong * $value->soluong,2);
            $new->date = $dt->toDateString(); 
            $new->so_number = $so_number;
            $new->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        return DinhMuc::where('product', 'LIKE', "%$request->name%")->get();
    }

    public function showhs(Request $request)
    {
        return HistorySX::where('date',$request->date)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $new = new HistorySX;
        $new->name = \Auth::user()->name;
        $new->product = $request->name;
        $new->soluong = $request->sl;
        $new->so_number = $request->so;
        $new->date = $request->date;
        $new->save();
        SanXuat::where('product',$request->name)->where('so_number',$request->so)->where('soluong',$request->sl)->delete();
    }

    public function dinhmuc(Request $request)
    {
        return DinhMuc::where('product', 'LIKE', "%$request->name%")->where('nvl',1)->get();
    }

    public function showproductsorder(Request $request)
    {
        $sx = SanXuat::where('so_number',$request->id)->get()->unique('soluong','product');
        if (empty($sx)) {
            return '';
        }else{
            $a = '';
            foreach ($sx as $key => $value) {
                $a = "Đơn ". $value['so_number']." đã có lệnh sản xuất ". $value['soluong']." cái ". $value['product']." vào ngày ". $value['date'].'</br>'.$a;
            }
            return $a;
        }
    }
}
