<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Order;
use Auth;
use App\OrderLine;
use App\Customer;
use App\Exports\OrdersExport;
use App\Exports\OrdersExportDoanhThu;
use Carbon;

class OrdersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('orders.index');
    }

    public function export()
    {
        $orders = Order::all()->take(1);
        return response()->view('orders.xml', compact('orders',$orders))->header('Content-Type', 'text/xml');
    }
    public function fillOrder(Request $request){
        $ex = explode("-",$request->range);
        $start = new Carbon(date('Y-m-d',strtotime(trim($ex[0]))));
        $end = new Carbon(date('Y-m-d',strtotime(trim($ex[1]))));
        $orders = \DB::table('order_delivery')->select('order','item','quantity','price','amount','delivery','startorder','warehouse','costcenter')->WhereBetween('delivery',[$start,$end])->OrderBy('delivery','desc')->get();
        foreach ($orders as $od){
            echo
                "<tr>",
                "<td>".$od->order."</td>",
                "<td>".$od->item."</td>",
                "<td>".$od->quantity."</td>",
                "<td>".$od->price."</td>",
                "<td>".$od->amount."</td>",
                "<td>".$od->delivery."</td>",
                "<td>".$od->startorder."</td>",
                "<td>".$od->warehouse."</td>",
                "<td>".$od->costcenter."</td>",
                "</tr>";
        }
        // dd($start->date());
    }
    public function fillOrderDoanhThu(Request $request){
        $ex = explode("-",$request->range);
        $start = new Carbon(date('Y-m-d',strtotime(trim($ex[0]))));
        $end = new Carbon(date('Y-m-d',strtotime(trim($ex[1]))));

        $orders = \DB::table('orders')->leftJoin('users', 'orders.user_id', '=', 'users.id')->select('orders.id','so_number','warehouse','start_date','costcenter','delivery_date','pre_pay','amount','vat','fee_ld','fee_vc','qgg','user_id','users.name')->whereBetween('start_date',[$start,$end])->OrderBy('start_date','desc')->get();
        foreach ($orders as $value) {
            $orders_line = \DB::table('order_lines')->where('order_id',$value->id)->leftJoin('products', 'order_lines.product_id', '=', 'products.id')->select('order_id','item','quantity','order_lines.price','amount','products.point')->get();
            foreach ($orders_line as $val) {
                $a[] =
                    "<tr>".
                    "<td>".$val->order_id."</td>".
                    "<td>".$val->item."</td>".
                    "<td>".$val->quantity."</td>".
                    "<td>".$val->price."</td>".
                    "<td>".$val->amount."</td>".
                    "<td>".$value->so_number."</td>".
                    "<td>".$value->warehouse."</td>".
                    "<td>".$value->delivery_date."</td>".
                    "<td>".$value->costcenter."</td>".
                    "<td>".$value->start_date."</td>".
                    "<td>".$value->pre_pay."</td>".
                    "<td>".$value->amount."</td>".
                    "<td>".$value->vat."</td>".
                    "<td>".$value->fee_ld."</td>".
                    "<td>".$value->fee_vc."</td>".
                    "<td>".$value->qgg."</td>".
                    "<td>".$val->quantity * $val->price."</td>".
                    "<td>".$value->name."</td>".
                    "<td>".$val->quantity * $val->point."</td>".
                    "</tr>";
            }
        }
        echo implode('', $a);
    }

        // dd($start->date());

    public function exportcsv(){
        // $orders = Order::OrderBy('delivery_date','desc')->pluck('so_number','description','warehouse','costcenter','amount','delivery_date');
        $orders = \DB::table('order_delivery')->select('order','item','quantity','price','amount','delivery','startorder','warehouse','costcenter')->OrderBy('delivery','desc')->get();
        return view('exportOrder',compact('orders'));
    }
    public function exportcsv2(){
        $orders = \DB::table('orders')->leftJoin('users', 'orders.user_id', '=', 'users.id')->select('orders.id','so_number','warehouse','start_date','costcenter','delivery_date','pre_pay','amount','vat','fee_ld','fee_vc','qgg','user_id','users.name')->get();
// dd($orders);
        foreach ($orders as $value) {
            $orders_line = \DB::table('order_lines')->where('order_id',$value->id)->leftJoin('products', 'order_lines.product_id', '=', 'products.id')->select('order_id','item','quantity','order_lines.price','amount','products.point')->get();
// dd($orders);

            foreach ($orders_line as $val) {
                $b = array_change_key_case((array)$val,CASE_UPPER);
                $get_order =array_merge($b, (array) $value);
                $get_arr[]= $get_order;
                $a=$get_arr;
            }
        }
            // dd($a);

        return view('exportDoanhThu',compact('a'));
    }
    function excel(Request $request)
    {
        return Excel::download(new OrdersExport([$request->start,$request->end]), 'DonHang.xlsx');
    }
    function excelDT(Request $request)
    {
        // dd($request->end);
        return Excel::download(new OrdersExportDoanhThu([$request->start,$request->end]), 'DoanhThu.xlsx');
    }
    public function import(Request $request)
    {
        //Excel::import(new SalesOrdersImport, $request->file('file_excell'));

        if($request->hasFile('file_excell'))
        {
            $path = $request->file('file_excell')->getRealPath();
            $data = Excel::load($path, function($reader) {
                //$reader->limitRows(10);
                //Excel::selectSheets('DS')->load();
                $reader->setDateFormat('Y-m-d');
                $reader->setDateColumns(array(
                    'ngay_dat_hang',
                    'ngay_giao_hang'
                ));
            })->get();

            if (!empty($data) && $data->count()) {
                foreach ($data as $key => $row) {
                    Order::updateOrCreate([
                        'description'  => $row['khu_vuc'] . $row['sdh'],
                        'your_ref'  => $row['sdh'],
                        'resource'  => $row['khu_vuc'],
                        'ordered_by'  => $row['ma_nv'],
                        'deliver_to'  => $row['sdt'],
                        'delivery'  => $row['ngay_giao_hang'],
                        'invoice_to'  => $row['sdt'],
                        'warehouse'  => $row['khu_vuc'],
                        'delivery_method'  => 'HNI_RC',
                        'costcenter'  => 'HNI_RC'
                    ]);
                }
            }
        }
        return redirect('/')->with('success', 'All good!');
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
        //
    }

    public function exportPdf($id){
       $order = Order::find($id);
       $excel = Excel::create('so-1', function($excel) {
            
        });
       $excel->sheet('Sheetname', function($sheet) use($order) {
                $sheet->loadView('orders.excell', array('order' => $order));
        });

       return $excel->download('xls');
    }
    public function print($id){
        $order = Order::find($id);
        $customer = Customer::find($order->deliver_to);
       return view('orders.print', ['order' => $order, 'customer' => $customer]);
    }
}
