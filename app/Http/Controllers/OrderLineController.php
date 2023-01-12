<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Order;
use App\OrderLine;
use App\Customer;

class OrderLineController extends Controller
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
        return view('orderline.index');
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
                    'ngay_giao_hang'
                ));
                //$reader->dd();
            })->get();

            if (!empty($data) && $data->count()) {
                foreach ($data as $key => $row) {
                    
                    $customer = Customer::updateOrCreate([
                        'phone' => $row['so_dien_thoai']
                    ]);

                    $order = Order::updateOrCreate([
                        'so_number'  => $row['so_don_hang']
                    ]);

                    $order->description = 'TC_' . $row['so_don_hang'];
                    $order->your_ref = 'PO:' . $row['so_don_hang'];
                    $customer->name = $row['ten_khach_hang'];
                    $customer->address_line1 = $row['dia_chi'];
                    $customer->title = $row['title'];
                    $order->ordered_by = $customer->id;
                    $order->deliver_to = $customer->id;
                    $order->invoice_to = $customer->id;
                    $order->resource = $row['khu_vuc'];
                    $order->warehouse = $row['kho'];
                    $order->delivery_method = 'HNI_RC';
                    $order->costcenter = 'HNI_RC';
                    $order->save();
                    $customer->save();

                    OrderLine::updateOrCreate([
                        'item'  => $row['ma_san_pham'],
                        'order_id' => $order->id,
                        'quantity'  => $row['so_luong'],
                        'price' => $row['don_gia'],
                        'amount' => $row['doanh_so_ban'],
                        'discount' => $row['discount'],
                        'delivery' => $row['ngay_giao_hang'],
                        'warehouse' => $order->warehouse,
                        'recipe' => $row['recipe'],
                        'costcenter' => 'HNI_RC',
                        'resource' => $order->resource,
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
}
