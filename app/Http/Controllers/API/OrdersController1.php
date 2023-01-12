<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Product;
use App\Helpers\Helper;
use Auth;
use App\Http\Resources\Order as OrderResource;
use App\User;
use Carbon\Carbon;
use NoiThatZip\Costcenter\Models\Costcenter;
use App\OrderDelivery;
class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth('api')->user();
        if (!$user->can('import')) {
            if (!$request->has('users')) {
                $request['users'] = [$user->id];
            }
        }
        // $order = Order::where('orders.id',15)->leftjoin('customers','orders.ordered_by', '=', 'customers.id')->first();
        // dd($order);
        if ($user->id == 269 || $user->id == 9318 || $user->id == 9320 || $user->id == 9367 || $user->id == 9368) {
            $orders = Order::whereIn('user_id',[269,9318,9320,9367,9368])->filter($request->all())->orderBy('start_date','desc')->paginateFilter();
        }else{
            if ($user->id == 9335) {
                $orders = Order::whereIn('costcenter',['HNI_RC','HNI_TC','HNI_SVC'])->filter($request->all())->orderBy('start_date','desc')->paginateFilter();
            }else{
                $orders = Order::filter($request->all())->orderBy('start_date','desc')->paginateFilter();
            }
        }
        //return $orders;
        // dd('ok');
        return OrderResource::collection($orders);
    }

    public function sum(Request $request)
    {
        $sum_amount = Order::filter($request->all())->orderBy('start_date','desc')->sum('amount');
        $sum_amount_pages = Order::filter($request->all())->orderBy('start_date','desc')->paginateFilter()->sum('amount');
        $sum_pre_pay = Order::filter($request->all())->orderBy('start_date','desc')->sum('pre_pay');
        $sum_pre_pay_pages = Order::filter($request->all())->orderBy('start_date','desc')->paginateFilter()->sum('pre_pay');
        $pay = $sum_amount - $sum_pre_pay;
        $pay_pages = $sum_amount_pages - $sum_pre_pay_pages;
        $sum = ['sum_amount' => $sum_amount, 'sum_amount_pages' => $sum_amount_pages,'sum_pre_pay' => $sum_pre_pay,'sum_pre_pay_pages' => $sum_pre_pay_pages,'pay' => $pay,'pay_pages' => $pay_pages];
        // dd($sum);
        return $sum;
        // return OrderResource::collection($orders);     
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Order::find($id);
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
        if(Auth::user()->id == 9001 or Auth::user()->id == 9002 ){
            $order = Order::find($request->id);
            $order->delivery_date = $request->date;
            $order->save();
            return ['message' => 'Đã cập nhận ngày giao đơn hàng'];
        }else{
            return response()->toJson([
                'message' => 'Bạn không có quyền xóa',
            ], 404);
        }
    }
    public function updateTrangThai(Request $request)
    {
        if(Auth::user()->id == 9001 or Auth::user()->id == 9002 ){
            $order = Order::find($request->id);
            $order->trangthai = $request->trangthai;
            $order->save();
            return ['message' => 'Đã cập nhận trạng thái đơn hàng'];
        }else{
            return response()->toJson([
                'message' => 'Bạn không có quyền xóa',
            ], 404);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth('api')->user();
        if ($user->can('delete orders')) {
            $order = Order::findOrFail($id);
            // if ($order->status_id == 2) {
            //     return response()->toJson([
            //             'message' => 'Không thể xóa Đơn hàng đã duyệt!',
            //     ], 404);
            // }
            $order->delete();
            return ['message' => 'Đã xóa đơn hàng'];
        } else {
            return response()->toJson([
                    'message' => 'Bạn không có quyền xóa',
            ], 404);
        }
    }
    public function details($id)
    {
        return Order::find($id)->orderlines;
    }
    public function getSum(){
        $order = Order::all();
        return array('count' => $order->count(), 'sum' => $order->sum('amount') );
    }
    public function status($id)
    {
        $order = Order::find($id);
            $order->status_id = 2;
            $order->save();
            Helper::orderToXml($order->id);
            return 'Đã duyệt đơn hàng';
        if (Auth::user()->can('import')) {
            $order = Order::find($id);
            $order->status_id = 2;
            $order->save();
            Helper::orderToXml($order->id);
            return 'Đã duyệt đơn hàng';
        } else {
            return 'Bạn không có quyền duyệt';
        }
    }
    public function searchbydate1(Request $request){
        $ngaydat = substr($request['ngaydat'],0,10);
        $ngaydat2 = substr($request['ngaydat2'],0,10);
        $ngaygiao = substr($request['ngaygiao'],0,10);
        $ngaygiao2 = substr($request['ngaygiao2'],0,10);
        //return $ngaygiao . $ngaygiao2;
        if (Auth::user()->can('import')) {
            return Order::latest()->paginate(25);
        } else {
            return Order::where('user_id','=', Auth::user()->resource->id)
            ->whereBetween('delivery_date',[$ngaygiao,$ngaygiao2])->paginate(25);
        }
    }
    public function searchbydate2(Request $request){
        return $request->all();
        $ngaydat = substr($request['ngaydat'],0,10);
        $ngaydat2 = substr($request['ngaydat2'],0,10);
        $ngaygiao = substr($request['ngaygiao'],0,10);
        $ngaygiao2 = substr($request['ngaygiao2'],0,10);
        //return $ngaygiao . $ngaygiao2;
        if (Auth::user()->can('import')) {
            return Order::latest()->paginate(25);
        } else {
            return Order::where('user_id','=', Auth::user()->resource->id)
            ->whereBetween('created_at',[$ngaydat,$ngaydat2])->paginate(25);
        }
    }
    public function findBySO(){
        $so = \Request::get('so');
        return Order::where('so_number','=',$so)->firstOrFail();
    }
    public function countByDate(Request $request){
        $orders = Order::filter($request->all())->get();
        $counted = $orders->countBy(function($order){
            return $order->created_at->toDateString();
        });
        $sum = $counted->sum();
        return [$sum,$counted,'Đơn Hàng'];
    }
    public function amountByDate(Request $request){
        $orders = Order::filter($request->all())->get();
        $grouped = $orders->groupBy(function($order){
           return $order->created_at->toDateString();
        });
        $amounted = $grouped->map(function($group, $date){
            return $group->sum('amount');
        });
        foreach ($amounted as $date => $amount) {
            $values[$date] = floatval(number_format($amount,0,",","."));  
        }
        $sum = $amounted->sum();
        return [number_format($sum,0,",",","),$values,'Doanh số'];
        // return $amounted->sortKeys()->all();
    }

    public function countBySale(Request $request){
        $orders = Order::filter($request->all())->get();
        $counted = $orders->countBy(function($order){
           return $order->user_id;
        });
        $namesp = $request->p;
        $sales = $this->sales($request->sdates)->map(function($user) use ($counted,$namesp,$orders){
            $sr = $user->costcentersList();
            if($namesp != null){
                foreach ($orders as $value) {
                    $dh =  \DB::table('order_lines')->where('order_id',$value->id)->pluck('product_id')->toArray(); // lấy id các sản phẩm trong dh đó
                    $pg_gr = Product::where('groups',$namesp)->whereIn('id',$dh)->count(); // đếm những sp nào có trong 1 gr
                    $count[$value->user_id][]=isset($pg_gr) ? $pg_gr : 0;
                        }

                // dd($count_mang);
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'manv' => $user->username,
                    'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
                    'count' => isset($counted[$user->id]) ? $counted[$user->id] : 0,
                    'product' =>isset($count[$user->name]) ? array_sum($count[$user->name]) : 0,
                    ];
            }

            else{
                 return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'manv' => $user->username,
                            'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
                            'count' => isset($counted[$user->id]) ? $counted[$user->id] : 0,
                        ];
            }
        });
        //     return[
        //         'id' => $user->id,
        //         'name' => $user->name,
        //         'manv' => $user->username,
        //         'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
        //         'count' => isset($counted[$user->id]) ? $counted[$user->id] : 0
        //     ];
        // });
        return $sales->sortByDesc('count')->values()->all();
    }

    public function amountBySale(Request $request){
        $orders = Order::filter($request->all())->get();
        $test[] = '';
        foreach ($orders as $k=>$subArray) {
            $test[] = array($subArray->user_id => $subArray->amount);
        }
        $final = array();
        array_walk_recursive($test, function($item, $key) use (&$final){
            $final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
        });
        // dd($this->costcentersList());
        $namesp = $request->p;
        $sales = $this->sales($request->sdates)->map(function($user) use ($final,$namesp,$orders){
            $sr = $user->costcentersList();
            if($namesp != null){
                foreach ($orders as $value) {
                    $dh =  \DB::table('order_lines')->where('order_id',$value->id)->pluck('product_id')->toArray(); // lấy id các sản phẩm trong dh đó
                    $pg_gr = Product::where('groups',$namesp)->whereIn('id',$dh)->count(); // đếm những sp nào có trong 1 gr
                    $count[$value->user_id][]=isset($pg_gr) ? $pg_gr : 0;
                        }
                return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'manv' => $user->username,
                        'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
                        'count' => isset($final[$user->id]) ? $final[$user->id] : 0,
                        'product' =>isset($count[$user->name]) ? array_sum($count[$user->name]) : 0,
                        ];
            }
            else{
                 return [
                            'id' => $user->id,
                            'name' => $user->name,
                            'manv' => $user->username,
                            'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
                            'count' => isset($final[$user->id]) ? $final[$user->id] : 0,
                        ];
            }
        });
        //     return[
        //         'id' => $user->id,
        //         'name' => $user->name,
        //         'manv' => $user->username,
        //         'showroom' =>  $sr->isNotEmpty() ? $sr->first()->name : 'Khac',
        //         'count' => isset($final[$user->id]) ? $final[$user->id] : 0
        //     ];
        // });
        return $sales->sortByDesc('count')->values()->all();
    }
    public function baocaoSanPham(Request $request){
        $orders = Order::filter($request->all())->get();
        $gr= Product::pluck('groups')->toArray();
        $loc = array_unique($gr);
        sort($loc);
        $count = array();
        foreach ($orders as $value) {
            $pg_gr = array();
            $sp_id =  \DB::table('order_lines')->where('order_id',$value->id)->pluck('product_id');
            foreach ($loc as $value) {
            $pg_gr = Product::where('groups',$value)->whereIn('id',$sp_id)->count();
            $count[$val][]=isset($pg_gr) ? $pg_gr : 0;
            }
        }
        dd($count);
    }
    public function countByCostcenter(Request $request){
        $orders = Order::filter($request->all())->get();
        $counted = $orders->countBy(function($order){
           return $order->costcenter;
        });
        $namesp = $request->p;
        $costcenter = Costcenter::where('closed','>',$request->sdates[0])->get();
        $sales = $costcenter->map(function($user) use ($counted,$namesp,$orders){
            if($namesp != null){
                foreach ($orders as $value) {
                    $name_sr =  \DB::table('costcenters')->where('code',$value->costcenter)->pluck('name'); // lấy tên sr
                    foreach ($name_sr as $val) {
                            $dh =  \DB::table('order_lines')->where('order_id',$value->id)->pluck('product_id')->toArray();
                            $pg_gr = Product::where('groups',$namesp)->whereIn('id',$dh)->count();
                            $count[$val][]=isset($pg_gr) ? $pg_gr : 0;
                        }
                        // dd($count_mang);
                    }
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'count' => isset($counted[$user->code]) ? $counted[$user->code] : 0,
                    'product' =>isset($count[$user->name]) ? array_sum($count[$user->name]) : 0,
                ];
            }
            else{
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'count' => isset($counted[$user->code]) ? $counted[$user->code] : 0,
                    'product' => 0,
                ];
            }
        });
        return $sales->sortByDesc('count')->values()->all();
        // $counted = $contacts->countBy(function($contact){
        //     $costcenter = $contact->costcenters()->first();
        //     if (!$costcenter) {
        //         return 'Khac';
        //     }
        //     return $costcenter->id;
        // });
        // $costcenters = Order::all()->map(function($costcenter) use ($counted){
        //     return [ 
        //         'name'=> $costcenter->name ,
        //         'count' => isset($counted[$costcenter->id])? $counted[$costcenter->id] : 0 
        //     ];
        // });
        // return $costcenters->sortByDesc('count')->values()->take(150);
    }
    // public function nameProduct(Request $request){
    //     $start = $request->sdates[0];
    //     $end = $request->sdates[1];
    //     $a = array();
    //     $c= array();
    //     $kh = Order::whereBetween('start_date',[$start,$end])->get();
    //     foreach ($kh as $value) {
    //         // $kh_sr = $value->costcentersList(); // lấy thông tin SR theo từng khách hàng
    //         // foreach ($kh_sr as $val) {
    //         //     $b=$val->name; // lấy tên sr

    //             $check = \DB::table('order_lines')->where('order_id',$value->id)->get();
    //              // lấy tên sp của khách hàng
    //             foreach ($check as $va) {
    //                 $a[]=$va->item;
    //                 // $a[$b]["productLines"] = $va->name;
    //             }

    //         // }
    //     }
    //            $c = array_unique($a);
    //             sort($c);
    //             // $myobject = arrayToObject($c);
    //             // dd($myobject);
    //     return $c;
    //     // foreach ($a as $key => $value) {
    //     //     $arr_key[]=$key;
    //     // }
    //     //  foreach ($a as $key => $value) {
    //     //     $count_mang[$key][] =array_count_values($value);
    //     // }
    // }
    public function amountByCostcenter(Request $request){
        $orders = Order::filter($request->all())->get();
        $counted = $orders->countBy(function($order){
           return $order->costcenter;
        });
        $namesp = $request->p;
        // dd($namesp);
        $test[] = '';
        foreach ($orders as $k=>$subArray) {
            $test[] = array($subArray->costcenter => $subArray->amount);
        }
        $final = array();
        array_walk_recursive($test, function($item, $key) use (&$final){
            $final[$key] = isset($final[$key]) ?  $item + $final[$key] : $item;
        });
        // dd($final);
        $costcenter = Costcenter::where('closed','>',$request->sdates[0])->get();
        $sales = $costcenter->map(function($user) use ($final,$namesp,$orders){
        if($namesp != null){
            foreach ($orders as $value) {
                $name_sr =  \DB::table('costcenters')->where('code',$value->costcenter)->pluck('name'); // lấy tên sr
                 // lấy các đơn hàng của sr đó
                foreach ($name_sr as $val) {
                    $dh =  \DB::table('order_lines')->where('order_id',$value->id)->pluck('product_id')->toArray();
                    $pg_gr = Product::where('groups',$namesp)->whereIn('id',$dh)->count();
                    $count[$val][]=isset($pg_gr) ? $pg_gr : 0;
                    //         if ($valu->item == $namesp) {
                    //             $name_pd[$val][] = $valu->item; // l ấy tên sp cùng trong từng  đơn hàng đó
                    //  
                }
            }
            // dd(array_sum($count["HNI Royal City"]));
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'count' => isset($final[$user->code]) ? $final[$user->code] : 0,
                        'product' =>isset($count[$user->name]) ? array_sum($count[$user->name]) : 0,
                        ];
            }
        else{
             return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'count' => isset($final[$user->code]) ? $final[$user->code] : 0,
                    ];
            }
        });
        return $sales->sortByDesc('count')->values()->all();
    }
    public function sales($r){
        $sales = User::whereDate('date_off','>',$r)->get();
        $sale = collect($sales)->filter(function($user){
            return $user->hasRole('sales');
        });
        return $sale;
    }

    public function amountCostcentersTarget(Request $request){
        $sales = Costcenter::filter($request->all())->get();

        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        $weeks = collect(range($start_week, $end_week ));
        // return $weeks;
        $contacts = $this->contacts2Target12(['dates' => $request['dates']]);
        // return $contacts;

        $salesTargets = $sales->map(function($sale) use($dates,$contacts,$weeks){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            $findContacts = isset($contacts[$sale->name]) ? $contacts[$sale->name] : [];
            $name = $sale->name;
            $targets = $this->groupByWeek2($findTargets->get());

            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'warehouse' => $sale->warehouse,
                'targets' => $targets,
                't0' => isset($targets[$weeks[0]]) ? (int)$targets[$weeks[0]]->sum('order_number') : 0,
                't1' => isset($targets[$weeks[1]]) ? (int)$targets[$weeks[1]]->sum('order_number') : 0,
                't2' => isset($targets[$weeks[2]]) ? (int)$targets[$weeks[2]]->sum('order_number') : 0,
                't3' => isset($targets[$weeks[3]]) ? (int)$targets[$weeks[3]]->sum('order_number') : 0,
                'contacts' => $findContacts,
                'c0' => isset($findContacts[$weeks[0]]) ? $findContacts[$weeks[0]]->values()->sum() : 0,
                'c1' => isset($findContacts[$weeks[1]]) ? $findContacts[$weeks[1]]->values()->sum() : 0,
                'c2' => isset($findContacts[$weeks[2]]) ? $findContacts[$weeks[2]]->values()->sum() : 0,
                'c3' => isset($findContacts[$weeks[3]]) ? $findContacts[$weeks[3]]->values()->sum() : 0,
            ];
        });
        // return $salesTargets->values();
        $showroom = $salesTargets->values()->groupBy(function($salet){
            return $salet->warehouse;
        });

        return $showroom;
    }

    public function amountTarget(Request $request){
        $users = User::filter($request->all())->get();
        $sales = $users->filter(function($user){
            return $user->hasRole('sales');
        });

        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        $weeks = collect(range($start_week, $end_week ));
        // return $weeks;
        $contacts = $this->contacts2Target(['dates' => $request['dates']]);
        // return $contacts;

        $salesTargets = $sales->map(function($sale) use($dates,$contacts,$weeks){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            $findContacts = isset($contacts[$sale->id]) ? $contacts[$sale->id] : [];
            $name = $sale->name;
            $targets = $this->groupByWeek2($findTargets->get());

            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'costcenter' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->name : 'Khac',
                'targets' => $targets,
                't0' => isset($targets[$weeks[0]]) ? (int)$targets[$weeks[0]]->sum('order_number') : 0,
                't1' => isset($targets[$weeks[1]]) ? (int)$targets[$weeks[1]]->sum('order_number') : 0,
                't2' => isset($targets[$weeks[2]]) ? (int)$targets[$weeks[2]]->sum('order_number') : 0,
                't3' => isset($targets[$weeks[3]]) ? (int)$targets[$weeks[3]]->sum('order_number') : 0,
                'contacts' => $findContacts,
                'c0' => isset($findContacts[$weeks[0]]) ? $findContacts[$weeks[0]]->values()->sum() : 0,
                'c1' => isset($findContacts[$weeks[1]]) ? $findContacts[$weeks[1]]->values()->sum() : 0,
                'c2' => isset($findContacts[$weeks[2]]) ? $findContacts[$weeks[2]]->values()->sum() : 0,
                'c3' => isset($findContacts[$weeks[3]]) ? $findContacts[$weeks[3]]->values()->sum() : 0,
            ];
        });
        // return $salesTargets->values();
        $showroom = $salesTargets->values()->groupBy(function($salet){
            return $salet->costcenter;
        });

        return $showroom;
    }

    public function amountTarget2(Request $request){
        $users = User::filter($request->all())->get();
        $sales = $users->filter(function($user){
            return $user->hasRole('sales');
        });

        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        $weeks = collect(range($start_week, $end_week ));
        // return $weeks;
        $contacts = $this->contacts2Target2(['dates' => $request['dates']]);
        // return $contacts;

        $salesTargets = $sales->map(function($sale) use($dates,$contacts,$weeks){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            $findContacts = isset($contacts[$sale->id]) ? $contacts[$sale->id] : [];
            $name = $sale->name;
            $targets = $this->groupByWeek2($findTargets->get());

            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'costcenter' => $sale->costcentersList()->count() ? $sale->costcentersList()->first()->name : 'Khac',
                'targets' => $targets,
                't0' => isset($targets[$weeks[0]]) ? round($targets[$weeks[0]]->sum('amount_number')/1000000,1) : 0,
                't1' => isset($targets[$weeks[1]]) ? round($targets[$weeks[1]]->sum('amount_number')/1000000,1) : 0,
                't2' => isset($targets[$weeks[2]]) ? round($targets[$weeks[2]]->sum('amount_number')/1000000,1) : 0,
                't3' => isset($targets[$weeks[3]]) ? round($targets[$weeks[3]]->sum('amount_number')/1000000,1) : 0,
                'contacts' => $findContacts,
                'c0' => isset($findContacts[$weeks[0]]) ? round($findContacts[$weeks[0]]->sum('amount')/1000000, 1) : 0,
                'c1' => isset($findContacts[$weeks[1]]) ? round($findContacts[$weeks[1]]->sum('amount')/1000000, 1) : 0,
                'c2' => isset($findContacts[$weeks[2]]) ? round($findContacts[$weeks[2]]->sum('amount')/1000000, 1) : 0,
                'c3' => isset($findContacts[$weeks[3]]) ? round($findContacts[$weeks[3]]->sum('amount')/1000000, 1) : 0,
            ];
        });
        // return $salesTargets->values();
        $showroom = $salesTargets->values()->groupBy(function($salet){
            return $salet->costcenter;
        });

        return $showroom;
    }

    public function amountCostcentersTarget2(Request $request){
        $sales = Costcenter::filter($request->all())->get();

        $dates = $request['dates'];
        $start_week = (new Carbon($dates[0]))->weekOfYear;
        $end_week = (new Carbon($dates[1]))->weekOfYear;
        $weeks = collect(range($start_week, $end_week ));
        // return $weeks;
        $contacts = $this->contacts2Target22(['dates' => $request['dates']]);
        // return $contacts;

        $salesTargets = $sales->map(function($sale) use($dates,$contacts,$weeks){
            $findTargets = $sale->salesTargets()->whereBetween('close', $dates);
            $findContacts = isset($contacts[$sale->name]) ? $contacts[$sale->name] : [];
            $name = $sale->name;
            $targets = $this->groupByWeek2($findTargets->get());

            return (object)[
                'id' => $sale->id,
                'name' => $name,
                'warehouse' => $sale->warehouse,
                'targets' => $targets,
                't0' => isset($targets[$weeks[0]]) ? round($targets[$weeks[0]]->sum('amount_number')/1000000,1) : 0,
                't1' => isset($targets[$weeks[1]]) ? round($targets[$weeks[1]]->sum('amount_number')/1000000,1) : 0,
                't2' => isset($targets[$weeks[2]]) ? round($targets[$weeks[2]]->sum('amount_number')/1000000,1) : 0,
                't3' => isset($targets[$weeks[3]]) ? round($targets[$weeks[3]]->sum('amount_number')/1000000,1) : 0,
                'contacts' => $findContacts,
                'c0' => isset($findContacts[$weeks[0]]) ? round($findContacts[$weeks[0]]->sum('amount')/1000000, 1) : 0,
                'c1' => isset($findContacts[$weeks[1]]) ? round($findContacts[$weeks[1]]->sum('amount')/1000000, 1) : 0,
                'c2' => isset($findContacts[$weeks[2]]) ? round($findContacts[$weeks[2]]->sum('amount')/1000000, 1) : 0,
                'c3' => isset($findContacts[$weeks[3]]) ? round($findContacts[$weeks[3]]->sum('amount')/1000000, 1) : 0,
            ];
        });
        // return $salesTargets->values();
        $showroom = $salesTargets->values()->groupBy(function($salet){
            return $salet->warehouse;
        });

        return $showroom;
    }

    protected function contacts2Target($dates){
        $contacts = Order::filter($dates)->get();
        $contacts = $this->groupBySale($contacts);
        $contacts = $contacts->map(function($contacts){
            return $this->groupByWeek($contacts)->map(function($contacts){
                return $this->countByDay($contacts);
            });
        });
        return $contacts;
    }
    protected function contacts2Target12($dates){
        $contacts = Order::filter($dates)->get();
        $contacts = $this->groupByCostcenter($contacts);
        $contacts = $contacts->map(function($contacts){
            return $this->groupByWeek($contacts)->map(function($contacts){
                return $this->countByDay($contacts);
            });
        });
        return $contacts;
    }

    protected function contacts2Target2($dates){
        $contacts = Order::filter($dates)->get();
        $contacts = $this->groupBySale($contacts);
        $contacts = $contacts->map(function($contacts){
            return $this->groupByWeek($contacts);
        });
        return $contacts;
    }

    protected function contacts2Target22($dates){
        $contacts = Order::filter($dates)->get();
        $contacts = $this->groupByCostcenter($contacts);
        $contacts = $contacts->map(function($contacts){
            return $this->groupByWeek($contacts);
        });
        return $contacts;
    }

    protected function groupBySale($contacts){
        return $contacts->groupBy(function($contact){
            return $contact->user_id;
        });
    }

    protected function groupByCostcenter($contacts){
        return $contacts->groupBy(function($contact){
            $sr = $contact->costcentersList();
            return $sr->isNotEmpty() ? $sr->first()->name : 'Khac';
        });
    }

    protected function groupByWeek($contacts){
        return $contacts->groupBy(function($contact){
            $date = new Carbon($contact->created_at);
            return $date->weekOfYear;
        });
    }

    protected function groupByWeek2($contacts){
        return $contacts->groupBy(function($contact){
            $date = new Carbon($contact->close);
            return $date->weekOfYear;
        });
    }

    protected function countByDay($contacts){
        return $contacts->countBy(function($contact){
            $date = new Carbon($contact->created_at);
            return $date->dayOfWeek;
        });
    }

    protected function countByWeek($contacts){
        return $contacts->countBy(function($contact){
            $date = new Carbon($contact->created_at);
            return $date->dayOfWeek;
        });
    }

    public function listOrders()
    {
        return Order::leftjoin('customers','orders.deliver_to','=','customers.id')->orderBy('orders.id','desc')->select('*', 'orders.id as id')->get();
    }

    public function findProducts(Request $r)
    {
        $list = \DB::table('order_lines')->where('order_id',$r->id)->select('*', 'item as name')->get();
        return $list;
    }

    public function showProducts(Request $r)
    {
        $list = \DB::table('order_delivery')->where('order_id',$r->id)->get();
        return $list;
    }

    public function updateDelivery(Request $r)
    {
        // dd($r->all());
        $order = Order::find($r->idorder);
        if ($r->soluongold != null) {
            OrderDelivery::where('quantity',$r->soluongold)->where('order_id',$r->idorder)->where('item',$r->item)->delete();
        }
            $new = new OrderDelivery;
            $new->order_id = $r->idorder;
            $new->item = $r->item;
            $new->order = $r->order;
            $new->product_id = $r->productid;
            $new->quantity = $r->soluong;
            $new->price = $r->price;
            if ($r->discount == 0) {
                $new->amount = $r->price * $r->soluong;
            }else{
                $new->amount = ($r->price * $r->soluong) * (100 - $r->discount) / 100 ;
            }
            $new->discount = $r->discount;
            $new->delivery = $r->date;
            $new->startorder = $order['start_date'];
            $new->warehouse = $order['warehouse'];
            $new->costcenter = $order['costcenter'];
            $new->save();
        return 'Thành Công';
    }

    public function updateall(Request $r)
    {
        OrderDelivery::where('order_id',$r->idorder)->delete();
        $order = Order::find($r->idorder);
        $line = \DB::table('order_lines')->where('order_id',$r->idorder)->get();
        foreach ($line as $key => $value) {
            $new = new OrderDelivery;
            $new->order_id = $value->order_id;
            $new->order = $r->order;
            $new->item = $value->item;
            $new->product_id = $value->product_id;
            $new->quantity = $value->quantity;
            $new->price = $value->price;
            $new->amount = $value->amount;
            $new->discount = $value->discount;
            $new->delivery = $r->date;
            $new->warehouse = $order['warehouse'];
            $new->costcenter = $order['costcenter'];
            $new->startorder = $order['start_date'];
            $new->save();
        }
        return 'Thành Công';
    }

    public function checkAdmin()
    {
        if (\Auth::user()->id == 9002 or \Auth::user()->id == 9001 or \Auth::user()->id == 9074) {
            return [['checkAdmin' => 1]];
        }else{
            return [['checkAdmin' => 0]];
        }
    }

    public function updateorder(Request $r)
    {
        if($r->status == 0){
            $order = Order::find($r->id);
            $orderold = Order::find($r->order);
            $order->status_order = 0;
            $order->status_amout = $orderold->amount;
            $order->save();
        }
        if($r->status == 1){
            $order = Order::find($r->id);
            $orderold = \DB::table('order_lines')->whereIn('id',explode(',', ($r->sp)))->sum('amount');
            $order->status_order = 1;
            // dd($orderold);
            $order->status_amout = $orderold;
            $order->save();
        }
        if($r->status == 2){
            $order = Order::find($r->id);
            $orderold = Order::find($r->order);
            $order->status_order = $orderold->so_number;
            // dd($orderold);
            $order->status_amout = $orderold->amount  - $order->amount;
            $order->save();
        }
    }

    public function baocaolichgiao(Request $request){
        // dd($request->all());
        if (isset($request->t)) {
            $lich = \DB::table('calendar_t')->whereIn('id_table',$request->t)->get();
            $array = array();$a = array();$list = array();$data = array();
            foreach ($lich as $value) {
                $donhang = Order::whereBetween('start_date',[$value->start_t,$value->end_t])->orderBy('delivery_date','asc')->get();
                foreach ($donhang as $v) {
                    $l = \DB::table('calendar_t')->whereDate('start_t','<=',$v->delivery_date)->whereDate('end_t','>=',$v->delivery_date)->first();
                    if (!empty($l)) {
                        $a[$l->t]['tien'][] = $v->amount - $v->pre_pay;
                        $orders = Order::where('so_number',$v->so_number)->get();
                        $a[$l->t]['order'][] = OrderResource::collection($orders);
                        $array[$value->t][$l->t]['orders'] = $a[$l->t]['order'];
                        $array[$value->t][$l->t]['t'] = $l->t;
                        $array[$value->t][$l->t]['v'] = array_sum($a[$l->t]['tien']);
                        $list = array_values($array[$value->t]);
                        $data[$value->t] = $list;
                    }
                }
            }
        }else{
            $lich = \DB::table('calendar')->where('p',$request->p)->where('z',$request->z)->get();
            $array = array();$a = array();$list = array();$data = array();
            foreach ($lich as $value) {
                $donhang = Order::whereBetween('start_date',[$value->start,$value->end])->orderBy('delivery_date','asc')->get();
                foreach ($donhang as $v) {
                    $l = \DB::table('calendar_t')->whereDate('start_t','<=',$v->delivery_date)->whereDate('end_t','>=',$v->delivery_date)->first();
                    if (!empty($l)) {
                        $a[$l->t]['thu'][] = $v->amount - $v->pre_pay;
                        $a[$l->t]['tong'][] = $v->amount;
                        $orders = Order::where('so_number',$v->so_number)->get();
                        $a[$l->t]['order'][] = OrderResource::collection($orders);
                        $array['P'.$value->p.'/Z'.$value->z][$l->t]['orders'] = $a[$l->t]['order'];
                        $array['P'.$value->p.'/Z'.$value->z][$l->t]['t'] = $l->t;
                        $array['P'.$value->p.'/Z'.$value->z][$l->t]['v'] = array_sum($a[$l->t]['thu']);
                        $array['P'.$value->p.'/Z'.$value->z][$l->t]['tong'] = array_sum($a[$l->t]['tong']);
                        $list = array_values($array['P'.$value->p.'/Z'.$value->z]);
                        $data['P'.$value->p.'/Z'.$value->z] = $list;
                    }
                }
            }
        }
        return $data;
    }

    public function searchorder(Request $request){
        $order = Order::whereBetween('delivery_date',[$request->start,$request->end])->pluck('so_number')->toArray();
        if ($request->orders == 'null') {
            foreach ($order as $key => $value) {
                $donhang = OrderResource::collection(Order::where('so_number',$value)->get());
                $o[$key]['so_number'] = $value;
                $o[$key]['order'] = $donhang;
                $o[$key]['product'] = $donhang[0];
                $o[$key]['color'] = 0;
            }
        }else{
            $orders = Order::whereIn('so_number',json_decode($request->orders))->pluck('so_number')->toArray();
            $merge = array_merge($order, $orders);
            $result = array_unique($merge);
            foreach ($result as $key => $value) {
                $k1 = array_search($value, $order);
                $k2 = array_search($value, $orders);
                $donhang = OrderResource::collection(Order::where('so_number',$value)->get());

                if(is_numeric($k1) and is_numeric($k2)){
                    $o[$key]['so_number'] = $value;
                    $o[$key]['color'] = 0;
                    $o[$key]['product'] = $donhang[0];
                    $o[$key]['order'] = $donhang;
                }
                if(!is_numeric($k1) and is_numeric($k2)){
                    $o[$key]['so_number'] = $value;
                    $o[$key]['order'] = $donhang;
                    $o[$key]['product'] = $donhang[0];
                    $o[$key]['color'] = 1;
                }
                if(is_numeric($k1) and !is_numeric($k2)){
                    $o[$key]['so_number'] = $value;
                    $o[$key]['order'] = $donhang;
                    $o[$key]['product'] = $donhang[0];
                    $o[$key]['color'] = 2;
                }
            }
        }
        return $o;
    }

    public function searchordernew(Request $request){
        $order = Order::whereBetween('delivery_date',[$request->start,$request->end])->pluck('so_number')->toArray();
        foreach ($order as $key => $value) {
            $donhang = OrderResource::collection(Order::where('so_number',$value)->get());
            $o[$key]['so_number'] = $value;
            $o[$key]['order'] = $donhang;
            $o[$key]['product'] = $donhang[0];
            $o[$key]['color'] = 0;
        }
        return $o;
    }

    public function listblackfriday(Request $request){
        $order = Order::whereNotNull('event')->get();
        $ivy = Order::where('event',1)->get();
        return ['tong' => $order->count(),'ivy' => $ivy->count(),'orderall' => OrderResource::collection($order),'orderivy' => OrderResource::collection($ivy)];
    }

    public function listblackfridaytype(Request $request){
        if ($request->type == 1) {
            $order = Order::whereNotNull('event')->get();
            return OrderResource::collection($order);
        }
        if ($request->type == 2) {
            $order = Order::where('event',1)->get();
            return OrderResource::collection($order);
        }
    }

}
