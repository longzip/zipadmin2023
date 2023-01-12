<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContactImport;

class ProductsController extends Controller
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
        return view('products.index');
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
                // Dump results and die
                 //$reader->dd();
            })->get();

            if (!empty($data) && $data->count()) {
                foreach ($data as $key => $row) {
                    $product = Product::updateOrCreate([
                        'code'  => $row['ma_san_pham']
                    ]);
                    $product->name = $row['ten_san_pham'];
                    $product->price = $row['don_gia'];
                    $product->point = $row['diem_thuong'];
                    $product->save();
                }
            }
        }
        
        return redirect('/')->with('success', 'All good!');
    }

    public function importkh(Request $request) 
    {
        // dd($request->all());
        if($request->hasFile('file_excell'))
        {
            Excel::import(new ContactImport,request()->file('file_excell'));
        }
        
        // return redirect('/')->with('success', 'All good!');
    }

    public function getim()
    {
        return view('products.importkh');
    }
}
