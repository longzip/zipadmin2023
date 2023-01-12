<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use NoiThatZip\SalesTarget\Models\SalesTarget;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\BlockTarget;

class SalesTargetsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::filter($request->all())->get();
        $sales = $users->filter(function($user){
            return $user->hasRole('sales');
        });

        $dates = $request['dates'];
        $salesTargets = $sales->map(function($sale) use($dates){
            // $sale->salesTargets()->delete();
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            $name = $sale->name;
            $status = \DB::table('block_target')->where('type','sales')->where('date',$dates[1])->get();
            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'costcenter' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->name : 'Khac',
                'targets' => $findTargets->get()
                ->map(function($target) use ($name){return (object)[
                    'id' => $target->id,
                    'name' => $name,
                    'number' => $target->number,
                    'kh15_number' => $target->kh15_number,
                    'order_number' => $target->order_number,
                    'amount_number' => $target->amount_number,
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
            return $salet->costcenter;
        });

        return $showroom;
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
    public function store(Request $request)
    {
        $user = auth('api')->user();
        // if (!$user->hasRole('asm') ) {
        //     return response(array(
        //         'success' => false,
        //         'data' => [],
        //         'message' => 'Bạn không có quyền sửa'
        //     ),400,[]);
        // }
        // dd($request->all());
        collect($request->all())->each(function($target){
                SalesTarget::where('targetable_type','App\User')->where('targetable_id',$target['id'])->whereDate('close',$target['close'])->delete();

                if($target['name'] == null){
                    SalesTarget::where('targetable_type','App\User')->where('targetable_id',$target['id'])->whereDate('close',$target['close'])->delete();
                    $user = User::findOrFail($target['id']);
                }else{
                    SalesTarget::where('id',$target['id'])->delete();
                    $userid = User::where('name',$target['name'])->first();
                    $user = User::findOrFail($userid['id']);

                }
                $user->addTarget($target['kh15_number'], $target['number'], $target['order_number'], $target['amount_number'] ,$target['close'], $target['dahlia'],  $target['khac'],$target['iris'], $target['ivy']);
       
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $target = SalesTarget::find($id);
        $target->number = $request['number'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $target = SalesTarget::findOrFail($id);
        $target->delete();
        return ['message' => "Delete complete!"];
    }

    public function blocksales(Request $request)
    {
        $dates = $request['dates'];
        $check = \DB::table('block_target')->where('type','sales')->where('date',$dates[1])->get();
        if(\Auth::user()->id == 9003 or \Auth::user()->id == 9074) {
            if($check->isEmpty()) {
                $block = new BlockTarget;
                $block->date = $dates[1];
                $block->type = 'sales';
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

    public function addsales(Request $request)
    {
        // dd($request->all());
        
        $get_p = \DB::table('calendar')->where('z',$request->year)->where('p',$request->pt)->first();
        SalesTarget::where('targetable_type','App\User')->whereBetween('close',[$get_p->start,$get_p->end])->delete();
        $all = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('amount_number','>',0)->whereBetween('close',[$get_p->start,$get_p->end])->pluck('targetable_id');
        $listid = $all->unique()->values()->map(function ($value,$key) use ($get_p){
            $id = Costcenter::find($value)->entries(User::class)->get();
            return [$value => $id->map(function($user) use ($get_p){
                if($user->date_off > $get_p->start && $user->hasRole('sales')){
                    return $user;
                }
            })->reject(function ($name) {
                return empty($name);
            })->values()];
        });

        // $each = $listid->each(function ($value,$key) use ($get_p){
        //     return $value->each(function ($v,$k) use ($get_p){
        //         $sr = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$k)->whereBetween('close',[$get_p->start,$get_p->end])->get();
        //         return $sr->each(function($target) use ($v){
        //             return $v->each(function($user) use ($target){
        //                 if (count($user['roles'])) {
        //                     $new = new SalesTarget;
        //                     $new->targetable_type = 'App\User';
        //                     $new->targetable_id = $v->id;
        //                     $new->amount_number = $target->amount_number * 40 / 1000;
        //                     $new->number = 0;
        //                     $new->kh15_number = 0;
        //                     $new->order_number = 0;
        //                     $new->close = $target->close;
        //                     $new->dahlia = 1000;
        //                     $new->iris = 1000;
        //                     $new->ivy = 1000;
        //                     $new->khac = 1000;
        //                     $new->save();
        //                 }
        //             });
        //         });
        //     });
        // });
        // dd($listid)
        foreach ($listid as $value) {
            foreach ($value as $k => $v) {
                $sr = SalesTarget::where('targetable_type','NoiThatZip\Costcenter\Models\Costcenter')->where('targetable_id',$k)->whereBetween('close',[$get_p->start,$get_p->end])->get();
                foreach ($sr as $target) {
                    $check = 0;
                    foreach ($v as $user) {
                        if (count($v) > 0) {
                            for ($i=0; $i < count($v); $i++) { 
                                if (count($v[$i]['roles']) > 1 && $v[$i]['id'] != 9017) {
                                    $check += 1;
                                }
                            }
                        }
                        if ($check > 0) {
                            if (count($user['roles']) == 1) {
                                if (count($v) > 1) {
                                    $order = $target->amount_number * 40 / 100 / (count($v) - 1);
                                    $new = new SalesTarget;
                                    $new->targetable_type = 'App\User';
                                    $new->targetable_id = $user->id;
                                    $new->amount_number = $order;
                                    $new->number = $order / 20000000 * 3;
                                    $new->kh15_number = $order / 20000000 * 9;
                                    $new->order_number = $order / 20000000;
                                    $new->close = $target->close;
                                    $new->dahlia = ($order / 20000000 * 3) * 20 / 100;
                                    $new->iris = ($order / 20000000 * 3) * 30 / 100;
                                    $new->ivy = ($order / 20000000 * 3) * 20 / 100;
                                    $new->khac = ($order / 20000000 * 3) * 30 / 100;
                                    $new->save();
                                }
                            }
                            if (count($user['roles']) == 2) {
                                if ($user->hasRole('sm')) {
                                    $order = $target->amount_number * 60 / 100;
                                    $new = new SalesTarget;
                                    $new->targetable_type = 'App\User';
                                    $new->targetable_id = $user->id;
                                    $new->amount_number = $order;
                                    $new->number = $order / 20000000 * 3;
                                    $new->kh15_number = $order / 20000000 * 9;
                                    $new->order_number = $order / 20000000;
                                    $new->close = $target->close;
                                    $new->dahlia = ($order / 20000000 * 3) * 20 / 100;
                                    $new->iris = ($order / 20000000 * 3) * 30 / 100;
                                    $new->ivy = ($order / 20000000 * 3) * 20 / 100;
                                    $new->khac = ($order / 20000000 * 3) * 30 / 100;
                                    $new->save();
                                }else{
                                    $order = $target->amount_number * 40 / 100 / (count($v) - 1);
                                    $new = new SalesTarget;
                                    $new->targetable_type = 'App\User';
                                    $new->targetable_id = $user->id;
                                    $new->amount_number = $order;
                                    $new->number = $order / 20000000 * 3;
                                    $new->kh15_number = $order / 20000000 * 9;
                                    $new->order_number = $order / 20000000;
                                    $new->close = $target->close;
                                    $new->dahlia = ($order / 20000000 * 3) * 20 / 100;
                                    $new->iris = ($order / 20000000 * 3) * 30 / 100;
                                    $new->ivy = ($order / 20000000 * 3) * 20 / 100;
                                    $new->khac = ($order / 20000000 * 3) * 30 / 100;
                                    $new->save();
                                }
                                
                            }
                            if (count($user['roles']) == 3) {
                                if (count($v) > 1) {
                                    $order = $target->amount_number * 40 / 100 / (count($v) - 1);
                                    $new = new SalesTarget;
                                    $new->targetable_type = 'App\User';
                                    $new->targetable_id = $user->id;
                                    $new->amount_number = $order;
                                    $new->number = $order / 20000000 * 3;
                                    $new->kh15_number = $order / 20000000 * 9;
                                    $new->order_number = $order / 20000000;
                                    $new->close = $target->close;
                                    $new->dahlia = ($order / 20000000 * 3) * 20 / 100;
                                    $new->iris = ($order / 20000000 * 3) * 30 / 100;
                                    $new->ivy = ($order / 20000000 * 3) * 20 / 100;
                                    $new->khac = ($order / 20000000 * 3) * 30 / 100;
                                    $new->save();
                                }
                            }
                            if (count($user['roles']) == 4) {
                                $order = $target->amount_number * 60 / 100;
                                $new = new SalesTarget;
                                $new->targetable_type = 'App\User';
                                $new->targetable_id = $user->id;
                                $new->amount_number = $order;
                                $new->number = $order / 20000000 * 3;
                                $new->kh15_number = $order / 20000000 * 9;
                                $new->order_number = $order / 20000000;
                                $new->close = $target->close;
                                $new->dahlia = ($order / 20000000 * 3) * 20 / 100;
                                $new->iris = ($order / 20000000 * 3) * 30 / 100;
                                $new->ivy = ($order / 20000000 * 3) * 20 / 100;
                                $new->khac = ($order / 20000000 * 3) * 30 / 100;
                                $new->save();
                            }
                        }else{
                            if ($user->id != 9017) {
                                $order = $target->amount_number / (count($v) - 1);
                                $new = new SalesTarget;
                                $new->targetable_type = 'App\User';
                                $new->targetable_id = $user->id;
                                $new->amount_number = $order;
                                $new->number = $order / 20000000 * 3;
                                $new->kh15_number = $order / 20000000 * 9;
                                $new->order_number = $order / 20000000;
                                $new->close = $target->close;
                                $new->dahlia = ($order / 20000000 * 3) * 20 / 100;
                                $new->iris = ($order / 20000000 * 3) * 30 / 100;
                                $new->ivy = ($order / 20000000 * 3) * 20 / 100;
                                $new->khac = ($order / 20000000 * 3) * 30 / 100;
                                $new->save();
                            }
                        }
                    }
                }
            }
        }

        return  $new ;
    }
}
