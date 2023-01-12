<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LoaiChiPhi;
use App\ChiPhi;
use App\Http\Resources\ThongBao as ThongBaoResource;
class ThongBaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {

    }
    public function store(Request $request)
    {

    }
    public function show(LoaiChiPhi $loaichiphi)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function destroy($id)
    {


}