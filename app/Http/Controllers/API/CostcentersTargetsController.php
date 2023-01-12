<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use NoiThatZip\Costcenter\Models\Costcenter;
use Carbon\Carbon;
use NoiThatZip\SalesTarget\Models\SalesTarget;
use App\BlockTarget;
use App\RolesTarget;

class CostcentersTargetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales = Costcenter::orderBy('stt','asc')->filter($request->all())->get();
        $dates = $request['dates'];
        $dt = Carbon::now('Asia/Ho_Chi_Minh');
        $d1 = $this->date($dates[0]);
        $d2 = $this->date($dt->toDateString());
        if ($d2 >= $d1) {
            $salesTargets = $sales->map(function($sale) use($dates){
                $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
                $name = $sale->name;
                $status = \DB::table('block_target')->where('type','costcenter')->where('date',$dates[1])->get();
                // dd($status);
                return (object)[
                    'id' => $sale->id,
                    'name' => $name,
                    'warehouse' => $sale->warehouse,
                    'code' => $sale->code,
                    'targets' => $findTargets->get()
                    ->map(function($target) use ($name){return (object)[
                        'id' => $target->id,
                        'name' => $name,
                        'number' => (int)$target->number,
                        'kh15_number' => (int)$target->kh15_number,
                        'order_number' => (int)$target->order_number,
                        'amount_number' => (int)$target->amount_number,
                        'ivy' => (int)$target->ivy,
                        'dahlia' => (int)$target->dahlia,
                        'iris' => (int)$target->iris,
                        'khac' => (int)$target->khac,
                        'close'  => $target->close
                    ];
                }),
                    'count' => (int)$findTargets->sum('number'),
                    'kh15_count' => (int)$findTargets->sum('kh15_number'),
                    'order_count' => (int)$findTargets->sum('order_number'),
                    'amount_count' => (int)$findTargets->sum('amount_number'),
                    'status' => ($status->isEmpty()) ? 1 : 0,
                    'ivy' => (int)$findTargets->sum('ivy'),
                    'dahlia' => (int)$findTargets->sum('dahlia'),
                    'iris' => (int)$findTargets->sum('iris'),
                    'khac' => (int)$findTargets->sum('khac'),
                ];
            });
            // return $salesTargets->values();
            $showroom = $salesTargets->values()->groupBy(function($salet){
                return $salet->warehouse;
            })->toArray();

            return $showroom;
        }else{
            $check = RolesTarget::whereDate('d',$dates[0])->where('user_id',\Auth::user()->id)->get();
            if ($check->isNotEmpty() or \Auth::user()->id == 9003) {
                $salesTargets = $sales->map(function($sale) use($dates){
                $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
                $name = $sale->name;
                $status = \DB::table('block_target')->where('type','costcenter')->where('date',$dates[1])->get();
                // dd($status);
                return (object)[
                    'id' => $sale->id,
                    'name' => $name,
                    'warehouse' => $sale->warehouse,
                    'code' => $sale->code,
                    'targets' => $findTargets->get()->map(function($target) use ($name){return (object)[
                            'id' => $target->id,
                            'name' => $name,
                            'number' => (int)$target->number,
                            'kh15_number' => (int)$target->kh15_number,
                            'order_number' => (int)$target->order_number,
                            'amount_number' => (int)$target->amount_number,
                            'ivy' => (int)$target->ivy,
                            'dahlia' => (int)$target->dahlia,
                            'iris' => (int)$target->iris,
                            'khac' => (int)$target->khac,
                            'close'  => $target->close
                        ];
                    }),
                    'count' => (int)$findTargets->sum('number'),
                    'kh15_count' => (int)$findTargets->sum('kh15_number'),
                    'order_count' => (int)$findTargets->sum('order_number'),
                    'amount_count' => (int)$findTargets->sum('amount_number'),
                    'ivy' => (int)$findTargets->sum('ivy'),
                    'dahlia' => (int)$findTargets->sum('dahlia'),
                    'iris' => (int)$findTargets->sum('iris'),
                    'khac' => (int)$findTargets->sum('khac'),
                    'status' => ($status->isEmpty()) ? 1 : 0,
                ];
            });
            // return $salesTargets->values();
            $showroom = $salesTargets->values()->groupBy(function($salet){
                return $salet->warehouse;
            })->toArray();

            return $showroom;
            }else{
                return response(array(
                    'error' => true,
                    'data' => [],
                    'message' => 'Bạn không có quyền sửa'
                ),400,[]);
            }
        }
        // dd($this->date($dates[0]));
    }

    public function date($date)
    {
        return implode('', explode('-', $date));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth('api')->user();
        if ($user->id != 9003 ) {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Bạn không có quyền sửa'
            ),400,[]);
        }
        // dd($request->all());
        collect($request->all())->each(function($target){
            // if ($target['id'] == 0) {
                if($target['name'] == null){
                    SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$target['costcenterId'])->whereDate('close',$target['close'])->delete();
                    $user = Costcenter::findOrFail($target['costcenterId']);
                }else{
                    SalesTarget::where('id',$target['id'])->delete();
                    $userid = Costcenter::where('name',$target['name'])->first();
                    $user = Costcenter::findOrFail($userid['id']);

                }
                $user->addTarget($target['kh15_number'], $target['number'], $target['order_number'], $target['amount_number'] ,$target['close'], $target['dahlia'], $target['khac'],$target['iris'],  $target['ivy']);
            // }
            // else{
            //     $saleTarget = SalesTarget::find($target['id']);
            //     $saleTarget->kh15_number = $target['kh15_number'];
            //     $saleTarget->number = $target['number'];
            //     $saleTarget->order_number = $target['order_number'];
            //     $saleTarget->amount_number = $target['amount_number'];
            //     $saleTarget->dahlia = $target['dahlia'];
            //     $saleTarget->ivy = $target['ivy'];
            //     $saleTarget->iris = $target['iris'];
            //     $saleTarget->khac = $target['khac'];
            //     $saleTarget->save();
            // }
        });

        
        
        return ['message' => 'Saved Target Success!!'];
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
        $this->validate($request,[
            'number' => ['required']
        ]);
        $saleTarget = SalesTarget::find($id);
        $saleTarget->kh15_number = $request['kh15_number'];
        $saleTarget->number = $request['number'];
        $saleTarget->order_number = $request['order_number'];
        $saleTarget->amount_number = $request['amount_number'];
        $saleTarget->save();
        return ['message' => 'Luu thanh cong!!'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $saleTarget = SalesTarget::findOrFail($id);
        $saleTarget->delete();
        return ['message' => 'Xoa thanh cong!!'];
    }

    public function block(Request $request)
    {
        $dates = $request['dates'];
        $check = \DB::table('block_target')->where('type','costcenter')->where('date',$dates[1])->get();
        if(\Auth::user()->id == 9003 or \Auth::user()->id == 9074) {
            if($check->isEmpty()) {
                $block = new BlockTarget;
                $block->date = $dates[1];
                $block->type = 'costcenter';
                $block->save();
            }
        }else {
            return response(array(
                'success' => false,
                'data' => [],
                'message' => 'Bạn không có quyền sửa'
            ),400,[]);
        }
    }

    public function openclose(Request $request)
    {
        $check = \DB::table('block_target')->where('type','costcenter')->where('date',$request->date)->get();
        if ($check->isNotEmpty()) {
            $stt = 0;
        }else{
            $stt = 1; 
        }
        return ['check'=>$stt];
    }

    public function open(Request $request)
    {
        $dates = $request['dates'];
        $check = \DB::table('block_target')->where('type','costcenter')->where('date',$dates[1])->delete();
    }

    public function deleterole(Request $request)
    {
        $saleTarget = \DB::table('roles_target')->where('id',$request->id)->delete();
    }

    public function allproduct(Request $request)
    {
        $dates = $request['dates'];
        $check = RolesTarget::whereDate('d',$dates[0])->where('user_id',\Auth::user()->id)->get();
        // if ($check->isNotEmpty() or \Auth::user()->id == 9003) {
            $sales = SalesTarget::where('targetable_type','App\Products')->whereBetween('close',$dates)->get();
            if($sales->isNotEmpty()) {
                $salesTargets = $sales->map(function($sale) use($dates){
                    return (object)[
                        'id' => $sale->id,
                        'ivy' => (int)$sale->ivy,
                        'dahlia' => (int)$sale->dahlia,
                        'iris' => (int)$sale->iris,
                        'khac' => (int)$sale->khac,
                        'close'  => $sale->close,
                    ];
                });
                return ['list' => collect($salesTargets)->sortBy('close'),'total'=>['totalivy'=>$salesTargets->sum('ivy'),'totaliris'=>$salesTargets->sum('iris'),'totaldahlia'=>$salesTargets->sum('dahlia'),'totalkhac'=>$salesTargets->sum('khac')]];
            }else{
                return ['list' =>[
                        ['ivy' => 0,
                        'id' => null,
                        'dahlia' => 0,
                        'close' => $dates[1],
                        'iris' => 0,
                        'khac' => 0,],
                        ['ivy' => 0,
                        'id' => null,
                        'dahlia' => 0,
                        'close' => date('Y-m-d', strtotime($dates[0]. ' + 20 days')),
                        'iris' => 0,
                        'khac' => 0,],
                        ['ivy' => 0,
                        'id' => null,
                        'dahlia' => 0,
                        'close' => date('Y-m-d', strtotime($dates[0]. ' + 13 days')),
                        'iris' => 0,
                        'khac' => 0,],
                        ['ivy' => 0,
                        'dahlia' => 0,
                        'id' => null,
                        'iris' => 0,
                        'close' => date('Y-m-d', strtotime($dates[0]. ' + 6 days')),
                        'khac' => 0,],
                    ],'total'=>[
                        'totalivy'=>0,'totaliris'=>0,'totaldahlia'=>0,'totalkhac'=>0
                    ]
                ];
            }
        // }
    }

    public function addtargetproduct(Request $request)    
    {
        // foreach ($request->line as $key => $value) {
        //    dd($value);
        // }
        $check = SalesTarget::where('targetable_type','App\Products')->where('close',$request->close)->get(); 
// dd()
        if ($check->isEmpty()) {
           $s = new SalesTarget; 
           $s->dahlia = $request->dahlia;
           $s->iris = $request->iris;
           $s->ivy = $request->ivy;
           $s->close = $request->close;
           $s->khac = $request->khac;
           $s->targetable_type = 'App\Products';
           $s->targetable_id = 0;
           $s->order_number = 0;
           $s->amount_number = 0;
           $s->kh15_number = 0;
           $s->number = 0;
           $s->save();
        }else{
           $s = SalesTarget::where('targetable_type','App\Products')->where('close',$request->close)->first(); 
           $s->dahlia = $request->dahlia;
           $s->iris = $request->iris;
           $s->ivy = $request->ivy;
           $s->close = $request->close;
           $s->khac = $request->khac;
           $s->targetable_type = 'App\Products';
           $s->targetable_id = 0;
           $s->order_number = 0;
           $s->amount_number = 0;
           $s->kh15_number = 0;
           $s->number = 0;
           $s->save();
        }

        return $s;
        // dd($request->all()s);
    }
    
}
