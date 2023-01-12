<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class ExportsController extends Controller
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

    public function export()
    {
        $customers = Customer::find(1);
        return ArrayToXml::convert($customers);
        //return response()->view('glentries.xml', compact('customers',$customers))->header('Content-Type', 'text/xml');
    }
}
