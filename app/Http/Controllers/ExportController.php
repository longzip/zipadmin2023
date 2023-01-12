<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\GiaoExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function giao(Request $r)
    {
    	return Excel::download(new GiaoExport, 'Giao '.Carbon::now('Asia/Ho_Chi_Minh')->toDateString().'.xlsx');
    }
}
