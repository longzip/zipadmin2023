<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\LoaiChiPhi;
use App\ChiphiDetail;
use App\ChiPhi;

class ThongBao extends JsonResource
{
    protected  $table =  "thongbao";
    public function toArray($request)
    {
        $user = User::where('id',$this->resource['user_tao'])->first();
        $role = \DB::table('roles')->where('id',$this->resource['role_id'])->first();
        return [
            'id' => $this->id,
            'id_chuyenmuc'=> $this->id_chuyenmuc,
            'noidung'=>$this->noidung,
            'type'=>$this->type,
            'user_see'=>$this->user_see,
            'user_tao'=>$this->user_tao,
            'action'=>$this->action,
            'user'=>$user,
            'role'=>$role,
            'user_name'=> $user->name,
            'created_at'=>$this->created_at,
            // 'role_name'=>$role->name,
        ];
    }

}
