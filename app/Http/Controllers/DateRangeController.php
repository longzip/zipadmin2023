<?php
// namespace App\Http\Controllers;
// use Illuminate\Http\Request;
// use DB;
// class DateRangeController extends Controller
// {
    // function index(Request $request)
    // {
    //  if(request()->ajax())
    //  {
    //   if(!empty($request->from_date))
    //   {
    //    $data = DB::table('order_lines')
    //      ->whereBetween('delivery', array($request->from_date, $request->to_date))
    //      ->get();
    //   }
    //   else
    //   {
    //    $data = DB::table('order_lines')
    //      ->get();
    //   }
    //   return datatables()->of($data)->make(true);
    //  }
    //  return view('exportOrder');
    // }
//     function index()
//     {
//      return view('exportOrder');
//     }

//     function fetch_data(Request $request)
//     {
//      if($request->ajax())
//      {
//       if($request->from_date != '' && $request->to_date != '')
//       {
//        $data = DB::table('order_lines')
//          ->whereBetween('delivery', array($request->from_date, $request->to_date))
//          ->get();
//       }
//       else
//       {
//        $data = DB::table('order_lines')->orderBy('delivery', 'desc')->get();
//       }
//       echo json_encode($data);
//      }
//     }
// }
// ?>