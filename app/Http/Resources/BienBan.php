<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Decision;
use App\GroupQuyDinh;

class BienBan extends JsonResource
{
    public function toArray($request)
    {
    
        $decision = Decision::where('id',$this->decision_id)->first();
        $userPhat = User::where('id',$this->user_id)->first();
        $userCreate = User::where('id',$this->user_create)->first();
        $group = GroupQuyDinh::where('name',$this->name_quydinh)->first();
        
        return [
            'name_quydinh' => $this->name_quydinh,
            'name_quytrinh' => $this->name_quytrinh,
            'id' => $this->id,
            'group' =>  $group,
            'name' => $this->name,
            //'ma' => $decision->name,
            //'detail' => $decision->detail,
            //'tieude' => $decision->sapo,
            'decision' => $decision,
            'resources' => $userPhat,
            'date' => (string)$this->created_at,
            'note' => $this->note,
            'userPhat' => $userPhat->name,
            'userCreate' =>  $userCreate->name,
             'ngayvipham' => $this->ngayvipham,
             'reason'   => $this->reason,
              'price'  => $this->price,
              'email' => $this->email,
              'trangthai' =>$this->trangthai,
        ];
    }
}
