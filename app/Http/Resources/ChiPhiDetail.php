<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\LoaiChiPhi;
use App\ChiphiDetail;

class ChiPhi extends JsonResource
{
    protected  $table =  "detail_chiphi";
    public function toArray($request)
    {
        $user = User::where('id',$this->resource['user_id'])->first();
        // $role = \DB::table('roles')->where('id',$this->resource['role_id'])->first();
        // $loaichiphi = LoaiChiPhi::where('id',$this->resource['loaichiphi_id'])->first();
        // $chiphi_detail = ChiphiDetail::where('chiphi_id',$this->id)->get();
        return [
            'id' => $this->id,
            'soluong'=> $this->soluong,
            'noidung'=>$this->noidung,
            'mucdich'=> $this->mucdich,
            'ghichu'=> $this->ghichu,
            'tongtien'=> $this->tongtien,
            'tongtienthuc'=>$this->tongtienthuc,
        ];
    }

}
