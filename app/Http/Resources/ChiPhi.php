<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\LoaiChiPhi;
use App\ChiPhiDetail;

class ChiPhi extends JsonResource
{
    protected  $table =  "chiphi";
    public function toArray($request)
    {
        $user = User::where('id',$this->resource['user_id'])->first();
        $role = \DB::table('roles')->where('id',$this->resource['role_id'])->first();
        $loaichiphi = LoaiChiPhi::where('id',$this->resource['loaichiphi_id'])->first();
        $chiphi_detail = ChiPhiDetail::where('chiphi_id',$this->id)->get();
        $ketoan = \DB::table('model_has_roles')->where('role_id',10)->where('model_id',\Auth::User()->id)->get();
        $bangiamdoc =\DB::table('model_has_roles')->where('role_id',8)->where('model_id',\Auth::User()->id)->get();
        if($ketoan->isEmpty()){
            $checkkt = 0;
        } else {
            $checkkt = 1;
        }
        if($bangiamdoc->isEmpty()){
            $checkgd = 0;
        } else {
            $checkgd = 1;
        }
        return [
            'id' => $this->id,
            'tendexuat'=> $this->name,
            'user'=>$user,
            'role'=>$role,
            'user_name'=> $user->name,
            'role_name'=>$role->name,
            'loaichiphi'=>$loaichiphi,
            'loaichiphi_name'=>$loaichiphi->name,
            'tongchiphi'=> $this->TongChiPhi,
            'tinhtrang'=> $this->TinhTrang,
            'tongtienthuc'=> $this->TongTienThuc,
            'detail'=>$chiphi_detail,
            'item'=>$chiphi_detail,
            'duyet'=>$this->duyet,
            'ketoan'=>$checkkt,
            'bangiamdoc'=>$checkgd,
            'comments'   =>  $this->comments,

        ];
    }

}
