<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\ThiCong;
use App\User;
use App\Order;
use App\BCTC;
use App\CSVC;
use App\Customer;
use NoiThatZip\Attachment\Http\Resources\Attachment as AttachmentResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Http\Resources\BCTC as BCTCResource;
use App\Http\Controllers\Controller;

class ThiCongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $query = $request->all();
        $query['thicong'] = \Auth::user()->hasRole('Thi Công') ? 1 : 0;
        $thicong = ThiCong::orderBy('delivery_date','asc')->filter($query)->latest()->get();

        $lich = $thicong->map(function ($value){
            $sp = json_decode($value->product);
            $array = array();$pro = array();
            // dd($sp);
            foreach ($sp as $k => $v) {
                $v = json_decode($v);
                if ($value->type > 0) {
                	$array[] = '<div>'.$v->item.' / '.$v->sanpham.' : '.$v->quantity.'</div>';
                }else{
                	$array[] = '<div>'.$v->item->name.' : '.$v->quantity.'</div>';
                }
                $pro[$k]['item'] =  $v->item;
                $pro[$k]['amount'] = $v->amount;
                $pro[$k]['quantity'] =  $v->quantity;
            }
            $color = 0;
            if ($value->type == 1) {
            	$color = 1;
            }
            if ($value->type == 2) {
            	$color = 2;
            }
            if ($value->type == 3) {
            	$color = 3;
            }

            $order = Order::where('so_number',$value->so_number)->orderBy('delivery_date','asc')->first();
            $tc = ThiCong::where('so_number',$value->so_number)->whereDate('delivery_date','<',$value->delivery_date)->orderBy('delivery_date','asc')->sum('amount');
            $prepay = empty($order) ? 0 : $order->pre_pay;
            return [
                'id' => $value->id,          
                'costcenter' => $value->costcenter,           
                'order' => $value->so_number.'<br>'.\Carbon\Carbon::parse($value->start_date)->format('d-m-Y'),             
                'date' => $value->delivery_date,
                'product' => implode('', $array),
                'products' => $pro,
                'name' => $value->name,
                'type' => $value->type,
                'start_date' => $value->start_date,
                'phone' => $value->phone,
                'so_number' => $value->so_number,
                'address' => $value->address,
                'status' => $value->status,
                'prepay' => $tc + $prepay,
                'total' => empty($order) ? 0 : $order->amount,
                'qgg' => $value->qgg,
                'fee_vc' => $value->fee_vc,
                'fee_ld' => $value->fee_ld,
                'vat' => $value->vat,
                'money' => $value->amount ,              
                'goc' => $value->amount,              
                'note' => $value->note,              
                'giovaonm' => $value->giovaonm,              
                'thongtinnhaxe' => $value->thongtinnhaxe,              
                'giohenkhach' => $value->giohenkhach,              
                'admin' => \Auth::user()->hasRole('BP Admin') ? 1 : 0,
                'thicong' => User::whereIn('id',json_decode($value->thicong))->get(),
                'color' => $color,
            ];  
        });
                // dd($lich);

        $orders = Order::whereBetween('delivery_date',[$request->dates])->orderBy('delivery_date','asc')->where('so_number', 'LIKE', "%$request->name%")->whereNotIn('so_number',$thicong->pluck('so_number'))->get();
        $order = $orders->map(function ($value){
            $cus = Customer::find($value->deliver_to);
            $sp = \DB::table('order_lines')->where('order_id',$value->id)->get();
            $array = array();$pro = array();
            foreach ($sp as $k => $v) {
                $array[] = '<div>'.$v->item.' : '.$v->quantity.'</div>';
                $pro[$k]['item'] =  \DB::table('products')->select('id','code as name')->where('code',$v->item)->first();
                $pro[$k]['amount'] = $v->amount;
                $pro[$k]['quantity'] =  $v->quantity;
            }
            $color = 100;
            if ($value->color == 1) {
                $color = 4;
            }
            if ($value->color == 2) {
                $color = 5;
            }
         
            return [
                'idorder' => $value->id,         
                'costcenter' => $value->costcenter,           
                'order' => $value->so_number.'<br>'.\Carbon\Carbon::parse($value->start_date)->format('d-m-Y'),             
                'start_date' => $value->start_date,
                'date' => $value->delivery_date,
                'so_number' => $value->so_number,
                'product' => implode('', $array),
                'products' => $pro,
                'type' => 0,
                'name' => $cus->name,
                'phone' => $cus->phone,
                'address' => $cus->address_line1,
                'status' => 0,
                'total' => $value->amount,              
                'prepay' => $value->pre_pay,              
                'money' => $value->amount - $value->pre_pay,              
                'note' => '',
                'qgg' => $value->qgg,
                'fee_vc' => $value->fee_vc,
                'fee_ld' => $value->fee_ld,
                'vat' => $value->vat,
                'admin' => \Auth::user()->hasRole('BP Admin') ? 1 : 0,
                'thicong' => '',
                'giohenkhach' => '',
                'thongtinnhaxe' => '',
                'color' => $color,
                'giovaonm' => '',
            ];  
        });
        if (\Auth::user()->hasRole('Thi Công')) {
        	if ($lich->count() > 0) {
	            $tc = $lich;
	        }else{
	            $tc = array();
	        }
        }else{
	        if ($lich->count() > 0) {
	            $tc = $lich->merge($order);
	        }else{
	            $tc = $order;
	        }
        }

        $page = (int) $request->page;

        $perPage = 15;
        $page = null; 
        $options = [];

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $paginated =  new LengthAwarePaginator(collect($tc)->forPage($page, $perPage), collect($tc)->count(), $perPage, $page, $options);

        return $paginated;

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
        $nv = array();
        foreach ($request->thicong as $key => $value) {
            $nv[] = $value['id'];
        }
        $new = new ThiCong;
        $new->so_number = $request->donhang ? $request->donhang['so_number'] : null;        
        $new->address = $request->address;
        $new->phone = $request->phone;
        $new->product =  $request->choiceproduct['item'] ; 
        $new->amount = $request->money;
        $new->thicong = json_encode($nv);
        $new->soluong = $request->soluong;
        $new->delivery_date = $request->date;
        $new->start_date = $request->donhang ? $request->donhang['start_date'] : null;
        $new->note = $request->note;
        $new->costcenter = $request->donhang ? $request->donhang['costcenter'] : null;
        $new->name = $request->name;
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
             

    }

    public function up(Request $request)
    {
        // dd($request->all());
        $item = array();
        $list = array();
        $listnew = array();
        foreach ($request->item as $key => $value) {
            $item[json_decode($value)->item->name] = json_decode($value); 
        }
        // dd($item);
        $amount = 0;
        $amountgiaosau = 0;

        $dagiao = ThiCong::where('so_number',$request->so_number)->get();

        if ($dagiao->count() > 0) {
            $tinh = ThiCong::where('so_number',$request->so_number)->orderBy('id','desc')->first();
            foreach (json_decode($tinh->product) as $k => $value) {
                if (!empty($item[json_decode($value)->item->name])) {
                    $check = json_decode($value)->quantity - $item[json_decode($value)->item->name]->quantity;
                    if ($check > 0) {
                        $list[$k]['item'] =  \DB::table('products')->select('id','code as name')->where('code',json_decode($value)->item->name)->first();
                        $list[$k]['amount'] = $item[json_decode($value)->item->name]->amount * $check / json_decode($value)->quantity;
                        $list[$k]['quantity'] =  $check;
                        $listnew[] = json_encode($list[$k]);
                    }
                }

            }
        }else{
            $sp = \DB::table('order_lines')->where('order_id',$request->idorder)->get();
            foreach ($sp as $k => $value) {
                if (!empty($item[$value->item])) {
                    $check = $value->quantity - $item[$value->item]->quantity;
                    if ($check > 0) {
                        $list[$k]['item'] =  \DB::table('products')->select('id','code as name')->where('code',$value->item)->first();
                        $list[$k]['amount'] = $item[$value->item]->amount * $check / $value->quantity;
                        $list[$k]['quantity'] =  $check;
                        $listnew[] = json_encode($list[$k]);
                    }
                }
            }
        }
        // dd($tong);
        if ($request->id) {
            $new = ThiCong::find($request->id);
            $new->so_number = $request->so_number;        
            $new->address = $request->address;
            $new->phone = $request->phone;
            $new->product = json_encode($request->item); 
            $new->amount = $request->money;
            $new->thicong = json_encode($request->thicong);
            $new->delivery_date = $request->date;
            if ($request->costcenter && $request->costcenter != 'null') {
                $new->costcenter =  $request->costcenter ;
            }
            if ($request->start_date) {
                $new->start_date =  $request->start_date ;
            }
            $new->giovaonm = $request->giovaonm ? $request->giovaonm : null;
            $new->thongtinnhaxe = $request->thongtinnhaxe ? $request->thongtinnhaxe : null;
            $new->giohenkhach = $request->giohenkhach ? $request->giohenkhach : null;
            $new->note = $request->note;
            $new->name = $request->name;
            $new->fee_ld = $request->fee_ld;
            $new->qgg = $request->qgg;
            $new->vat = $request->vat;
            $new->fee_vc = $request->fee_vc;
            $new->name = $request->name;
            $new->save();   
            if(count($listnew) > 0) {
                $new = new ThiCong;
                $new->so_number = $request->so_number;        
                $new->address = $request->address;
                $new->phone = $request->phone;
                $new->product = json_encode($listnew); 
                $new->amount = $request->total - $request->money - $request->prepay;
                $new->thicong = '[]';
                $new->delivery_date =   date ( 'Y-m-d' , strtotime ( '+7 day' , strtotime ( $request->date ) ) );
                if ($request->start_date) {
                    $new->start_date =  $request->start_date ;
                }
                $new->giovaonm = null;
                $new->thongtinnhaxe =  null;
                $new->giohenkhach = null;
                $new->note = null;
                if ($request->costcenter && $request->costcenter != 'null') {
                    $new->costcenter =  $request->costcenter ;
                }
                $new->name = $request->name;
                $new->save();
            }
        }else{
            $new = new ThiCong;
            $new->so_number = $request->so_number;        
            $new->address = $request->address;
            $new->phone = $request->phone;
            $new->product = json_encode($request->item); 
            $new->amount = $request->money;
            $new->thicong = $request->thicong ? json_encode($request->thicong) : '[]';
            $new->delivery_date = $request->date;
            $new->start_date = $request->start_date ? $request->start_date : null;
            $new->giovaonm = $request->giovaonm ? $request->giovaonm : null;
            $new->thongtinnhaxe = $request->thongtinnhaxe ? $request->thongtinnhaxe : null;
            $new->giohenkhach = $request->giohenkhach ? $request->giohenkhach : null;
            $new->note = $request->note;
            $new->costcenter = $request->costcenter;
            $new->fee_ld = $request->fee_ld;
            $new->qgg = $request->qgg;
            $new->vat = $request->vat;
            $new->fee_vc = $request->fee_vc;
            $new->name = $request->name;
            $new->save();
            if(count($listnew) > 0) {
                $new = new ThiCong;
                $new->so_number = $request->so_number;        
                $new->address = $request->address;
                $new->phone = $request->phone;
                $new->product = json_encode($listnew); 
                $new->amount = $request->total - $request->money - $request->prepay;
                $new->thicong = '[]';
                $new->delivery_date =   date ( 'Y-m-d' , strtotime ( '+7 day' , strtotime ( $request->date ) ) );
                $new->start_date = $request->start_date ? $request->start_date : null;
                $new->giovaonm = null;
                $new->thongtinnhaxe =  null;
                $new->giohenkhach = null;
                $new->note = null;
                $new->costcenter = $request->costcenter;
                $new->name = $request->name;
                $new->save();
            }
        }
         

    }

    public function add(Request $request)
    {
        $item = array(); $amount=0; $itemnew = array();
        // dd($request->item);
        if (!empty($request->item)) {
	        foreach ($request->item as $key => $value) {
	            $item[$key]['item'] = json_decode($value)[0] ;
	            $item[$key]['quantity'] = json_decode($value)[3] ;
	            $item[$key]['amount'] = json_decode($value)[2] ;
	            $item[$key]['sanpham'] = json_decode($value)[1] ;

	            $itemnew[$key] = json_encode($item[$key]);
	            $amount += json_decode($value)[2] ;
	        }
        }

        $new = new ThiCong;
        $new->so_number = $request->so_number;        
        $new->address = $request->address;
        $new->phone = $request->phone;
        $new->product = json_encode($itemnew); 
        $new->amount = $amount;
        $new->thicong = json_encode($request->thicong);
        $new->delivery_date = $request->date;
        $new->start_date = null;
        $new->giovaonm = $request->giovaonm ? $request->giovaonm : null;
        $new->thongtinnhaxe = $request->thongtinnhaxe ? $request->thongtinnhaxe : null;
        $new->giohenkhach = $request->giohenkhach ? $request->giohenkhach : null;
        $new->note = $request->note;
        $new->costcenter = $request->costcenter;
        $new->fee_ld = $request->fee_ld;
        $new->qgg = $request->qgg;
        $new->vat = $request->vat;
        $new->fee_vc = $request->fee_vc;
        $new->name = $request->name;
        $new->type = $request->pos;
        $new->save();
    }

    public function duyetthicong(Request $request)
    {
        if (\Auth::user()->hasRole('BP Admin')) {
            $new = ThiCong::find($request->id);
            $new->status = 1;
            $new->save();     
        }
    }

    public function delete(Request $request)
    {
        if (\Auth::user()->hasRole('BP Admin')) {
            $new = ThiCong::find($request->id);
            $new->status = 1;
            $new->delete();     
        }
    }

    public function getdonhang(Request $request)
    {
        $tc = array();
        $all = ThiCong::where('status',1)->pluck('thicong','id');
        foreach ($all as $key => $value) {
            foreach (json_decode($value) as $v) {
                if (\Auth::user()->id == $v) {
                    $tc[] = $key;
                }
            }   
        }
        $data = ThiCong::whereIn('id', $tc)->orderBy('delivery_date','desc')->get();
        return $data->map(function ($value){
            $sp = json_decode($value->product);
            $pro = array();
            // dd($sp);
            foreach ($sp as $k => $v) {
                $v = json_decode($v);
                $pro[$k]['item'] =  $v->item;
                $pro[$k]['amount'] = $v->amount;
                $pro[$k]['quantity'] =  $v->quantity;
                $pro[$k]['pos'] =  'Nhà Máy';
            }
            return [
                'id' => $value->id,          
                'costcenter' => $value->costcenter,           
                'date' => $value->delivery_date,
                'info' => $value->name.' / '.$value->phone.' / '.$value->delivery_date,
                'phone' => $value->phone,
                'so_number' => $value->so_number,
                'address' => $value->address,
                'name' => $value->name,
                'type' => $value->type,
                'start_date' => $value->start_date,
                'phone' => $value->phone,
                'so_number' => $value->so_number,
                'address' => $value->address,
                'product' => $pro,
                'status' => $value->status,
            ];
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bdedit(Request $request)
    {
        $item = array(); $amount=0; $itemnew = array();
        // dd($request->item);
        if (!empty($request->item)) {
            foreach ($request->item as $key => $value) {
                $item[$key]['item'] = json_decode($value)[0] ;
                $item[$key]['quantity'] = json_decode($value)[3] ;
                $item[$key]['amount'] = json_decode($value)[2] ;
                $item[$key]['sanpham'] = json_decode($value)[1] ;

                $itemnew[$key] = json_encode($item[$key]);
                $amount += json_decode($value)[2] ;
            }
        }
        // dd($itemnew);
        $new = ThiCong::find($request->id);
        $new->address = $request->address;
        $new->phone = $request->phone;
        $new->product = json_encode($itemnew); 
        $new->amount = 0;
        $new->thicong = json_encode($request->thicong);
        $new->delivery_date = $request->date;
        if ($request->start_date) {
            $new->start_date =  $request->start_date ;
        }
        $new->giovaonm = $request->giovaonm ? $request->giovaonm : null;
        $new->thongtinnhaxe = $request->thongtinnhaxe ? $request->thongtinnhaxe : null;
        $new->giohenkhach = $request->giohenkhach ? $request->giohenkhach : null;
        $new->note = $request->note;
        $new->save(); 
    }

    public function addbctc(Request $request)
    {
        // dd($request->all());
        if($request->item){
            foreach ($request->item as $key => $value) {
                $a = json_decode($value);
                if ($a->pos == 'Showroom') {
                    $csvc = new CSVC;
                    $csvc->showroom = $request->costcenter;
                    $csvc->code = $a->item;
                    $csvc->sanpham = $a->item;
                    $csvc->so_luong = $a->quantity;
                    $csvc->save();
                }
            }
        }
        $n = new BCTC;
        $n->status = $request->status;
        $n->type = $request->type;
        $n->mota = $request->mota;
        $n->dathu = $request->dathu;
        $n->phatsinh = $request->phatsinh;
        $n->note = $request->note;
        $n->id_tc = $request->id_tc;
        $n->save();
        if($request->hasFile('file')){
            $user = auth('api')->user();
            foreach ($request->file('file') as $key => $value) {
                $media = $n->addMedia($value)
                ->toMediaCollection('images','public');
                $image = $n->attach([
                    'src' => $media->getUrl(),
                ], $user);
            }
        }
        
    }

    public function editbctc(Request $request)
    {
        // dd($request->all());
        $n = BCTC::find($request->id);
        // dd($n);
        $n->status = $request->status;
        $n->type = $request->type;
        $n->mota = $request->mota;
        $n->dathu = $request->dathu;
        $n->phatsinh = $request->phatsinh;
        $n->note = $request->note;
        $n->id_tc = $request->id_tc;
        $n->save();
        if($request->hasFile('file')){
            $user = auth('api')->user();
            foreach ($request->file('file') as $key => $value) {
                $media = $n->addMedia($value)
                ->toMediaCollection('images','public');
                $image = $n->attach([
                    'src' => $media->getUrl(),
                ], $user);
            }
        }
        if($request->item){
            foreach ($request->item as $key => $value) {
                $a = json_decode($value);
                if ($a->pos == 'Showroom') {
                    $csvc = new CSVC;
                    $csvc->showroom = $request->costcenter;
                    $csvc->code = $a->item;
                    $csvc->sanpham = $a->item;
                    $csvc->so_luong = $a->quantity;
                    $csvc->save();
                }
            }
        }

        
    }


    public function deletebctc(Request $request)
    {
        BCTC::find($request->id)->delete();
    }

    public function bctc(Request $request)
    {
        $dc = BCTC::filter($request->all())->latest()->paginateFilter();
        return BCTCResource::collection($dc);
    }
}
