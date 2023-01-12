<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\ArrayToXml\ArrayToXml;

class CustomersController extends Controller
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
        return view('customers.index');
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

    public function import(Request $request) 
    {
        if($request->hasFile('file_excell'))
        {
            $path = $request->file('file_excell')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();

            if (!empty($data) && $data->count()) {
                foreach ($data as $key => $row) {
                    Customer::updateOrCreate([
                        'last_name'  => $row['name'],
                        'phone'  => $row['tel'],
                        'address_line1'  => $row['add1'],
                    ]);
                }
            }
        }
        
        return redirect('/')->with('success', 'All good!');
    }

    public function export()
    {
        $customers = Customer::where('created_at','>','2019/02/23')->get()->toArray();
        //return $customers;
        //return ArrayToXml::convert($customers);
        return response()->view('customers.xml', compact('customers',$customers))->header('Content-Type', 'text/xml');
    }
}
