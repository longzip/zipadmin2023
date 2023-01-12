<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Bank;
use App\Order;
use App\Costcenter;
use App\DanhSachVay;

class Thu extends JsonResource
{
    protected  $table =  "Thu";
    public function toArray($request)
    {
        // $user = User::where('id',$this->resource['user_id'])->first();
        // $role = \DB::table('roles')->where('id',$this->resource['role_id'])->first();
        $bank = Bank::where('id',$this->resource['bank_id'])->first();
        $order = Order::where('id',$this->resource['donhang_id'])->first();
        $danhsachvay = DanhSachVay::where('id',$this->resource['danhsachvay_id'])->first();
        $showroom = Costcenters::where('id',$this->resource['showroom_id'])->first();
        // $ketoan = \DB::table('model_has_roles')->where('role_id',10)->where('model_id',\Auth::User()->id)->get();
        // $bangiamdoc =\DB::table('model_has_roles')->where('role_id',8)->where('model_id',\Auth::User()->id)->get();

        return [
            'id' => $this->id,
            'loaithu'=> $this->loaithu,
            'order'=>$order,
            'bank'=>$bank,
            'order_name'=> $order->name,
            'bank_name'=>$bank->name,
            'ngaythu'=> $this->ngaythu,
            'ngaytienve'=> $this->ngaytienve,
            'sotien'=> $this->sotien,
            'mota'=>$this->mota,
            'danhsachvay'=>$danhsachvay,
            'showroom'=>$showroom,
            'showroom_name'=>$showroom->name,
            'danhsachvay_name'=>$danhsachvay->name,

        ];
    }

}
