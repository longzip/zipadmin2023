<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\ChiPhi;
use App\LoaiChiPhi;

class ChiPhi extends JsonResource
{
    public function toArray($request)
    {
        // $nameRole = \DB::table('roles')->whereIn('id',json_decode($this->role_id))->pluck('name');

    //  $decision = \DB::table('decision')->where('quytrinh_id',$this->id)->pluck('id_ma');
    // // $code = GroupQuyDinh::where('id',$this->group_id)->first();
    //  $codes =  \DB::table('quy_trinh')->where('id',$this->id)->first();
    //  $maCode  = [];
    //  foreach($decision as $key => $value){
    //     if($value > 0  && $value <10 ){
    //        $maCode[]  = $codes->code.'-00'.$value ; 
    //     }
    //     if($value > 9  && $value <100 ){
    //         $maCode[]  = $codes->code.'-0'.$value ; 
    //      }
    //      if($value > 99){ 
    //         $maCode[]  = $codes->code.'-'.$value ; 
    //      }
          
    //  }
     
        return [
            'id' => $this->id,
            'name' => $this->name,
            ''
        ];
    }
}
